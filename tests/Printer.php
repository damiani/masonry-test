<?php

namespace Tests;

use PHPUnit\Framework\Test;
use PHPUnit\Framework\TestSuite;
use Tests\TestResultCode;

if (class_exists('\PHPUnit\TextUI\ResultPrinter')) {
    class ResultPrinter extends ResultPrinterV6 {}
} else {
    class ResultPrinter extends ResultPrinterV5 {}
}

class Printer extends ResultPrinter
{
    protected static $ansi_codes = [
        'bold' => 1,
        'underline' => 4,
        'fg-black' => 30,
        'fg-red' => 31,
        'fg-green' => 32,
        'fg-yellow' => 33,
        'fg-blue' => 34,
        'fg-magenta' => 35,
        'fg-cyan' => 36,
        'fg-white' => 37,
        'fg-dark-gray' => 90,
        'bg-black' => 40,
        'bg-red' => 41,
        'bg-green' => 42,
        'bg-yellow' => 43,
        'bg-blue' => 44,
        'bg-magenta' => 45,
        'bg-cyan' => 46,
        'bg-white' => 47
    ];
    protected $left_column_width = 40;
    protected $previous_namespace;
    protected $previous_class_name;
    protected $result_codes;
    protected $window_width;

    public function __construct($out = null, $verbose = false, $colors = self::COLOR_DEFAULT, $debug = false, $numberOfColumns = 80)
    {
        parent::__construct($out, $verbose, $colors, $debug, $numberOfColumns);
        $this->result_codes = new TestResultCode;
        $this->left_column_width = min((int) ($this->getWindowWidth() / 2), $this->left_column_width);
    }

    public function formatWithColor($color, $buffer)
    {
        if (! $this->colors) {
            return $buffer;
        }

        $codes   = array_map('trim', explode(',', $color));
        $lines   = explode("\n", $buffer);
        $padding = max(array_map('strlen', $lines));
        $styles  = [];

        foreach ($codes as $code) {
            $styles[] = self::$ansi_codes[$code];
        }

        $style = sprintf("\x1b[%sm", implode(';', $styles));

        $styledLines = [];

        foreach ($lines as $line) {
            $styledLines[] = $style . str_pad($line, $padding) . "\x1b[0m";
        }

        return implode("\n", $styledLines);
    }

    public function print($text, $color = 'fg-white', $lf = true)
    {
        $this->colors && $color ? $this->writeWithColor($color, $text, ! $lf) : $this->write($text, ! $lf);
        $this->column = $lf ? 0 : $this->column + mb_strlen($text);
    }

    public function printTestResult($progress)
    {
        $output = ' ' . $this->result_codes->getCode($progress);

        if ($this->outputShouldWrap($output)) {
            $this->writeNewLine();
            $this->writeColumnIndent();
        }

        $this->print($output, $this->result_codes->getColor($progress), $lf = true);
    }

    public function writeNewLine($count = 1)
    {
        $this->column = 0;

        for ($i=0; $i < $count; $i++) {
            $this->write("\n");
        }
    }

    public function writeProgress($progress)
    {
        if ($this->debug) {
            return;
        }

        if ($this->namespaceIsNew()) {
            $this->previous_namespace = $this->test_class->getNamespace();
            $this->writeNewLine();
            $this->writeNamespace();
        }

        if ($this->classNameIsNew()) {
            $this->previous_class_name = $this->test_class->getName();
            $this->writeNewLine();
            $this->writeClassName();
        }

        $this->printTestResult($progress);
    }

    public function writeProgressWithColor($color, $progress)
    {
        $this->writeProgress($progress);
    }

    protected function getFixedWidthText($text = '', $width)
    {
        return strlen($text) <= $width ? str_pad($text, $width) : $this->getTruncatedText($text, $width);
    }

    protected function getTruncatedText($text, $width)
    {
        return substr($this->test_class->getName(), 0, $width - 3) . '...';
    }

    protected function getWindowWidth()
    {
        if (! $this->window_width) {
            $columns = (int) explode(' ', exec('stty size 2>/dev/null'))[1];
            $this->window_width = $columns ?: 120;
        }

        return $this->window_width;
    }

    protected function classNameIsNew()
    {
        dd($this->test_class);
        dd(get_class($this->test_class));

        return $this->test_class->getName() !== $this->previous_class_name;
    }

    protected function namespaceIsNew()
    {
        return $this->test_class->getNamespace() !== $this->previous_namespace;
    }

    protected function outputShouldWrap($text = ' ')
    {
        return $this->column > $this->window_width - mb_strlen($text);
    }

    protected function writeClassName()
    {
        $class_name = $this->getFixedWidthText($this->test_class->getFormattedName(), $this->left_column_width);

        $this->print($class_name, 'fg-white', $lf = true);
    }

    protected function writeColumnIndent()
    {
        $this->print($this->getFixedWidthText('', $this->left_column_width), null, $lf = true);
    }

    protected function writeNamespace()
    {
        $this->print($this->test_class->getNamespace(), 'fg-dark-gray,underline', $lf = true);
    }

    /**
     *
     * Experimental
     *
     */
    public function startTestSuite(TestSuite $suite)
    {
        // dd($suite->tests()[0]->count());
        // dd($suite->tests()[0]->tests()[2]->count());
        // dd($suite->tests()[0]->tests()[0]->tests()[0]->count());
        // dd($suite->tests()[0]->tests()[0]->tests()[0]->getName());

        parent::startTestSuite($suite);
    }

    // public function startTest(Test $test)
    // {
    //     parent::startTest($test);
    // }
}
