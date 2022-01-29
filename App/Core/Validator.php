<?php

namespace Albet\Asmvc\Core;

class Validator
{
    /**
     * @var boolean $status
     */
    private static $status = true;

    /**
     * Function to put a validation
     * @param string $field, $value
     */
    private static function put($field, $value)
    {
        $_SESSION['validation'][$field] = $value;
    }

    /**
     * Function to validate the field based from the validate parameter
     * @param string $field, $validate
     */
    private static function validate($field, $validate)
    {
        set_old($field);
        if ($validate == 'required') {
            if (empty(request()->input($field))) {
                self::put($field, "{$field} wajib diisi");
                self::$status = false;
            }
        }
        if ($validate == 'file') {
            if (empty(request()->upload($field))) {
                self::put($field, "{$field} wajib diisi");
                self::$status = false;
            }
        }
        if (str_starts_with($validate, 'min')) {
            $min = getStringAfter(':', $validate);
            if (strlen(request()->input($field)) < $min) {
                self::put($field, "{$field} Minimal karakter hanya boleh " . $min);
                self::$status = false;
            }
        }
        if (str_starts_with($validate, 'max')) {
            $max = getStringAfter(':', $validate);
            if (strlen(request()->input($field)) > $max) {
                self::put($field, "{$field} Maksimal karakter hanya boleh " . $max);
                self::$status = false;
            }
        }
    }

    /**
     * Function to make a validation
     * @param array $validate
     */
    public static function make(array $validate)
    {
        foreach ($validate as $key => $value) {
            if (is_string($value)) {
                self::validate($key, $value);
            } else {
                foreach ($value as $value) {
                    self::validate($key, $value);
                }
            }
        }
        return new static;
    }

    /**
     * Function to get validation error message
     * @param string $field
     * @return string
     */
    public static function validMsg($field)
    {
        $return = $_SESSION['validation'][$field];
        unset($_SESSION['validation'][$field]);
        return $return;
    }

    /**
     * Function to check whenever validation's error or not per field.
     * @param string $field
     * @return boolean
     */
    public static function checkError($field)
    {
        if (isset($_SESSION['validation'][$field])) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * Function to check whenever validation's fails or not. For globals not per field.
     * @return boolean
     */
    public static function fails()
    {
        if (!self::$status) {
            return true;
        } else {
            flush_old();
            return false;
        }
    }
}
