<?php

namespace Albet\Asmvc\Core\Routing;

use Albet\Asmvc\Core\Middleware;
use Albet\Asmvc\Core\Exceptions\CallingToUndefinedMethod;
use Closure;
use FastRoute\Dispatcher;
use FastRoute\RouteCollector;

use function FastRoute\simpleDispatcher;

class Route
{
    private RoutesCollection $definedRouteCollection;

    public function __construct()
    {
        $this->definedRouteCollection = new RoutesCollection();
    }

    public function __call($method, $arguments)
    {
        if ($method === 'map') {
            return $this->map();
        } else if ($method === 'triggerRoute') {
            return $this->triggerRoute();
        } else {
            throw new CallingToUndefinedMethod($method);
        }
    }

    public static function __callStatic($method, $arguments)
    {
        if ($method === 'map') {
            return (new self)->map();
        } else if ($method === 'triggerRoute') {
            return (new self)->triggerRoute();
        } else {
            throw new CallingToUndefinedMethod($method);
        }
    }

    private function map(): self
    {
        $routeFile = base_path('App/Routes/routes.php');
        if (!file_exists($routeFile)) {
            throw new NoRouteFileException();
        }

        $routes = require_once $routeFile;
        if (!($routes instanceof Closure)) {
            throw new RouteFileInvalidException();
        }

        $routes($this);
        return $this;
    }

    protected function parseRoute(string $method, string $path, array|Closure $handler, ?Middleware $middleware = null): void
    {
        if ($handler instanceof Closure) {
            $this->definedRouteCollection->setAsClosure() // set add to behave handling closure
                ->add($path, $handler, $method, $middleware)
                ->setAsClosure(false); // return it back to normal so it won't collide with other types.
            return;
        }
        if (!class_exists($handler[0])) {
            throw new ControllerNotFoundException();
        }

        if (!method_exists($handler[0], $handler[1])) {
            throw new MethodNotExistException();
        }

        $this->definedRouteCollection->add($path, $handler, $method, $middleware);
    }

    public function get(string $path, array|Closure $handler, ?Middleware $middleware = null): self
    {
        $this->parseRoute('GET', $path, $handler, $middleware);
        return $this;
    }

    public function post(string $path, array|Closure $handler, ?Middleware $middleware = null): self
    {
        $this->parseRoute('POST', $path, $handler, $middleware);
        return $this;
    }

    public function put(string $path, array|Closure $handler, ?Middleware $middleware = null): self
    {
        $this->parseRoute('PUT', $path, $handler, $middleware);
        return $this;
    }

    public function patch(string $path, array|Closure $handler, ?Middleware $middleware = null): self
    {
        $this->parseRoute('PATCH', $path, $handler, $middleware);
        return $this;
    }

    public function delete(string $path, array|Closure $handler, ?Middleware $middleware = null): self
    {
        $this->parseRoute('DELETE', $path, $handler, $middleware);
        return $this;
    }

    public function head(string $path, array|Closure $handler, ?Middleware $middleware = null): self
    {
        $this->parseRoute('HEAD', $path, $handler, $middleware);
        return $this;
    }

    public function options(string $path, array|Closure $handler, ?Middleware $middleware = null): self
    {
        $this->parseRoute('OPTIONS', $path, $handler, $middleware);
        return $this;
    }

    public function view(string $path, string $viewPath, ?Middleware $middleware = null): self
    {
        $this->definedRouteCollection
            ->setAsView()
            ->add($path, $viewPath, 'GET', $middleware, true) // this add handler will behave to add a view.
            ->setAsView(false); // disable setAsView.
        return $this;
    }

    private function triggerRoute()
    {
        $dispatcher = simpleDispatcher(function (RouteCollector $routes) {
            $routeCollection = $this->definedRouteCollection->getRoutes();
            foreach ($routeCollection as $route) {
                $routes->addRoute($route['httpMethod'], $route['path'], $route['handler']);
            }
            $routes->addRoute('GET', '/public/{file:.+}', function ($args) {
                $ext = explode('.', $args['file']);
                $ext = $ext[array_key_last($ext)];

                $filePath = public_path($args['file']);
                if (!file_exists($filePath)) {
                    returnErrorPage(404);
                }

                header("Content-Type: text/$ext");
                readfile($filePath);
                http_response_code(200);
            });
        });

        $request = request();

        $routeInfo = $dispatcher->dispatch(
            $request->getAll()->getMethod(),
            $request->getAll()->getUri()->getPath()
        );

        switch ($routeInfo[0]) {
            case Dispatcher::NOT_FOUND:
                returnErrorPage(404);
                break;
            case Dispatcher::METHOD_NOT_ALLOWED:
                throw new MethodNotAllowedException($request->getAll()->getMethod());
            case Dispatcher::FOUND:
                $handler = $routeInfo[1];
                $vars = $routeInfo[2];
                $handler($vars);
                break;
        }
    }
}
