<?php

namespace Controller;

use Core\ViewRenderer;
use Service\Authentication\AuthenticationServiceInterface;

abstract class BaseController
{
    protected AuthenticationServiceInterface $authenticationService;
    protected ViewRenderer $renderer;

    public function __construct(AuthenticationServiceInterface $authenticationService, ViewRenderer $renderer)
    {
        $this->authenticationService = $authenticationService;
        $this->renderer = $renderer;
    }

}