<?php

namespace App\Asmvc\Core;

use Rakit\Validation\Validator as RakitValidator;

class Validator
{
    /**
     * Put error to session.
     */
    private static function put(object $array): void
    {
        foreach ($array->toArray() as $field => $message) {
            $_SESSION['validation'][$field] = $message;
        }
    }

    /**
     * Make a validation.
     */
    public static function make(array $validate, array $customMsg = [], bool $redirect = true): bool
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
        }
        flush_old();
        return true;
    }

    /**
     * Get a message validation
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
        unset($_SESSION['validation'][$field]);
        return $string . "</ul>";
    }

    /**
     * Check if error in validation is exist.
     */
    public static function checkError(string $field): bool
    {
        return isset($_SESSION['validation'][$field]);
    }
}
