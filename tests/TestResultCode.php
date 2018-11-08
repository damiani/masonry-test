<?php

namespace Tests;

class TestResultCode
{
    private $result_codes;

    public function __construct()
    {
        $this->result_codes = [
            '.' => [
                'code' => mb_convert_encoding("\x27\x13", 'UTF-8', 'UTF-16BE'),
                'color' => 'fg-green',
            ],
            'E' => [
                'code' => '!',
                'color' => 'fg-red',
            ],
            'F' => [
                'code' => mb_convert_encoding("\x27\x15", 'UTF-8', 'UTF-16BE'),
                'color' => 'fg-red',
            ],
            'I' => [
                'code' => 'ℹ',
                'color' => 'fg-blue',
            ],
            'S' => [
                'code' => mb_convert_encoding("\x27\x94", 'UTF-8', 'UTF-16BE'),
                'color' => 'fg-yellow',
            ],
        ];
    }

    public function getCode($code)
    {
        return $this->result_codes[strtoupper($code)]['code'];
    }

    public function getColor($code)
    {
        return $this->result_codes[strtoupper($code)]['color'];
    }
}
