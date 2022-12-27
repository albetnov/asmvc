<?php

use App\Asmvc\Core\Validator;

if (!function_exists('old')) {
    /**
     * Function to get old value of field.
     * @param string $field_name
     * @param string $data
     * @return string
     */
    function old(string $field_name, ?string $data = null): string
    {
        if (isset($_SESSION['old'][$field_name])) {
            $return = $_SESSION['old'][$field_name];
            unset($_SESSION['old'][$field_name]);
            return $return;
        } else if (!is_null($data)) {
            return $data;
        }
    }
}

if (!function_exists('set_old')) {
    /**
     * Function to set old to a field
     * @param string $field
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
     * @param array $validate
     * @return bool
     */
    function makeValidate(array $validate, array $customMsg = []): bool
    {
        return Validator::make($validate, $customMsg);
    }
}

if (!function_exists('checkError')) {
    /**
     * Function to access Validator's checkError method immediately
     * @param string $field
     * @return bool
     */
    function checkError(string $field): bool
    {
        return Validator::checkError($field);
    }
}

if (!function_exists('getErrorMsg')) {
    /**
     * Function to access Validator's validMsg method immediately
     * @param string $field
     * @return string
     */
    function getErrorMsg(string $field): string
    {
        return Validator::validMsg($field);
    }
}
