<?php

namespace ifw\behaviors;

use Ifw;

class Json extends \ifw\core\Behavior
{
    public function init()
    {
        $this->context->on('EVENT_AFTER_ACTION', [$this, 'transform']);
    }

    public function transform()
    {
        $content = $this->context->response;
        if (!is_string($content) && (is_array($content) ||  is_object($content))) {
            Ifw::$app->response->setContentType('json');
            $this->context->response = $this->encode($content);

            return true;
        }

        throw new \Exception('Invalid action response. The provided response must be an array or object.');
    }

    public function encode($content)
    {
        return json_encode($content);
    }
}
