<?php

namespace App\Domain\Sale\Data;

use Pilot\Component\Sql\Entity;

/**
 * @method int getId()
 * @method string getCreated_datetime()
 */
final class SaleEntity extends Entity
{
    /**
     * The isValid function in PHP always returns true.
     *
     * @return bool the `isValid` function is returning a boolean value of `true`
     */
    public function isValid(): bool
    {
        return true;
    }
}
