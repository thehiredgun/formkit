<?php

namespace FormKit\SubmissionKit;

use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

/**
 * FormKit: a nice little toolkit to better manage forms in Laravel
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
    protected $rules;

    /**
     * @var array $errors
     */
    protected $errors;

    /**
     * construct
     *
     * @author Nick Wakeman <nick@thehiredgun.tech>
     *
     * @param  Request $request
     * @param  array   $rules
     * @param  bool    $validateCsrfToken
     */
    public function __construct(Request $request, array $rules, $validateCsrfToken = true)
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
                    Throw new Exception('$rules should be of type array or string, not ' . getType($rules));
                break;
            }
        }
        if ($validateCsrfToken) {
            $this->rules['_token'] = [
                'required',
                'in:' . csrf_token(),
            ];
        }
    }

    /**
     * validate
     *
     * @author Nick Wakeman <nick@thehiredgun.tech>
     *
     * @return bool
     */
    public function validate()
    {
        if (in_array($this->request->method(), ['POST', 'PUT'])) {
            $validator = Validator::make($this->request->all(), $this->rules);
            if ($formErrors = $validator->errors()) {
                foreach ($this->rules as $name => $rule) {
                    if ($errorsForInput = $formErrors->get($name)) {
                        if ('_token' === $name) {
                            $this->errors[$name] = ['A System Error Occurred'];
                        } else {
                            $this->errors[$name] = $errorsForInput;
                        }
                    }
                }
            }

            return true;
        }

        return false;
    }

    /**
     * has errors (for the form or for a property)
     *
     * @author Nick Wakeman <nick@thehiredgun.tech>
     *
     * @param  string $name
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
     * @author Nick Wakeman <nick@thehiredgun.tech>
     *
     * @param  string $name
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
     * @author Nick Wakeman <nick@thehiredgun.tech>
     *
     * @param  string $name
     * @param  string $error
     */
    public function addError(string $name, string $error)
    {
        $this->errors[$name][] = $error;
    }

    /**
     * set errors
     *
     * @author Nick Wakeman <nick@thehiredgun.tech>
     *
     * @param  string $name
     * @param  array  $errors
     */
    public function setErrors(string $name, array $errors)
    {
        $this->errors[$name] = $errors;
    }

    /**
     * set properties on an Eloquent object
     *
     * @author Nick Wakeman <nick@thehiredgun.tech>
     *
     * @param  mixed $object
     * @param  mixed $properties
     * @param  array $options
     */
    public function setProperties($object, $properties = '*', $options = [])
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
            Throw new Exception('$properties should be \'*\' or of type string or array, not ' . getType($properties));
        }
    }

    /**
     * set property on an object
     *
     * @author Nick Wakeman <nick@thehiredgun.tech>
     *
     * @param  mixed  $object
     * @param  string $name
     * @param  array  $options
     */
    protected function setProperty($object, string $name, $options = [])
    {
        if ('_token' != $name) {
            if (!$this->hasErrors($name)) {
                $object->$name = $this->request->input($name);
            }
        }
    }

    /**
     * is valid: the inverse of hasErrors()
     *
     * @author Nick Wakeman <nick@thehiredgun.tech>
     * @since  0.7.0 (2017-11-06)
     *
     * @param  string $key
     *
     * @return bool
     */
    public function isValid(string $key = '')
    {
        return !$this->hasErrors($key);
    }
}
