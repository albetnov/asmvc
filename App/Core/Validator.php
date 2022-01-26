<?php

namespace Albet\Asmvc\Core;

class Validator
{
    private static $status = true;

    private static function put($field, $value)
    {
        $_SESSION['validation'][$field] = $value;
    }

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

    public static function validMsg($field)
    {
        $return = $_SESSION['validation'][$field];
        unset($_SESSION['validation'][$field]);
        return $return;
    }

    public static function checkError($field)
    {
        if (isset($_SESSION['validation'][$field])) {
            return true;
        } else {
            return false;
        }
    }

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
