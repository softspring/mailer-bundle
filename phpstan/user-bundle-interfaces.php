<?php

// Static analysis definitions for the optional softspring/user-bundle integration.
// This file is never loaded by the application.
namespace Softspring\UserBundle\Model;

interface NameSurnameInterface
{
    public function getName(): ?string;

    public function setName(?string $name): void;

    public function getSurname(): ?string;

    public function setSurname(?string $surname): void;
}

interface UserWithEmailInterface
{
    public function getEmail(): ?string;

    public function setEmail(?string $email): void;
}
