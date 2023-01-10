<?php

namespace App\Asmvc\Core\REST;

use App\Asmvc\Core\Logger\Logger;

class UnixTimestamp
{
    private int $timestamp;

    public function __construct($time)
    {
        if ($time instanceof \DateTimeImmutable) {
            $this->timestamp = $time->getTimestamp();
        }

        try {
            $time = new \DateTimeImmutable($time);
            $this->timestamp = $time->getTimestamp();
        } catch (\Exception $e) {
            Logger::error('Time conversion failed.', ['exception' => $e]);
        }

        $this->validate();
    }

    public function validate()
    {
        $date = date('m-d-Y', $this->timestamp);
        list($month, $day, $year) = explode('-', $date);

        if (!checkdate($month, $day, $year)) {
            throw new InvalidTimestampException();
        }

        return true;
    }

    public function get(): \DateTimeImmutable
    {
        return new \DateTimeImmutable($this->timestamp);
    }

    public function now(): \DateTimeImmutable
    {
        return new \DateTimeImmutable();
    }
}
