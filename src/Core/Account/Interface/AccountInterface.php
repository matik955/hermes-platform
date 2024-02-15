<?php

namespace App\Core\Account\Interface;

interface AccountInterface
{
    public function getId(): ?int;

    public function getLogin(): ?string;

    public function getPassword(): string;

    public function getTradeServer(): string;

    public function getMtVersion(): int;

    public function getBalance(): ?float;
    public function getSourceDefinitions(): array;

}
