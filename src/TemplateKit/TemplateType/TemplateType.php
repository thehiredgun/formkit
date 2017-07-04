<?php

namespace FormKit\TemplateKit\TemplateType;

abstract class TemplateType
{
    protected $name;

    public function __construct(string $name)
    {
        $this->name = $name;
    }

    public function getName()
    {
        return $this->name;
    }

    protected function getLabelFromName($name)
    {
        return ucwords(str_replace('_', ' ', $name));
    }

    public function setTemplate(string $template)
    {
        $this->template = $template;
    }

    public function getTemplate()
    {
        return $this->template;
    }
}
