<?php

namespace Albet\Ppob\Core;

class Validator
{
    private static $status = true;
    public static function validate($validate, $field)
    {
        if ($validate == 'required') {
            if (empty(request()->input($field))) {
                Flash::flash($field, 'Kolom wajib diisi');
                set_old($field);
                self::$status = false;
            }
        }
        if ($validate == 'file') {
            if (empty(request()->upload($field))) {
                Flash::flash($field, 'Kolom wajib diisi');
                set_old($field);
                self::$status = false;
            }
        }
        if (str_starts_with($validate, 'min')) {
            $min = getStringAfter(':', $validate);
            if (strlen(request()->input($field)) < $min) {
                Flash::flash($field, 'Minimal karakter hanya boleh ' . $min);
                set_old($field);
                self::$status = false;
            }
        }
        if (str_starts_with($validate, 'max')) {
            $max = getStringAfter(':', $validate);
            if (strlen(request()->input($field)) < $max) {
                Flash::flash($field, 'Maksimal karakter hanya boleh ' . $max);
                set_old($field);
                self::$status = false;
            }
        }
    }

    public static function make(array $validate)
    {
        $field = array_keys($validate);
        $validation = array_values($validate);
        foreach ($validation as $validate) {
            foreach ($field as $field) {
                if (str_contains($validate, '|')) {
                    $exploded = explode('|', $validate);
                    foreach ($exploded as $exploded) {
                        self::validate($exploded, $field);
                    }
                } else {
                    self::validate($validate, $field);
                }
            }
        }
        return new static;
    }

    public static function validMsg($field)
    {
        return Flash::catchFlash($field);
    }

    public static function fails()
    {
        if (!self::$status) {
            return true;
        } else {
            return false;
        }
    }
}
