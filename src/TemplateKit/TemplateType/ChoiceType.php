<?php

namespace FormKit\TemplateKit\TemplateType;

use StdClass;

abstract class ChoiceType extends TemplateType
{
    protected $choices;
    protected $label;
    protected $required = true;

    public function __construct(string $name, array $choices)
    {
        parent::__construct($name);
        $this->label = $this->getLabelFromName($name);
        foreach ($choices as $index => $choice) {
            if (is_object($choice) && property_exists($choice, 'id') && property_exists($choice, 'name')) {
                $this->choices = $choices;
                break;
            } else {
                $c = new StdClass();
                $c->id = $index;
                $c->name = $choice;
                $this->choices[] = $c;
            }
        }
    }

    public function getChoices()
    {
        return $this->choices;
    }

    public function setLabel(string $label)
    {
        $this->label = $label;
    }

    public function getLabel()
    {
        return $this->label;
    }

    public function getRequired()
    {
        return $this->required;
    }
}

