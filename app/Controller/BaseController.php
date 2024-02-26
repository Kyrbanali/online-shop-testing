<?php

namespace Controller;


use Kurbanali\MyCore\AuthenticationServiceInterface;
use Kurbanali\MyCore\ViewRenderer;

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