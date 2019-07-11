<?php

declare(strict_types=1);

namespace App\Controller;

use ApiPlatform\Core\Api\IriConverterInterface;
use App\DTO\Rent;
use App\Entity\Car;
use App\Entity\CarUnavailability;
use App\Core\RentPriceCalculator;
use Doctrine\ORM\EntityManagerInterface;
use App\Exception\CarNotAvailableException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Lock\Factory;
use Symfony\Component\Lock\Store\SemaphoreStore;

class RentAction
{
    /** @var RentPriceCalculator */
    private $rentPriceCalculator;

    /** @var IriConverterInterface */
    private $iriConverter;

    /** @var EntityManagerInterface */
    private $em;

    /**
     * @param RentPriceCalculator    $rentPriceCalculator
     * @param IriConverterInterface  $iriConverter
     * @param EntityManagerInterface $em
     */
    public function __construct(
        RentPriceCalculator $rentPriceCalculator,
        IriConverterInterface $iriConverter,
        EntityManagerInterface $em
    ) {
        $this->rentPriceCalculator = $rentPriceCalculator;
        $this->iriConverter = $iriConverter;
        $this->em = $em;
    }

    /**
     * @param Request $request
     *
     * @return Rent
     *
     * @throws \Exception
     */
    public function __invoke(Request $request): Rent
    {
        $data = \json_decode($request->getContent(), true);

        $carIri = $data['car'] ?? '';
        if ($dateStart = $data['dateStart'] ?? null) {
            $dateStart = new \DateTime($dateStart);
        }

        if ($dateEnd = $data['dateEnd'] ?? null) {
            $dateEnd = new \DateTime($dateEnd);
        }

        /** @var Car $car */
        $car = $this->iriConverter->getItemFromIri($carIri, ['fetch_data' => true]);

        if (!$car || !$dateStart || !$dateEnd) {
            throw new \InvalidArgumentException();
        }

        $store = new SemaphoreStore();
        $factory = new Factory($store);

        $lock = $factory->createLock(sprintf('rent-car-%d', $car->getId()));
        if ($lock->acquire(true)) {
            $rent = null;
            if ($this->em->getRepository(CarUnavailability::class)->isAvailable($car, $dateStart, $dateEnd)) {
                $price = $this->rentPriceCalculator->calculate($car, $dateStart, $dateEnd);
                $carUnavailability = (new CarUnavailability())
                    ->setCar($car)
                    ->setStartDate((clone $dateStart)->setTimezone(new \DateTimeZone('UTC')))
                    ->setEndDate((clone $dateEnd)->setTimezone(new \DateTimeZone('UTC')))
                ;
                $this->em->persist($carUnavailability);
                $this->em->flush();

                $rent = new Rent(uniqid(), $car, $price);
            }
            $lock->release();
            
            
            if ($rent) {
                return $rent;
            }
        }

        throw new CarNotAvailableException('car not available');
    }
}
