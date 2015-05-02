<?php

namespace ifw\web;

class Response extends \ifw\core\Component
{
    public $statusCode = 200;
    
    private $_contentType = 'text/html';
    
    public function setStatusCode($code)
    {
        $this->statusCode = $code;
    }
    
    public function setContentType($type)
    {
        switch($type) {
            case "text":
                $this->_contentType = 'text/html';
                break;
            case "json":
                $this->_contentType = 'application/json';
                break;
            case "xml":
                $this->_contentType = 'text/xml';
                break;
            default:
                throw new \Exception("The ContentType '$type' does not exist.");
                break;
        }
    }
    
    public function getHeader()
    {
        http_response_code($this->statusCode);
        header('Content-Type: ' . $this->_contentType);
    }
}