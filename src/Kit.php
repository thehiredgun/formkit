<?php

namespace FormKit;

abstract class Kit
{
    /**
     * has errors (for the form or for a property)
     *
     * @param string $name
     *
     * @return bool
     */
    public function hasErrors(string $name = '')
    {
        return ('' === $name) ? (bool) count($this->errors) : isset($this->errors[$name]);
    }
}
