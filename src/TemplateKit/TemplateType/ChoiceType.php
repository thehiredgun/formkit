<?php

namespace FormKit\TemplateKit\TemplateType;

use StdClass;

abstract class ChoiceType extends TemplateType
{
    protected $choices;
    protected $label;
    protected $required = true;

    public function __construct(string $name, $choices)
    {
        parent::__construct($name);
        $this->label = $this->getLabelFromName($name);
        foreach ($choices as $index => $choice) {
            if (
                is_object($choice)
                &&
                (property_exists($choice, 'id') && property_exists($choice, 'name'))
                ||
                (
                    method_exists($choice, 'getAttributes')
                    &&
                    in_array('id', array_flip($choice->getAttributes()))
                )
            ) {
                $this->choices = $choices;
                break;
            } else {
                $c = new StdClass();
                $c->id = $index;
                $c->name = $choice . getType($choices);
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

