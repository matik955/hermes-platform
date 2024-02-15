<?php

namespace App\Core\Account\Interface;


interface CopyDefinitionInterface
{
    public function getId(): ?int;
    public function getSourceAccount(): ?AccountInterface;
    public function getTargetAccount(): ?AccountInterface;
}
