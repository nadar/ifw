<?php

namespace ifw\behaviors;

class Json extends \ifw\core\Behavior
{
    public function init()
    {
        $this->context->on('EVENT_AFTER_ACTION', [$this, 'encode']);
    }
    
    public function encode()
    {
        $content = $this->context->response;
        if (!is_string($content) && (is_array($content) || is_object($content))) {
            $this->context->response = json_encode($content);
            return true;
        }
        
        throw new \Exception('Invalid action response. The provided response must be an array or object.');
    }
}