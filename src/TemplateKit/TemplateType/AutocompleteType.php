<?php

namespace FormKit\TemplateKit\TemplateType;

class AutocompleteType extends TemplateType
{
    protected $template = 'templatekit::autocomplete';
    protected $label;
    protected $placeholder;
    protected $choices;
    protected $required = true;

    public function __construct(string $name, $choices)
    {
        parent::__construct($name);
        $this->label = $this->placeholder = $this->getLabelFromName($name);
        $this->choices = $choices;
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

    public function getChoices()
    {
        return $this->choices;
    }
}
