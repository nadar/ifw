<?php

namespace ifw\web;

use Ifw;

class User extends \ifw\core\Component
{
    public $isGuest = true;
    
    public $identity = null;
    
    public function status()
    {
    
    }
    
    public function login(\ifw\core\Identity $userModel)
    {
        if ($this->identity !== null) {
            throw new \ifw\core\Exception("Another user is already logged in. Logout first.");
        }
        
        $id = $userModel->getId();
        
        Ifw::$app->session->user = $id;
        
        $this->identity = $userModel;
        
        $this->isGuest = false;
        
        return true;
    }
    
    public function logout()
    {
        if ($this->identity !== null) {
            unset(Ifw::$app->session->user);
            $this->isGuest = true;
            $this->identity = null;
        }
    }
}