<?php

namespace Tests;

use PHPUnit_Framework_Test as PhpUnitTest;
use PHPUnit_TextUI_ResultPrinter as ResultPrinter;
use Tests\TestClass;
use Tests\TestMethod;

class ResultPrinterV5 extends ResultPrinter
{
    protected $test_class;

    public function startTest(PhpUnitTest $test)
    {
        $this->test_class = new TestClass($test);
        $this->test_method = new TestMethod($test);
        parent::startTest($test);
    }
}

