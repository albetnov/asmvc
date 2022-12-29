<?php

namespace App\Asmvc\Core\REST;

use DateTime;
use Exception;

class UnixTimestamp
{
    private int $timestamp;

    public function validate(?int $timestamp = null)
    {
        if (!$timestamp) {
            $timestamp = $this->timestamp;
        }

        $date = date('m-d-Y', $timestamp);
        dd($date);
        list($month, $day, $year) = explode('-', $date);

        if (!checkdate($month, $day, $year)) {
            throw new InvalidTimestampException();
        }

        return true;
    }

    public function put(int $timestamp): self
    {
        $this->validate($timestamp);
        $this->timestamp = $timestamp;
        return $this;
    }

    public function get(): int
    {
        return $this->timestamp;
    }
}
