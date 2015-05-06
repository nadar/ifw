<?php

namespace ifw\web;

/**
 * Handling PHP SESSIONS via ifw::$app->session component.
 *
 * Set a variable into a session
 * ------------------------------
 *
 * ```
 * \ifw::$app->session->set('key', 'value');
 * ```
 * which is equivalent to
 * ```
 * \ifw::$app->session->key = 'value';
 * ```
 *
 * Get a variable from the session
 * -------------------------------
 *
 * ```
 * \ifw::$app->session->get('key', 'default_value_if_not_exists');
 * ```
 * which is equivalent to
 * ```
 * \ifw::$app->session->key;
 * ``
 * The default value for the magic access method is null.
 *
 * Get Session from identifier
 * ---------------------------
 * If the session id can not be provided via cookie you can provide a session id via get or post the resume
 * a current session.
 *
 * Resume a session based on the session id:
 * ```
 * \ifw::app->session->setId('dcff7df64b796b759ece2eceea08d242');
 * ```
 *
 * To access the session id you can always call getId:
 * ```
 * \ifw::$app->session->getId();
 * ```
 *
 * @author nadar
 */
class Session extends \ifw\core\Component
{
    private $_resumeIdentifier = null;

    private $_session = [];

    private $_status = null;

    public function ensure()
    {
        if (!$this->isActive()) {
            session_start($this->_resumeIdentifier);
        }
    }

    public function isActive()
    {
        return (session_status() === PHP_SESSION_ACTIVE) ? true : false;
    }

    public function setId($sessionIdentifier)
    {
        if ($this->isActive()) {
            throw new \ifw\core\Exception('can not set session id when session is already active.');
        }
        $this->_resumeIdentifier = $sessionIdentifier;
    }

    public function getId()
    {
        return ($this->isActive()) ? session_id() : false;
    }

    public function __set($key, $value)
    {
        $this->set($key, $value);
    }

    public function set($key, $value)
    {
        $this->ensure();
        $_SESSION[$key] = $value;
    }

    public function __get($key)
    {
        if ($this->has($key)) {
            return $this->get($key);
        }

        parent::__get($key);
    }

    public function __unset($key)
    {
        if ($this->has($key)) {
            unset($_SESSION[$key]);
        }
    }
    
    public function has($key)
    {
        $this->ensure();

        return array_key_exists($key, $_SESSION);
    }

    public function get($key, $defaultValue = null)
    {
        $this->ensure();

        return ($this->has($key)) ? $_SESSION[$key] : $defaultValue;
    }
}
