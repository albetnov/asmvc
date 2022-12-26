<?php

namespace Albet\Asmvc\Core;

use Rakit\Validation\Validator as RakitValidator;

class Validator
{
    /**
     * Put error to session.
     * @param object $array
     */
    private static function put(object $array): void
    {
        foreach ($array->toArray() as $field => $message) {
            $_SESSION['validation'][$field] = $message;
        }
    }

    /**
     * Make a validation.
     * @param array $validate
     * @param array $customMsg
     * @param bool $redirect
     * @return bool
     */
    public static function make(array $validate, array $customMsg = [], bool $redirect = false): bool
    {
        $validator = new RakitValidator();
        $validation = $validator->make(request()->getInput("*"), $validate);

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
    public static function validMsg(string $field, string $class = null, string $liclass = null): string
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
     * @return bool
     */
    public static function checkError(string $field): bool
    {
        if (isset($_SESSION['validation'][$field])) {
            return true;
        } else {
            return false;
        }
    }
}
