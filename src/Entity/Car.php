<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 */
class Car
{
    private const MAX_MILEAGE = 4;

    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="integer")
     */
    private $mileage;

    /**
     * @ORM\Column(type="integer")
     */
    private $priceDay1;

    /**
     * @ORM\Column(type="integer")
     */
    private $priceDay3;

    /**
     * @ORM\Column(type="integer")
     */
    private $priceDay7;

    /**
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @param int $mileage
     *
     * @return static
     *
     * @throws \Exception
     */
    public function setMileage(int $mileage): self
    {
        if ($mileage > self::MAX_MILEAGE) {
            throw new \Exception('invalid mileage');
        }
        $this->mileage = $mileage;

        return $this;
    }

    /**
     * @return int
     */
    public function getMileage(): int
    {
        return $this->mileage;
    }

    /**
     * @param int
     *
     * @return static
     */
    public function setPriceDay1(int $priceDay1): self
    {
        $this->priceDay1 = $priceDay1;

        return $this;
    }

    /**
     * @return int
     */
    public function getPriceDay1(): int
    {
        return $this->priceDay1;
    }

    /**
     * @param int
     *
     * @return static
     */
    public function setPriceDay3(int $priceDay3): self
    {
        $this->priceDay3 = $priceDay3;

        return $this;
    }

    /**
     * @return int
     */
    public function getPriceDay3(): int
    {
        return $this->priceDay3;
    }

    /**
     * @param int
     *
     * @return static
     */
    public function setPriceDay7(int $priceDay7): self
    {
        $this->priceDay7 = $priceDay7;

        return $this;
    }

    /**
     * @return int
     */
    public function getPriceDay7(): int
    {
        return $this->priceDay7;
    }
}
