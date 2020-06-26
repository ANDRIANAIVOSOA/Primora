<?php

namespace App\Repository;

use App\Entity\DataCapteurParJour;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method DataCapteurParJour|null find($id, $lockMode = null, $lockVersion = null)
 * @method DataCapteurParJour|null findOneBy(array $criteria, array $orderBy = null)
 * @method DataCapteurParJour[]    findAll()
 * @method DataCapteurParJour[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class DataCapteurParJourRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, DataCapteurParJour::class);
    }

    /**
     * @return DataCapteurParJour[] Returns an array of DataCapteurParJour objects
     */
    public function getAll()
    {
        return $this->createQueryBuilder('d')
            ->orderBy('d.sendingDateTime', 'DESC')
            ->groupBy('d.capteur')
            ->getQuery()
            ->getResult()
        ;
    }

    /**
     * @return DataCapteurParJour[] Returns an array of DataCapteurParJour objects
     */
    public function getBySendingDateTime($dt, $site = 1)
    {
        $qb = $this->_em->createQuery("SELECT dc FROM App:DataCapteurParJour dc WHERE SUBSTRING(dc.sendingDateTime, 1, 10) = '" .$dt ."'  AND dc.isDeleted = 0 AND dc.siteId = ". $site);
         return $qb->getResult();
        ;
    }

    /**
     * @return DataCapteurParJour[] Returns an array of DataCapteurParJour objects
     */
    public function groupByDate($device, $siteId = 1)
    {
            $dataCapteurParJour = $this->createQueryBuilder('d')
            ->where('d.isDeleted = 0')
            ->andWhere('d.siteId = :site')
            ->andWhere('d.deviceId = :device')
            ->setParameter('site', $siteId)
            ->setParameter('device', $device)
            ->orderBy('d.sendingDateTime', 'ASC')
            ->getQuery()
            ->getResult();
        return $dataCapteurParJour;
    }

    public function findBySendingDate($sendingDateTimeStart, $sendingDateTimeEnd)
    {
        return $this->createQueryBuilder('d')
            ->where('d.sendingDateTime BETWEEN :sendingDateTimeStart AND :sendingDateTimeEnd')
            ->setParameter('sendingDateTimeStart', $sendingDateTimeStart)
            ->setParameter('sendingDateTimeEnd', $sendingDateTimeEnd)
            ->getQuery()
            ->getResult()
        ;
    }

    public function getByStartDateEndDateCapteur($sendingDateTimeStart, $sendingDateTimeEnd, $capteur, $deviceId, $site = 1)
    {
        $qb = $this->_em->createQuery("SELECT dc FROM App:DataCapteurParJour dc WHERE dc.capteur = '" . $capteur ."' AND SUBSTRING(dc.sendingDateTime, 1, 7) BETWEEN '" . $sendingDateTimeStart ."' AND '". $sendingDateTimeEnd ."' AND dc.deviceId = '" . $deviceId ."' AND dc.siteId = ". $site);
         return $qb->getResult();
    }

    public function getByStartDateEndDateSite($sendingDateTimeStart, $sendingDateTimeEnd, $site, $deviceId)
    {
        $qb = $this->_em->createQuery("SELECT dc FROM App:DataCapteurParJour dc WHERE SUBSTRING(dc.sendingDateTime, 1, 7) BETWEEN '" . $sendingDateTimeStart ."' AND '". $sendingDateTimeEnd ."' AND dc.deviceId = '" . $deviceId ."' AND dc.siteId = ". $site);
         return $qb->getResult();
    }

    public function getByStartDateEndDateCapteurSite($sendingDateTimeStart, $sendingDateTimeEnd, $capteur, $site, $deviceId)
    {
        $qb = $this->_em->createQuery("SELECT dc FROM App:DataCapteurParJour dc WHERE dc.capteur = '" . $capteur ."' AND SUBSTRING(dc.sendingDateTime, 1, 7) BETWEEN '" . $sendingDateTimeStart ."' AND '". $sendingDateTimeEnd ."' AND dc.deviceId = '" . $deviceId ."' AND dc.siteId = ". $site);
         return $qb->getResult();
    }

    public function compareGraph($sendingDateTimeStart, $sendingDateTimeEnd, $capteur, $site, $deviceId)
    {
        $qb = $this->_em->createQuery("SELECT dc FROM App:DataCapteurParJour dc WHERE dc.capteur = '" . $capteur ."' AND SUBSTRING(dc.sendingDateTime, 1, 7) = '" . $sendingDateTimeStart ."' OR SUBSTRING(dc.sendingDateTime, 1, 7) = '". $sendingDateTimeEnd ."' AND dc.deviceId = '" . $deviceId ."' AND dc.siteId = ". $site);
         return $qb->getResult();
    }

    public function getByStartDateEndDate($sendingDateTimeStart, $sendingDateTimeEnd, $deviceId, $site = 1)
    {
        $qb = $this->_em->createQuery("SELECT dc FROM App:DataCapteurParJour dc WHERE dc.deviceId = '" . $deviceId ."' AND SUBSTRING(dc.sendingDateTime, 1, 7) BETWEEN '" . $sendingDateTimeStart ."' AND '". $sendingDateTimeEnd ."' AND dc.siteId = ". $site);
         return $qb->getResult();
    }

    public function getByStartDateCapteur($sendingDateTimeStart, $capteur, $deviceId, $site = 1)
    {
        $qb = $this->_em->createQuery("SELECT dc FROM App:DataCapteurParJour dc WHERE dc.capteur = '" . $capteur ."' AND SUBSTRING(dc.sendingDateTime, 1, 7) = '" . $sendingDateTimeStart ."' AND dc.deviceId = '" . $deviceId ."' AND dc.siteId = ". $site);
         return $qb->getResult();
    }

    public function getBySiteCapteur($site, $capteur, $deviceId)
    {
        $qb = $this->_em->createQuery("SELECT dc FROM App:DataCapteurParJour dc WHERE dc.capteur = '" . $capteur ."' AND dc.siteId = $site  AND dc.deviceId = '" . $deviceId ."'");
         return $qb->getResult();
    }

    public function getBySiteCapteurStartDateEndDate($site, $capteur, $startDate, $endDate, $deviceId)
    {
        $qb = $this->_em->createQuery("SELECT dc FROM App:DataCapteurParJour dc WHERE dc.capteur = '" . $capteur ."' AND dc.siteId = $site AND SUBSTRING(dc.sendingDateTime, 1, 7) BETWEEN '" . $startDate ."' AND '". $endDate ."' AND dc.deviceId = '" . $deviceId ."'");
         return $qb->getResult();
    }

    public function getByStartDate($sendingDateTimeStart, $deviceId, $site = 1)
    {
        $qb = $this->_em->createQuery("SELECT dc FROM App:DataCapteurParJour dc WHERE SUBSTRING(dc.sendingDateTime, 1, 7) = '" . $sendingDateTimeStart ."' AND dc.deviceId = '" . $deviceId ."' AND dc.siteId = ". $site);
         return $qb->getResult();
    }

    public function getFreshData($freshData)
    {
        $qb = $this->createQueryBuilder('f');
        $qb->where('f.freshData >= :freshData')
           ->setParameter('freshData', $freshData);
        return $qb->getQuery()
              ->getResult();
    }
}
