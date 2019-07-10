<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use ApiPlatform\Core\Annotation\ApiResource;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Context\ExecutionContextInterface;

/**
 * @ORM\Entity(repositoryClass="App\Repository\CarUnavailabilityRepository")
 * @ApiResource(
 *     collectionOperations={"post"},
 * )
 */
class CarUnavailability
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="Car")
     * @Assert\NotNull
     */
    private $car;

    /**
     * @ORM\Column(type="datetime")
     * @Assert\GreaterThan("now")
     */
    private $startDate;

    /**
     * @ORM\Column(type="datetime")
     */
    private $endDate;

    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @param Car $car
     *
     * @return static
     */
    public function setCar(Car $car): self
    {
        $this->car = $car;

        return $this;
    }

    /**
     * @return Car
     */
    public function getCar(): Car
    {
        return $this->car;
    }

    /**
     * @param \DateTime $startDate
     *
     * @return static
     */
    public function setStartDate(\DateTime $startDate): self
    {
        $this->startDate = $startDate;

        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getStartDate(): \DateTime
    {
        return $this->startDate;
    }

    /**
     * @param \DateTime $endDate
     *
     * @return static
     */
    public function setEndDate(\DateTime $endDate): self
    {
        $this->endDate = $endDate;

        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getEndDate(): \DateTime
    {
        return $this->endDate;
    }

    /**
     * @param ExecutionContextInterface $context
     * @param $payload
     *
     * @Assert\Callback
     */
    public function validate(ExecutionContextInterface $context, $payload): void
    {
        if ($this->getEndDate() < $this->getStartDate()) {
            $context->buildViolation('end date must be superior to start date')
                ->atPath('endDate')
                ->addViolation();
        }
    }
}
