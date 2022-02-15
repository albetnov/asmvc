<?php

use Albet\Asmvc\Core\Validator;

/**
 * Function to get old value of field.
 * @param string $field_name
 * @param string $data
 * @return string
 */
function old($field_name, $data = null)
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
function set_old($field)
{
    $store = request()->input($field);
    $_SESSION['old'][$field] = $store;
}

/**
 * Function to flush entire session old if there's no validation error
 */
function flush_old()
{
    unset($_SESSION['old']);
}

/**
 * Function to access Validator's make method immediately
 * @param array $validate
 * @return Validator
 */
function makeValidate($validate, $customMsg = [])
{
    return (new Validator)::make($validate, $customMsg);
}

/**
 * Function to access Validator's checkError method immediately
 * @param string $field
 * @return Validator
 */
function checkError($field)
{
    return (new Validator)::checkError($field);
}

/**
 * Function to access Validator's validMsg method immediately
 * @param string $field
 * @return Validator
 */
function validateMsg($field)
{
    return (new Validator)::validMsg($field);
}
