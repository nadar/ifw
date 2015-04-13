<?php
namespace ifw\core;

/**
 * basic model class
 * 
 * Defines the models attributes/propertries and the corresponding rules to
 * each property.
 * 
 * @todo what happens if validate() validates against no rules?
 * @author nadar
 */
abstract class Model extends \ifw\core\Component
{
    public $scenario = 'default';
    
    public $safeAttributes = [];
    
    public $errors = [];
    
    /**
     * See if the attribute key exists as a class property and set the value.
     * 
     * @param string $key
     * @param string $value
     * @throws \ifw\core\Exception
     */
    public function setAttribute($key, $value)
    {
        /*
        if (!$this->hasProperty($key)) {
            throw new \ifw\core\Exception("The attribute property $key does not exsists in the model class " . $this->getClass());
        }
        */
        $this->$key = $value;
    }
    
    /**
     * The array key represents the class property and the value is the value to be set for the property.
     * 
     * @param array $values
     */
    public function setAttributes(array $values)
    {
        foreach ($values as $key => $value) {
            $this->setAttribute($key, $value);
        }
    }
    
    public function getAttribute($key)
    {
        return $this->$key;
    }
    
    public function addError($field, $message)
    {
        $this->errors[$field][] = $message;
    }
    
    public function getErrors()
    {
        return $this->errors;
    }
    
    /**
     * 
     * ``````php
     * return [
     *     'validatorName' => ['prop1', 'prop2'],
     *     ['validatorName', ['prop1', 'prop2']],
     *     ['validatorName', ['prop1', 'prop2'], 'on' => 'scenarioName']
     * ];
     * `````
     * @return multitype:
     */
    public function rules()
    {
        return [];
    }
    
    public function getValidators()
    {
        $validators = [];
        foreach ($this->rules() as $k => $v) {
            if (is_int($k)) {
                $filterName = $v[0];
                $attributes = $v[1];
                if(isset($v['on'])) {
                    $scenarion = $v['on'];
                    if ($scenarion !== $this->scenario) {
                        continue;
                    }
                }
            } else {
                $filterName = $k;
                $attributes = $v;
            }
            
            $validators[$filterName] = $attributes;
        }
        
        return $validators;
    }
    
    public function addSafeAttribute($attribute)
    {
        if (!in_array($attribute, $this->safeAttributes)) {
            $this->safeAttributes[] = $attribute;
        }
    }
    
    public function getSafeAttributes()
    {
        return $this->safeAttributes;
    }
    
    /**
     * match and validate the current scenario rules against propertys
     * 
     * @todo 
     */
    public function validate()
    {
        $error = false;
        foreach ($this->getValidators() as $validator => $attributes) {
            foreach ($attributes as $attribute) {
                $validationResponse = $this->validator($validator, $this->getAttribute($attribute));
                if ($validationResponse !== null & !$validationResponse) {
                    $error = true;
                    $this->addError($attribute, "validator '$validator' error on attribute '$attribute'");
                } else {
                    $this->addSafeAttribute($attribute);
                }
            }
        }
        return !$error;
    }
    
    public function resetValidation()
    {
        $this->safeAttributes = [];
        $this->errors = [];
    }
    
    private function validator($name, $values)
    {
        if ($this->hasMethod($name)) {
            return $this->$name($values);
        } else {
            $className = '\\ifw\\validators\\' . $name;
            return (new $className)->run($values);
        }
    }
}