<?php

namespace Apache\VhostHelper;

class Directive
{
    public $name;
    public $value;
    const INDENTATION_SPACES = "    ";

    public function __construct($name, $value = null)
    {
        $this->name = $name;
        $this->value = $value;
    }

    public function toString()
    {
        return self::INDENTATION_SPACES . $this->name . " " . $this->value . PHP_EOL;
    }
}