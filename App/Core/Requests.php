<?php

namespace Albet\Ppob\Core;

class Requests
{
    public function input($field)
    {
        if (isset($_POST[$field])) {
            return $_POST[$field];
        } else if (isset($_GET[$field])) {
            return $_GET[$field];
        } else {
            return;
        }
    }

    public function currentURL()
    {
        get_http_protocol() . '://' . base_url() . $_SERVER['REQUEST_URI'];
    }

    public function upload($name)
    {
        if (isset($_FILES[$name])) {
            return $_FILES[$name];
        } else {
            return;
        }
    }

    public function query($name)
    {
        if (isset($_GET[$name])) {
            return $_GET[$name];
        } else {
            return;
        }
    }
}
