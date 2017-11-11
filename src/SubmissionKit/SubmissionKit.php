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
        foreach ($rules as $key => $rule) {
            switch(gettype($rule)) {
                case 'string':
                    $elementRules = explode('|', $rule);
                    foreach ($elementRules as $elementRule) {
                        $this->rules[$key][] = trim($elementRule);
                    }
                break;
                case 'array':
                    $this->rules[$key] = $rule;
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
                foreach ($this->rules as $key => $rule) {
                    if ($errorsForInput = $formErrors->get($key)) {
                        if ('_token' === $key) {
                            $this->errors[$key] = ['A System Error Occurred'];
                        } else {
                            $this->errors[$key] = $errorsForInput;
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
     * @param  string $key
     *
     * @return bool
     */
    public function hasErrors(string $key = '')
    {
        return ('' === $key) ? (bool) count($this->errors) : isset($this->errors[$key]);
    }

    /**
     * get errors (for the form or for the property)
     *
     * @author Nick Wakeman <nick@thehiredgun.tech>
     *
     * @param  string $key
     *
     * @return array
     */
    public function getErrors(string $key = '')
    {
        return ('' === $key) ? $this->errors : $this->errors[$key];
    }

    /**
     * add error
     *
     * @author Nick Wakeman <nick@thehiredgun.tech>
     *
     * @param  string $key
     * @param  string $error
     */
    public function addError(string $key, string $error)
    {
        $this->errors[$key][] = $error;
    }

    /**
     * set errors
     *
     * @author Nick Wakeman <nick@thehiredgun.tech>
     *
     * @param  string $key
     * @param  array  $errors
     */
    public function setErrors(string $key, array $errors)
    {
        $this->errors[$key] = $errors;
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
            foreach ($this->rules as $key => $rule) {
                $this->setProperty($object, $key);
            }
        } elseif (is_array($properties)) {
            foreach ($properties as $key) {
                $this->setProperty($object, $key);
            }
        } elseif (is_string($properties)) {
            foreach (explode(',', $properties) as $key) {
                $this->setProperty($object, $key);
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
     * @param  string $key
     * @param  array  $options
     */
    protected function setProperty($object, string $key, $options = [])
    {
        if ('_token' != $key) {
            if (!$this->hasErrors($key)) {
                $object->$key = $this->request->input($key);
            }
        }
    }

    /**
     * is valid: the inverse of hasErrors()
     *
     * @author Nick Wakeman <nick@thehiredgun.tech>
     * @since  0.7.0 (2017-11-10)
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
     * @since  0.7.0 (2017-11-10)
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
