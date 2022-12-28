<?php

namespace App\Asmvc\Core\Routing;

use App\Asmvc\Core\Exceptions\CallingToUndefinedMethod;
use App\Asmvc\Core\Logger\Logger;
use App\Asmvc\Core\Middleware\MiddlewareRouteBuilder;
use App\Asmvc\Core\Requests;
use App\Asmvc\Core\SessionManager;
use App\Asmvc\Core\Views\ViewRouteBuilder;
use Closure;
use FastRoute\Dispatcher;
use FastRoute\RouteCollector;

use function FastRoute\simpleDispatcher;

class Route
{
    private RoutesCollection $definedRouteCollection;

    /**
     * Create Routes Collection
     */
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

    /**
     * Map the routing based on routes file.
     */
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

        $routes($this, new MiddlewareRouteBuilder());
        return $this;
    }

    /**
     * Parse the routing and pass it to route collection
     */
    protected function parseRoute(string $method, string $path, array|Closure $handler, ?MiddlewareRouteBuilder $middleware = null): void
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

    /**
     * Add route with get as it's http method
     */
    public function get(string $path, array|Closure $handler, ?MiddlewareRouteBuilder $middleware = null): self
    {
        $this->parseRoute('GET', $path, $handler, $middleware);
        return $this;
    }

    /**
     * Add route with post as it's http method
     */
    public function post(string $path, array|Closure $handler, ?MiddlewareRouteBuilder $middleware = null): self
    {
        $this->parseRoute('POST', $path, $handler, $middleware);
        return $this;
    }

    /**
     * Add route with put as it's http method
     */
    public function put(string $path, array|Closure $handler, ?MiddlewareRouteBuilder $middleware = null): self
    {
        $this->parseRoute('PUT', $path, $handler, $middleware);
        return $this;
    }

    /**
     * Add route with patch as it's http method
     */
    public function patch(string $path, array|Closure $handler, ?MiddlewareRouteBuilder $middleware = null): self
    {
        $this->parseRoute('PATCH', $path, $handler, $middleware);
        return $this;
    }

    /**
     * Add route with delete as it's http method
     */
    public function delete(string $path, array|Closure $handler, ?MiddlewareRouteBuilder $middleware = null): self
    {
        $this->parseRoute('DELETE', $path, $handler, $middleware);
        return $this;
    }

    /**
     * Add route with head as it's http method
     */
    public function head(string $path, array|Closure $handler, ?MiddlewareRouteBuilder $middleware = null): self
    {
        $this->parseRoute('HEAD', $path, $handler, $middleware);
        return $this;
    }

    /**
     * Add route with options as it's http method
     */
    public function options(string $path, array|Closure $handler, ?MiddlewareRouteBuilder $middleware = null): self
    {
        $this->parseRoute('OPTIONS', $path, $handler, $middleware);
        return $this;
    }

    /**
     * Make a GET for view.
     */
    public function view(string $path, string|Closure $view, ?MiddlewareRouteBuilder $middleware = null): self
    {
        if (is_string($view) && !($view instanceof Closure)) {
            $data = [$view, []];
        } else {
            $view = $view(new ViewRouteBuilder());
            $data = [$view->path, $view->bind];
        }
        $this->definedRouteCollection
            ->setAsView()
            ->add($path, $data, 'GET', $middleware, true) // this add handler will behave to add a view.
            ->setAsView(false); // disable setAsView.
        return $this;
    }

    /**
     * Register previos route handler
     */
    private function registerPrevious(Requests $request): void
    {
        if (!str_contains($request->getCurrentUrl(), "/public/")) {
            SessionManager::registerPrevious($request->getCurrentUrl());
        }
    }

    /**
     * Trigger and run the router.
     * Which later will redirect users to corresponding handler.
     */
    private function triggerRoute()
    {
        $dispatcher = simpleDispatcher(function (RouteCollector $routes): void {
            $routeCollection = $this->definedRouteCollection->getRoutes();
            foreach ($routeCollection as $route) {
                $routes->addRoute($route['httpMethod'], $route['path'], $route['handler']);
            }
            $routes->addRoute('GET', '/public/{file:.+}', function ($args): void {
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
                Logger::info("Route not found.", ['url' => $request->getCurrentUrl()]);
                $this->registerPrevious($request);
                returnErrorPage(404);
                break;
            case Dispatcher::METHOD_NOT_ALLOWED:
                Logger::info("Method not allowed.", ['url' => $request->getCurrentUrl()]);
                $this->registerPrevious($request);
                throw new MethodNotAllowedException($request->getAll()->getMethod());
            case Dispatcher::FOUND:
                Logger::info("Route found.", ['url' => $request->getCurrentUrl()]);
                $handler = $routeInfo[1];
                $vars = $routeInfo[2];
                $this->registerPrevious($request);
                $handler($vars);
                break;
        }
    }
}
