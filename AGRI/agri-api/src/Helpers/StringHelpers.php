<?php

namespace App\Helpers;

use Ramsey\Uuid\Uuid;

class StringHelpers
{
    /**
     * @return string
     * @throws \Exception
     */
    public function generateUuid(): string
    {
        return Uuid::uuid4()->toString();
    }

    /**
     * @return string
     */
    public function generateAlternativeUuid(): string
    {
        return sprintf(
            '%04X%04X-%04X-%04X-%04X-%04X%04X%04X',
            random_int(0, 65535),
            random_int(0, 65535),
            random_int(0, 65535),
            random_int(16384, 20479),
            random_int(32768, 49151),
            random_int(0, 65535),
            random_int(0, 65535),
            random_int(0, 65535)
        );
    }
}
