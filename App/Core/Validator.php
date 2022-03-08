<?php

namespace Albet\Asmvc\Core;

use Rakit\Validation\Validator as RakitValidator;

class Validator
{
    /**
     * Put error to session.
     * @param object $array
     */
    private static function put($array)
    {
        foreach ($array->toArray() as $field => $message) {
            $_SESSION['validation'][$field] = $message;
        }
    }

    /**
     * Make a validation.
     * @param array $validate
     * @param array $customMsg
     * @param boolean $redirect
     * @return boolean
     */
    public static function make(array $validate, array $customMsg = [], $redirect = false)
    {
        $validator = new RakitValidator();
        $validation = $validator->make(request()->input('*'), $validate);

        if ($customMsg !== []) {
            $validation->setMessages($customMsg);
        }

        $validation->validate();

        if ($validation->fails()) {
            self::put($validation->errors());
            foreach (array_keys($validate) as $field) {
                set_old($field);
            }
            if ($redirect) {
                return redirect(back(), false);
            }
            return false;
        } else {
            flush_old();
            return true;
        }
    }

    /**
     * Get a message validation
     * @param string $field
     * @param string $class
     * @param string $liclass
     * @return string
     */
    public static function validMsg($field, $class = null, $liclass = null)
    {
        $string = "";
        if ($class) {
            $string .= "<ul class=\"{$class}\">";
        } else {
            $string .= "<ul>";
        }
        foreach ($_SESSION['validation'][$field] as $error) {
            if ($liclass) {
                $string .= "<li class=\"{$liclass}\">{$error}</li>";
            } else {
                $string .= "<li>{$error}</li>";
            }
        }
        $string .= "</ul>";
        unset($_SESSION['validation'][$field]);
        return $string;
    }

    /**
     * Check if error in validation is exist.
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
}
