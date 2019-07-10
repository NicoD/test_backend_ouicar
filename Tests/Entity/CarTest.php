<?php

declare(strict_types=1);

namespace Tests\App\Entity;

use App\Entity\Car;
use PHPUnit\Framework\TestCase;
use Psr\Log\InvalidArgumentException;

class CarTest extends TestCase
{
    public function testExactMileage(): void
    {
        $car = new Car();

        $car->setExactMileage(1000);
        $this->assertEquals(1, $car->getMileage());

        $car->setExactMileage(60000);
        $this->assertEquals(2, $car->getMileage());

        $car->setExactMileage(120000);
        $this->assertEquals(3, $car->getMileage());

        $car->setExactMileage(10000000);
        $this->assertEquals(4, $car->getMileage());
    }

    public function testInvalidMileage(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $car = new Car();
        $car->setExactMileage(1000);
        $car->setMileage(4);
    }
}
