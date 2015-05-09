<?php

namespace ifw\web;

use Ifw;

class Routing extends \ifw\core\Component
{
    public $rewriteEnabled = false;

    public $rules = [];

    /**
     * @todo wrong integration when using cli application!
     *
     * @param unknown $request
     *
     * @return unknown
     */
    public function getRoute($request)
    {
        if ($this->rewriteEnabled) {
            $route = $request->getRequestPathSuffix();
        } else {
            $route = $request->get('route', false);
        }

        return $route;
    }

    /**
     * The below example shows how use the pattern and route arguments. All examples are made with the news
     * module example. ***news*** represent the module, ***article*** the controller and ***detail*** the action sindie the article
     * controller.
     *
     * ````php
     * addRule('news/article/detail', 'news-detail');
     * ```
     * The aboce rule would go to the route "news/article/detail" without any parementer.
     *
     * ```php
     * addRule('news/article/detail', 'news-detail/<rewrite:\w+>');
     * ```
     *
     * If the input url adresse would be "news-detail/my-first-article" the route would redirect
     * to "news/article/detail" with the GET paramter rewrite=my-first-article. If you want to match an
     * numeric value use <id:\d+> instead.
     *
     * The next step is using the paremter as a route pattern rule. If we want to change the action
     * based on an input parameter we have to build the rule liks this:
     *
     * ```php
     * addRule('news/article/<action>', 'news-detail/<action:\w+>/<id:\d+>');
     * ```
     *
     * if the input url adresse would be "news-detail/detail/1" it would route to "news/article/detail" with get param id=1.
     * if the input url adresse would be "news-detail/archive/1" if would route to "news/article/archive" with get param id=1.
     *
     * @param string $route   Route where the pattern goes to e.g. "module/controller/<action>"
     * @param string $pattern The pattern which matches against the url e.g. "news-detail/<action:\w+>/<id:\d+>"
     */
    public function addRule($route, $pattern)
    {
        $this->rules[] = ['route' => $route, 'pattern' => $pattern];
    }

    /**
     * @todo test $param value against rule/match regex.
     *
     * @param string $route
     * @param array  $params
     *
     * @return string
     */
    public function createUrl($route, array $params = [])
    {
        foreach ($this->rules as $rule) {
            if ($rule['route'] == $route) {
                preg_match_all('/<(\w+):?([^>]+)?>/', $rule['pattern'], $matches, PREG_SET_ORDER);
                $url = $rule['pattern'];
                $i = 0;
                foreach ($matches as $match) {
                    if (count($match) !== 3) {
                        continue;
                    }
                    if (array_key_exists($match[1], $params)) {
                        $i++;
                        $url = str_replace($match[0], $params[$match[1]], $url);
                        unset($params[$match[1]]);
                    }
                }
                if (count($matches) == $i) {
                    return $this->buildUrl($url, $params);
                }
            }
        }

        return $this->buildUrl($route, $params);
    }

    private function buildUrl($url, array $params)
    {
        if ($this->rewriteEnabled) {
            return (count($params) > 0) ? $url.'?'.http_build_query($params) : $url;
        } else {
            $params['route'] = $url;
            $url =  '?' . http_build_query($params, '', '&', PHP_QUERY_RFC3986);
            return str_replace("%2F", "/", $url);
        }
    }

    /**
     * @todo rename parseRequest
     *
     * @param unknown $requestRoute
     *
     * @return unknown|mixed
     */
    public function parseRules($requestRoute)
    {
        if (!$requestRoute) {
            return $requestRoute;
        }

        $requestRouteParts = explode('/', $requestRoute);

        foreach ($this->rules as $key => $item) {
            $error = false;
            $regex = [];
            $params = [];
            foreach (explode('/', $item['pattern']) as $key => $part) {
                if ($error) {
                    continue;
                }
                // see if part is a regex part:
                if (preg_match('/<(.*?)>/', $part)) {
                    preg_match('/<(\w+):?([^>]+)?>/', $item['pattern'], $matches);
                    if (isset($matches[2])) {
                        $regex[] = $matches[2];
                        if (!array_key_exists($key, $requestRouteParts)) {
                            $error = true;
                            continue;
                        }
                        $params[$matches[1]] = $requestRouteParts[$key];
                    } else {
                        $error = true;
                        continue;
                    }
                } else {
                    $regex[] = "\b$part\b";
                }
            }
            if ($error) {
                continue;
            }

            if (preg_match('/'.implode("\/", $regex).'/', $requestRoute, $matches)) {
                preg_match_all('/<(.*?)>/', $item['route'], $routeMatch, PREG_SET_ORDER);
                foreach ($routeMatch as $match) {
                    $item['route'] = str_replace($match[0], $params[$match[1]], $item['route']);
                }
                foreach ($params as $key => $value) {
                    Ifw::$app->request->setGet($key, $value);
                }

                return $item['route'];
            }
        }

        return $requestRoute;
    }

    public function getRouting($app, $request)
    {
        $route = $this->getRoute($request);
        $route = $this->parseRules($route);
        $parts = explode('/', $route);

        /*
        if (!$route || count($parts) !== 3) {
            return [$app->defaultModule, 'index', 'index'];
        }
        */
        return $parts;
    }
}
