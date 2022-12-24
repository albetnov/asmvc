<?php

use Albet\Asmvc\Core\Validator;

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
    return;
}

/**
 * Function to set old to a field
 * @param string $field
 */
function set_old(string $field): void
{
    $store = request()->input($field);
    $_SESSION['old'][$field] = $store;
}

/**
 * Function to flush entire session old if there's no validation error
 */
function flush_old(): void
{
    unset($_SESSION['old']);
}

/**
 * Function to access Validator's make method immediately
 * @param array $validate
 * @return bool
 */
function makeValidate(array $validate, array $customMsg = []): bool
{
    return Validator::make($validate, $customMsg);
}

/**
 * Function to access Validator's checkError method immediately
 * @param string $field
 * @return bool
 */
function checkError(string $field): bool
{
    return Validator::checkError($field);
}

/**
 * Function to access Validator's validMsg method immediately
 * @param string $field
 * @return string
 */
function validateMsg(string $field): string
{
    return Validator::validMsg($field);
}
