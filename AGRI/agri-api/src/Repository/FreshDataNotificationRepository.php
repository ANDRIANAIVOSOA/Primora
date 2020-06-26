<?php

namespace App\Repository;

use App\Entity\FreshDataNotification;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method FreshDataNotification|null find($id, $lockMode = null, $lockVersion = null)
 * @method FreshDataNotification|null findOneBy(array $criteria, array $orderBy = null)
 * @method FreshDataNotification[]    findAll()
 * @method FreshDataNotification[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class FreshDataNotificationRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, FreshDataNotification::class);
    }

//    /**
//     * @return FreshDataNotification[] Returns an array of FreshDataNotification objects
//     */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('f')
            ->andWhere('f.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('f.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?FreshDataNotification
    {
        return $this->createQueryBuilder('f')
            ->andWhere('f.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
    public function getFreshData($id)
    {
        $qb = $this->createQueryBuilder('f');
        $qb->select('MIN(f.freshData) AS min_fresh_data');
        return $qb->getQuery()
              ->getResult();
    }
}
