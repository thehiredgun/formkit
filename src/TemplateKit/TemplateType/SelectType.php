<?php

namespace FormKit\TemplateKit\TemplateType;

class SelectType extends ChoiceType
{
    protected $template = 'templatekit::select';

    public function setRequired(bool $required)
    {
        $this->required = $required;
    }
}

