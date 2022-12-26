<?php

namespace Albet\Asmvc\Core;

use Laminas\Diactoros\ServerRequest;
use Laminas\Diactoros\ServerRequestFactory;

class Requests extends ServerRequestFactory
{
    private ServerRequest $request;

    public function __construct()
    {
        $this->request = $this->fromGlobals($_SERVER, $_GET, $_POST, $_COOKIE, $_FILES);
    }

    /**
     * Get an input field value
     * @param string $field
     * @param bool $escape
     * @return string|array
     */
    public function getInput(string $field): string|bool|array
    {
        if ($field == '*') {
            return $this->request->getParsedBody();
        }

        if (!isset($this->request->getParsedBody()[$field])) {
            return false;
        }

        return $this->request->getParsedBody()[$field];
    }

    /**
     * Get user's current URL
     * @return string
     */
    public function getCurrentUrl(): string
    {
        return $this->request->getUri()->__toString();
    }

    /**
     * Get form file uploads
     * @param string $name
     * @return mixed
     */
    public function getUpload(string $name): mixed
    {
        vdd($this->request->getUploadedFiles(), $_FILES);
        if (isset($this->request->getUploadedFiles()[$name])) {
            return $this->request->getUploadedFiles()[$name];
        }

        return false;
    }

    /**
     * Get url query parameter values
     * @param string $name
     * @return string|bool
     */
    public function getQuery(string $name): string | bool
    {
        if (isset($this->request->getQueryParams()[$name])) {
            return $this->request->getQueryParams()[$name];
        }
        return false;
    }

    public function getAll(): ServerRequest
    {
        return $this->request;
    }
}
