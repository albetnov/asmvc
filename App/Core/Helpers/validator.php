<?php

use App\Asmvc\Core\Validator;

if (!function_exists('old')) {
    /**
     * Function to get old value of field.
     */
    function old(string $field_name, ?string $data = null): ?string
    {
        if (!is_null($data)) {
            return $data;
        }

        return null;
    }
}

if (!function_exists('set_old')) {
    /**
     * Function to set old to a field
     */
    function set_old(string $field): void
    {
        $store = request()->getInput($field);
        $_SESSION['old'][$field] = $store;
    }
}

if (!function_exists('flush_old')) {
    /**
     * Function to flush entire session old if there's no validation error
     */
    function flush_old(): void
    {
        unset($_SESSION['old']);
    }
}

if (!function_exists('makeValidate')) {
    /**
     * Function to access Validator's make method immediately
     */
    function makeValidate(array $validate, array $customMsg = []): bool
    {
        return Validator::make($validate, $customMsg);
    }
}

if (!function_exists('checkError')) {
    /**
     * Function to access Validator's checkError method immediately
     */
    function checkError(string $field): bool
    {
        return Validator::checkError($field);
    }
}

if (!function_exists('getErrorMsg')) {
    /**
     * Function to access Validator's validMsg method immediately
     */
    function getErrorMsg(string $field): string
    {
        return Validator::validMsg($field);
    }
}
