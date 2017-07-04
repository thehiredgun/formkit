<?php

namespace FormKit\TemplateKit\TemplateType;

abstract class StringType extends TemplateType
{
    protected $label;
    protected $placeholder;
    protected $required = true;
    protected $maxLength = 255;

    public function __construct(string $name)
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

    public function setMaxLength(int $maxLength)
    {
        $this->maxLength = $maxLength;
    }

    public function getMaxLength()
    {
        return $this->maxLength;
    }
}
