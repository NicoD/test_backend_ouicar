<?php

declare(strict_types=1);

namespace App\Core;

use App\Entity\Car;
use Psr\Log\InvalidArgumentException;

class RentPriceCalculator
{
    /**
     * @param Car       $car
     * @param \DateTime $dateStart
     * @param \DateTime $dateEnd
     *
     * @return int
     *
     * @throws InvalidArgumentException
     */
    public function calculate(Car $car, \DateTime $dateStart, \DateTime $dateEnd): int
    {
        if ($dateEnd <= $dateStart) {
            throw new InvalidArgumentException('end date must be after date start');
        }

        $nbDays = (int) (clone $dateEnd)->diff($dateStart)->format('%a') + 1;

        $price = min(2, $nbDays) * $car->getPriceDay1();
        $nbDays = max(0, $nbDays - 2);

        $price += min(4, $nbDays) * $car->getPriceDay3();
        $nbDays = max(0, $nbDays - 4);

        $price += $nbDays * $car->getPriceDay7();

        return $price;
    }
}
