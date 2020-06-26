<?php

namespace App\Repository;

use App\Entity\AccessCode;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method AccessCode|null find($id, $lockMode = null, $lockVersion = null)
 * @method AccessCode|null findOneBy(array $criteria, array $orderBy = null)
 * @method AccessCode[]    findAll()
 * @method AccessCode[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AccessCodeRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, AccessCode::class);
    }

//    /**
//     * @return AccessCode[] Returns an array of AccessCode objects
//     */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('a.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?AccessCode
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
