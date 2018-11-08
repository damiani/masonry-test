<?php

namespace Tests;

use PHPUnit\Framework\Test as PhpUnitTest;
use PHPUnit\TextUI\ResultPrinter;
use Tests\TestClass;
use Tests\TestMethod;

class ResultPrinterV6 extends ResultPrinter
{
    protected $test_class;

    public function startTest(PhpUnitTest $test)
    {
        $this->test_class = new TestClass($test);
        $this->test_method = new TestMethod($test);
        parent::startTest($test);
    }
}

