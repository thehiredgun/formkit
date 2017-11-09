<?php

namespace FormKit\SubmissionKit;

use InvalidArgumentException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

/**
 * SubmissionKit: a nice bit of kit to better manage data-submissions in Laravel
 *
 * @author Nick Wakeman <nick@thehiredgun.tech>
 * @since  2017-06-13
 */
class SubmissionKit
{
    /**
     * @var Request $request
     */
    protected $request;

    /**
     * @var array $rules
     */
    protected $rules = [];

    /**
     * @var array $errors
     */
    protected $errors = [];

    /**
     * construct
     *
     * @author Nick Wakeman <nick@thehiredgun.tech>
     *
     * @param Request $request
     * @param array   $rules
     */
    public function __construct(Request $request, array $rules)
    {
        $this->request = $request;
        foreach ($rules as $name => $rule) {
            switch(gettype($rule)) {
                case 'string':
                    $elementRules = explode('|', $rule);
                    foreach ($elementRules as $elementRule) {
                        $this->rules[$name][] = trim($elementRule);
                    }
                break;
                case 'array':
                    $this->rules[$name] = $rule;
                break;
                default:
                    Throw new InvalidArgumentException('Each Rule should be of type array or string, not ' . getType($rule));
                break;
            }
        }
    }

    /**
     * validate
     *
     * @author Nick Wakeman <nick@thehiredgun.tech>
     */
    public function validate()
    {
        $validator = Validator::make($this->request->all(), $this->rules);
        if ($formErrors = $validator->errors()) {
            foreach ($this->rules as $name => $rule) {
                if ($errorsForInput = $formErrors->get($name)) {
                    $this->errors[$name] = $errorsForInput;
                }
            }
        }
    }

    /**
     * has errors (for the form or for a property)
     *
     * @param string $name
     *
     * @return bool
     */
    public function hasErrors(string $name = '')
    {
        return ('' === $name) ? (bool) count($this->errors) : isset($this->errors[$name]);
    }

    /**
     * get errors (for the form or for the property)
     *
     * @param string $name
     *
     * @return array
     */
    public function getErrors(string $name = '')
    {
        return ('' === $name) ? $this->errors : $this->errors[$name];
    }

    /**
     * add error
     *
     * @param string $name
     * @param string $error
     */
    public function addError(string $name, string $error)
    {
        $this->errors[$name][] = $error;
    }

    /**
     * set errors
     *
     * set the errors array for a $name
     *
     * @param string $name
     * @param array  $errors
     */
    public function setErrors(string $name, array $errors)
    {
        $this->errors[$name] = $errors;
    }

    /**
     * set properties on an object
     *
     * @param  object $object
     * @param  mixed  $properties
     *
     * @throws InvalidArgumentException
     */
    public function setProperties($object, $properties = '*')
    {
        if ('*' === $properties) {
            foreach ($this->rules as $name => $rule) {
                $this->setProperty($object, $name);
            }
        } elseif (is_array($properties)) {
            foreach ($properties as $name) {
                $this->setProperty($object, $name);
            }
        } elseif (is_string($properties)) {
            foreach (explode(',', $properties) as $name) {
                $this->setProperty($object, $name);
            }
        } else {
            Throw new InvalidArgumentException('$propertyNames should be \'*\' or of type string or array, not ' . getType($propertyNames));
        }
    }

    /**
     * set property on an object
     *
     * @param mixed  $object
     * @param string $name
     */
    protected function setProperty($object, string $name)
    {
        if (!$this->hasErrors($name)) {
            $object->$name = $this->request->input($name);
        }
    }

    /**
     * is valid: the inverse of hasErrors()
     *
     * @author Nick Wakeman <nick@thehiredgun.tech>
     * @since  1.0.0 (2017-11-06)
     *
     * @param  string $key
     *
     * @return bool
     */
    public function isValid(string $key = '')
    {
        return !$this->hasErrors($key);
    }

    /**
     * remove errors
     *
     * @author Nick Wakeman <nick@thehiredgun.tech>
     * @since  1.0.0 (2017-11-06)
     *
     * @param  string $key
     *
     * @throws InvalidArgumentException
     */
    public function removeErrors(string $key)
    {
        if (!isset($this->errors[$key])) {
            Throw new InvalidArgumentException();
        }
        unset($this->errors[$key]);
    }
}
