<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\Car;
use Doctrine\ORM\EntityRepository;

class CarUnavailabilityRepository extends EntityRepository
{
    /**
     * @param Car       $car
     * @param \DateTime $from
     * @param \DateTime $to
     *
     * @return bool
     *
     * @throws \Doctrine\ORM\NoResultException
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function isAvailable(Car $car, \DateTime $from, \DateTime $to): bool
    {
        if ($to < $from) {
            return false;
        }
        $query = $this->createQueryBuilder('availability')
            ->select('COUNT(availability.id)')
            ->andWhere('availability.car = :car')
            ->andWhere(
                '(:startDate >= availability.startDate AND :startDate < availability.endDate) OR '.
                '(:endDate >= availability.startDate AND :endDate < availability.endDate)'
            )
            ->setParameter('car', $car)
            ->setParameter('startDate', (clone $from)->setTimezone(new \DateTimeZone('UTC')))
            ->setParameter('endDate', (clone $to)->setTimezone(new \DateTimeZone('UTC')))
            ->getQuery()
        ;

        return 0 === (int) $query->getSingleScalarResult();
    }
}
