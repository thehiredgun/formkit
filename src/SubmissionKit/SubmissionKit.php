<?php

namespace FormKit\SubmissionKit;

use Exception;
use Illuminate\Database\Eloquent\Model;
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
    private $request;
    private $rules;
    private $errors;

    /**
     * construct
     *
     * @param Request $request
     * @param array   $rules
     * @param bool    $validateCsrfToken
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
     * set properties on an Eloquent object
     *
     * @param Model $object
     * @param mixed $properties
     * @param array $options
     */
    public function setProperties(Model $object, $properties = '*', $options = [])
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
     * @param Model  $object
     * @param string $name
     * @param array  $options
     */
    private function setProperty(Model $object, string $name, $options = [])
    {
        if ('_token' != $name) {
            if (!$this->hasErrors($name)) {
                $object->$name = $this->request->input($name);
            }
        }
    }
}
