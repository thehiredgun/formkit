<?php

namespace FormKit\TemplateKit\TemplateType;

class TextareaType extends StringType
{
    protected $template = 'templatekit::textarea';
    protected $numRows = 5;

    public function setNumRows(int $numRows)
    {
        $this->numRows = $numRows;
    }

    public function getNumRows()
    {
        return $this->numRows;
    }
}
