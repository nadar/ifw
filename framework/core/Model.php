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
    
    /**
     * See if the attribute key exists as a class property and set the value.
     * 
     * @param string $key
     * @param string $value
     * @throws \ifw\core\Exception
     */
    public function setAttribute($key, $value)
    {
        if (!$this->hasProperty($key)) {
            throw new \ifw\core\Exception("The attribute property $key does not exsists in the model class " . $this->getClass());
        }
        
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
    
    /**
     * 
     * ``````php
     * return [
     *     'filterName' => ['prop1', 'prop2'],
     *     ['filterName', ['prop1', 'prop2']],
     *     ['filterName', ['prop1', 'prop2'], 'on' => 'scenarioName']
     * ];
     * `````
     * @return multitype:
     */
    public function rules()
    {
        return [];
    }
    
    /**
     * match and validate the current scenario rules against propertys
     * 
     * @todo 
     */
    public function validate()
    {
        foreach ($this->rules() as $k => $v) {
            if (is_int($k)) {
                $filterName = $v[0];
                $filterProps = $v[1];
                if(isset($v['on'])) {
                    $scenarion = $v['on'];
                    if ($scenarion !== $this->scenario) {
                        continue;
                    }
                }
            } else {
                $filterName = $k;
                $filterProps = $v;
            }
            
            // validate $filterProps against $filterName
        }
    }
}