<?php


namespace Apache\VhostHelper;


class AnObj
{
    protected $methods = array();

    public function __construct(array $options)
    {
        $this->methods = $options;
    }

    public function __call($name, $arguments)
    {
        $callable = null;
        if (array_key_exists($name, $this->methods))
            $callable = $this->methods[$name];
        elseif (isset($this->$name))
            $callable = $this->$name;

        if (!is_callable($callable))
            throw new BadMethodCallException("Method {$name} does not exists");

        return call_user_func_array($callable, $arguments);
    }
}