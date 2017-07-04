<?php

namespace FormKit\TemplateKit\TemplateType;

class DateType extends TemplateType
{
    protected $template = 'templatekit::date';
    protected $label;
    protected $placeholder;
    protected $format = 'Y-m-d';
    protected $required = true;

    public function __construct($name)
    {
        parent::__construct($name);
        $this->label = $this->placeholder = $this->getLabelFromName($name);
    }

    public function setLabel(string $label)
    {
        $this->label = $label;
    }

    public function getLabel()
    {
        return $this->label;
    }

    public function setPlaceholder(string $placeholder)
    {
        $this->placeholder = $placeholder;
    }

    public function getPlaceholder()
    {
        return $this->placeholder;
    }

    public function setRequired(bool $required)
    {
        $this->required = $required;
    }

    public function getRequired()
    {
        return $this->required;
    }
}