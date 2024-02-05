<?php

namespace App\Admin\Account\Payload;

final readonly class AccountBalanceUpdatePayload
{
    public function __construct(
        public float $balance,
    ) {
    }
}
