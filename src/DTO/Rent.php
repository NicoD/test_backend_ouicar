<?php

namespace App\DTO;

use ApiPlatform\Core\Annotation\ApiProperty;
use ApiPlatform\Core\Annotation\ApiResource;
use App\Entity\Car;

/**
 * @ApiResource(
 *     itemOperations={"get"},
 *     collectionOperations={
 *      "rent"={
 *         "method"="POST",
 *         "path"="/rent",
 *         "controller"=App\Controller\RentAction::class,
 *         "read"=false,
 *         "defaults"={"_api_receive"=false},
 *         "swagger_context"={"summary"="Rent a car"}
 *     }
 *   }
 * )
 */
class Rent
{
    /**
     * @var string
     * @ApiProperty(identifier=true)
     */
    private $id;

    /** @var  */
    private $car;

    /** @var int */
    private $price;

    /**
     * @param Car $car
     * @param int $price
     */
    public function __construct(string $id, Car $car, int $price)
    {
        $this->id = $id;
        $this->car = $car;
        $this->price = $price;
    }

    public function getId(): string
    {
        return $this->id;
    }

    /**
     * @return Car
     */
    public function getCar(): Car
    {
        return $this->car;
    }

    /**
     * @return int
     */
    public function getPrice(): int
    {
        return $this->price;
    }
}
