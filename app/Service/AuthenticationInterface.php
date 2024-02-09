<?php

namespace Service;
use Model\User;
interface AuthenticationInterface
{
    public function login(string $email, string $password) : bool;
    public function check(): bool;
    public function getCurrentUser(): ?User;
    public function logout(): void;

}