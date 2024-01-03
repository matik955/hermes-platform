<?php

namespace App\Core\User\Interface;

interface UserResourceInterface
{
    public function getId(): ?int;

    public function setId(int $id): void;

    public function getEmail(): ?string;

    public function getRoles(): array;

    public function getPassword(): string;
}
