<?php

declare(strict_types=1);

namespace Tests\App\Core;

use App\Core\RentPriceCalculator;
use App\Entity\Car;
use PHPUnit\Framework\TestCase;
use Psr\Log\InvalidArgumentException;

class RentPriceCalculatorTest extends TestCase
{
    public function testCalculatePrice(): void
    {
        $car = (new Car())
            ->setPriceDay1(5)
            ->setPriceDay3(4)
            ->setPriceDay7(3);

        $calculator = new RentPriceCalculator();

        $this->assertSame(5,
            $calculator->calculate(
                $car,
                new \DateTime('2019-04-22T04:00:00-05:00'),
                new \DateTime('2019-04-22T19:00:00-05:00')
            )
        );

        $this->assertSame(10,
            $calculator->calculate(
                $car,
                new \DateTime('2019-04-22T04:00:00-05:00'),
                new \DateTime('2019-04-23T19:00:00-05:00')
            )
        );

        $this->assertSame(14,
            $calculator->calculate(
                $car,
                new \DateTime('2019-04-22T04:00:00-05:00'),
                new \DateTime('2019-04-24T19:00:00-05:00')
            )
        );

        $this->assertSame(22,
            $calculator->calculate(
                $car,
                new \DateTime('2019-04-22T04:00:00-05:00'),
                new \DateTime('2019-04-26T19:00:00-05:00')
            )
        );

        $this->assertSame(29,
            $calculator->calculate(
                $car,
                new \DateTime('2019-04-22T04:00:00-05:00'),
                new \DateTime('2019-04-28T19:00:00-05:00')
            )
        );

        $this->assertSame(41,
            $calculator->calculate(
                $car,
                new \DateTime('2019-04-22T04:00:00-05:00'),
                new \DateTime('2019-05-02T19:00:00-05:00')
            )
        );
    }

    public function testInvalidDate(): void
    {
        $this->expectException(InvalidArgumentException::class);

        $car = (new Car())
            ->setPriceDay1(5)
            ->setPriceDay3(4)
            ->setPriceDay7(3);

        $calculator = new RentPriceCalculator();

        $calculator->calculate(
            $car,
            new \DateTime('2019-04-22T19:00:00-05:00'),
            new \DateTime('2019-04-22T04:00:00-05:00')
        );
    }
}
