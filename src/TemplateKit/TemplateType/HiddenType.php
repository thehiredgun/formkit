<?php

namespace FormKit\TemplateKit\TemplateType;

class HiddenType extends TemplateType
{
    protected $template = 'templatekit::hidden';
    protected $value;

    public function __construct(string $name, string $value)
    {
        parent::__construct($name);
        $this->value = $value;
    }

    public function getValue()
    {
        return $this->value;
    }
}