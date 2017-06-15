<?php

namespace FormKit;

/**
 * error message kit: a little toolkit to help present errors on forms
 *
 * @author Nick Wakeman <nick@thehiredgun.tech>
 * @since  2017-06-15
 */
class ErrorMessageKit extends Kit
{
    /**
     * @var string $errorClass
     */
    private $errorClass = 'has-error';

    public function __construct(array $errors)
    {
        $this->errors = $errors;
    }

    /**
     * add error class: return a CSS class name for elements with errors
     *
     * @param string $name
     *
     * @return string
     */
    public function addErrorClass(string $name)
    {
        return (isset($this->errors[$name]) && count($this->errors[$name])) ?
            ' ' . trim($this->errorClass) : ''
        ;
    }

    /**
     * add error messages: return the error messages, each wrapped in an html tag
     *
     * @param  string $name
     * @param  string $wrapper
     *
     * @return string
     */
    public function addErrorMessages(string $name = '')
    {
        if ('' != $name) {
            return $this->getErrorMessagesForKey($name);
        } else {
            $errorMessages = '';
            foreach ($this->errors as $error) {
                $errorMessages .= $this->getErrorMessageForKey($name);
            }
            return $errorMessages;
        }
    }

    /**
     * get error messages for key
     *
     * @param string $name
     *
     * @return string
     */
    private function getErrorMessagesForKey(string $name)
    {
        $wrapper = 'li';
        if (isset($this->errors[$name]) && count($this->errors[$name])) {
            return "<$wrapper>" . implode("</$wrapper><$wrapper>", $this->errors[$name]) . "</$wrapper>";
        }
    }
}
