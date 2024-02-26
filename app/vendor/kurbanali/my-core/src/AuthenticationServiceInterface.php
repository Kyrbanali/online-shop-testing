<?php

namespace Kurbanali\MyCore;
use Model\User;

interface AuthenticationServiceInterface
{
    public function login(string $email, string $password) : bool;

    public function check(): bool;

    public function getCurrentUser(): ?User;

    public function logout(): void;

}