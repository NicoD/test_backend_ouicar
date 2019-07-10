<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use ApiPlatform\Core\Annotation\ApiResource;
use Psr\Log\InvalidArgumentException;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Context\ExecutionContextInterface;

/**
 * @ORM\Entity
 * @ApiResource(
 *     collectionOperations={"post", "get"},
 *     itemOperations={
 *      "get"
 *   }
 * )
 */
class Car
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="integer")
     * @Assert\Range(min=0, max=4)
     */
    private $mileage;

    /**
     * @ORM\Column(type="integer", nullable=true)
     * @Assert\GreaterThanOrEqual(1)
     */
    private $exactMileage;

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
     */
    public function setMileage(int $mileage): self
    {
        if (null !== $this->exactMileage) {
            if ($mileage !== self::getMileageFromExactMileage($this->exactMileage)) {
                throw new InvalidArgumentException();
            }
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
     * @param int $mileage
     *
     * @return static
     */
    public function setExactMileage(int $mileage): self
    {
        $this->exactMileage = $mileage;

        $this->setMileage(self::getMileageFromExactMileage($mileage));

        return $this;
    }

    /**
     * @return int
     */
    public function getExactMileage(): int
    {
        return $this->exactMileage;
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

    /**
     * @param ExecutionContextInterface $context
     * @param $payload
     *
     * @Assert\Callback
     */
    public function validate(ExecutionContextInterface $context, $payload): void
    {
        if ($this->getPriceDay7() > $this->getPriceDay3()) {
            $context->buildViolation('price day 7 must be inferior to price day 3')
                ->atPath('priceDay7')
                ->addViolation();

            return;
        }
        if ($this->getPriceDay3() > $this->getPriceDay1()) {
            $context->buildViolation('price day 3 must be inferior to price day 1')
                ->atPath('priceDay3')
                ->addViolation();

            return;
        }
    }

    /**
     * @param int $exactMileage
     *
     * @return int
     *
     * @throws \OutOfBoundsException
     */
    private static function getMileageFromExactMileage(int $exactMileage): int
    {
        if ($exactMileage < 1) {
            throw new \OutOfBoundsException();
        }
        if ($exactMileage < 50000) {
            return 1;
        } elseif ($exactMileage < 100000) {
            return 2;
        } elseif ($exactMileage < 150000) {
            return 3;
        } else {
            return 4;
        }
    }
}
