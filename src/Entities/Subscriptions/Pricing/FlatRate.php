<?php
/**
 * This source file is proprietary and part of Rebilly.
 *
 * (c) Rebilly SRL
 *     Rebilly Ltd.
 *     Rebilly Inc.
 *
 * @see https://www.rebilly.com
 */

namespace Rebilly\Entities\Subscriptions\Pricing;

use Rebilly\Entities\Subscriptions\PlanPricing;

final class FlatRate extends PlanPricing
{
    protected function formula()
    {
        return self::FLAT_RATE;
    }

    public function getPrice()
    {
        return $this->getAttribute('price');
    }

    public function setPrice($value)
    {
        return $this->setAttribute('price', $value);
    }

    public function getMaxQuantity()
    {
        return $this->getAttribute('maxQuantity');
    }

    public function setMaxQuantity($value)
    {
        return $this->setAttribute('maxQuantity', $value);
    }
}
