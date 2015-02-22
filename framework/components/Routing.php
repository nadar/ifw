<?php
namespace ifw\components;

class Routing extends \ifw\core\Component
{
    public $rewriteEnabled = false;
    
    public $rules = [];
    
    public function parseRules($matchingRoute)
    {
        if (!$matchingRoute) {
            return $matchingRoute;
        }
        foreach ($this->rules as $key => $item) {
            // @TODO match against regex rules not justing string matching
            if ($routeMatching == $item['pattern']) {
                return $item['route'];
            }
        }
        
        return $matchingRoute;
    }
    
    public function getRoute($request)
    {
        if ($this->rewriteEnabled) {
            $route = $request->getRequestPathSuffix();
        } else {
            $route = $request->get('route', false);
        }
        
        return $route;
    }
    
    public function getRouting($app, $request)
    {
        $route = $this->getRoute($request);
        $route = $this->parseRules($route);
        
        if (!$route) {
            return [$app->defaultModule, 'index', 'index'];
        }
        
        $parts = explode("/", $route);
        return $parts;
    }
}