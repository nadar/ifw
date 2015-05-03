<?php
namespace ifw\web;

use Ifw;

class View extends \ifw\core\Component
{
    protected $js = [];
    
    protected $css = [];
    
    public function renderFile($viewFile, array $params = [])
    {
        ob_start();
        extract($params);
        require($viewFile);
        $rendered = ob_get_contents();
        ob_end_clean();
        return $rendered;
    }
    
    public function findViewFile($viewFile, $path)
    {
        $view = \ifw::$app->getAlias(implode(DIRECTORY_SEPARATOR, [$path, $viewFile]));
        
        if (!file_exists($view)) {
            throw new \Exception("The view File '$view' does not exists.");
        }
        
        return $view;
    }
    
    public function endBody()
    {
        foreach($this->js as $key => $value) {
            $this->js[$key] = '<script src="'.$value.'"></script>';
        }
        
        return implode("\n\r", $this->js);
    }
    
    public function head()
    {
        foreach($this->css as $key => $value) {
            $this->css[$key] = '<link href="'.$value.'" rel="stylesheet" />';
        }
        
        return implode("\n\r", $this->css);
    }
    
    public function render($viewFile, array $params = [], $context)
    {
        // @todo change to array merger helper function
        $_vars = $params;
        $_vars['context'] = $context;
        return $this->renderFile($this->findViewFile($viewFile, $context->getViewPath()), $params, $context);
    }
    
    public function renderLayout($layoutFile, $viewFile, array $params = [], $context)
    {
        // @todo change to array merger helper function
        $_vars = $params;
        $_vars['context'] = $context;
        
        return $this->renderFile($this->findViewFile($layoutFile, $context->getLayoutPath()), [
            "content" => $this->renderFile($this->findViewFile($viewFile, $context->getViewPath()), $_vars)
        ], $context);
    }
    
    public function registerAsset($assetClass)
    {
        $object = Ifw::createObject($assetClass, ['view' => $this]);
        $this->css = array_merge($this->css, $object->getCss());
        $this->js = array_merge($this->js, $object->getJs());
        return $object;
    }
}