<?php

namespace Tests;

class TestMethod
{
    protected $name;
    protected $test;

    public function __construct($test)
    {
        $this->test = $test;
    }

    public function getName()
    {
        if (! $this->name) {
            $this->name = $this->test->getName();
        }

        return $this->name;
    }
}
