<?php

namespace Tests;

use ReflectionClass;

class TestClass
{
    protected $class;
    protected $name;
    protected $namespace;

    public function __construct($test)
    {
        $this->class = new ReflectionClass(get_class($test));
    }

    public function getFormattedName()
    {
        $prefix = $this->getNamespace() ? '    ' : '';

        return $prefix . $this->getName();
    }

    public function getName()
    {
        if (! $this->name) {
            $this->name = $this->class->getShortName();
        }

        return $this->name;
    }

    public function getNamespace()
    {
        if (! $this->namespace) {
            $this->namespace = $this->class->getNamespaceName();
        }

        return $this->namespace;
    }
}
