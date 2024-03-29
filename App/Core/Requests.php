<?php

namespace App\Asmvc\Core;

use Laminas\Diactoros\ServerRequest;
use Laminas\Diactoros\ServerRequestFactory;

class Requests
{
    private ServerRequest $request;

    /**
     * Create Request from Globals.
     */
    public function __construct()
    {
        $this->request = (new ServerRequestFactory)->fromGlobals($_SERVER, $_GET, $_POST, $_COOKIE, $_FILES);
    }

    /**
     * Get an input field value
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
     */
    public function getCurrentUrl(): string
    {
        return $this->request->getUri()->__toString();
    }

    /**
     * Get form file uploads
     */
    public function getUpload(string $name): mixed
    {
        if (isset($this->request->getUploadedFiles()[$name])) {
            return $this->request->getUploadedFiles()[$name];
        }

        return false;
    }

    /**
     * Get url query parameter values
     */
    public function getQuery(string $name): string | bool
    {
        if (isset($this->request->getQueryParams()[$name])) {
            return $this->request->getQueryParams()[$name];
        }
        return false;
    }

    /**
     * Return all instance of ServerRequest.
     */
    public function getAll(): ServerRequest
    {
        return $this->request;
    }

    /**
     * Check if request wants json
     */
    public function wantsJson(): bool
    {
        if (!$this->request->hasHeader('Accept')) {
            return false;
        }
        return $this->request->getHeader('Accept')[0] === "application/json";
    }
}
