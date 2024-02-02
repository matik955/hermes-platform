<?php

namespace App\Admin\Account\Command;

final readonly class AccountBalanceUpdate
{
    public function __construct(
        public int $id,
        public float $balance,
    ) {
    }
}
