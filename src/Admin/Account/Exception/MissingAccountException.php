<?php

namespace App\Admin\Account\Exception;

class MissingAccountException extends \RuntimeException
{
    public function __construct(int $id, int $code = 0, ?\Throwable $previous = null)
    {
        parent::__construct(sprintf('Cannot find book with id %s', $id), $code, $previous);
    }
}
