<?php

namespace FormKit\TemplateKit\TemplateType;

class SubmitType
{
    protected $template = 'templatekit::submit';
    protected $label = 'Submit';

    public function getTemplate()
    {
        return $this->template;
    }

    public function setLabel(string $label)
    {
        $this->label = $label;
    }

    public function getLabel()
    {
        return $this->label;
    }
}