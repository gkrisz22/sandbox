<?php 

/*
    Inspiráció: Zod - TypeScript alapú séma validátor
    - https://zod.dev/
    - https://github.com/colinhacks/zod
*/

/**
 * Validator osztály
 */
class Validator {

    private $schema;
    private $errors;

    public function __construct($schema) {
        $this->schema = $schema;
        $this->errors = [];
    }

    public function validate($data) {
        $this->errors = [];
        foreach ($this->schema as $field => $rules) {
            $value = isset($data[$field]) ? $data[$field] : null;
            $this->validateField($field, $value, $rules);
        }
        return empty($this->errors);
    }

    public function getErrors() {
        return $this->errors;
    }

    private function validateField($field, $value, $rules)
    {
        foreach ($rules as $rule => $ruleValue) {
            if($rule === 'required' && $ruleValue === false && is_null($value)) {
                break;
            }
            switch ($rule) {
                case 'required':
                    if ($ruleValue && is_null($value)) {
                        $this->errors[$field][] = 'Field is required';
                    }
                    break;
                case 'type':
                    if(is_null($value))
                        break;
                    
                    if (gettype($value) !== $ruleValue) {
                        $this->errors[$field][] = 'Field must be of type ' . $ruleValue;
                    }
                    break;
                case 'min':
                    if(is_null($value))
                        break;

                    if (is_string($value) && strlen($value) < $ruleValue) {
                        $this->errors[$field][] = 'Field must be at least ' . $ruleValue . ' characters long';
                    } else if (is_numeric($value) && $value < $ruleValue) {
                        $this->errors[$field][] = 'Field must be at least ' . $ruleValue;
                    }
                    break;
                case 'max':
                    if(is_null($value))
                        break;
                    if (is_string($value) && strlen($value) > $ruleValue) {
                        $this->errors[$field][] = 'Field must be no more than ' . $ruleValue . ' characters long';
                    } else if (is_numeric($value) && $value > $ruleValue) {
                        $this->errors[$field][] = 'Field must be no more than ' . $ruleValue;
                    }
                    break;
                case 'regex':
                    if(is_null($value))
                        break;
                    if (!preg_match($ruleValue, $value)) {
                        $this->errors[$field][] = 'Field does not match the required pattern';
                    }
                    break;
                case 'is_email': 
                    if(is_null($value))
                        break;
                    if (!filter_var($value, FILTER_VALIDATE_EMAIL)) {
                        $this->errors[$field][] = 'Field must be a valid email address';
                    }
                    break;
                case 'must_contain':
                    if(is_null($value))
                        break;
                    if (!str_contains($value, $ruleValue)) {
                        $this->errors[$field][] = 'Field must contain ' . $ruleValue;
                    }
                    break;
                default:
                    $this->errors[$field][] = 'Unknown validation rule ' . $rule;
            }
        }
    }

}