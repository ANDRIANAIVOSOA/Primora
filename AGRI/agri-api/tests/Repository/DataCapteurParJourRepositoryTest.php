<?php

namespace App\Tests\Repository;

use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use App\Entity\DataCapteurParJour;

class DataCapteurParJourRepositoryTest extends KernelTestCase
{
    /**
     * @var \Doctrine\ORM\EntityManager
     */
    private $em;

    protected function setUp()
    {
        $kernel = self::bootKernel();
        $this->em = static::$kernel->getContainer()->get('doctrine')->getManager();
    }

    protected function tearDown()
    {
        parent::tearDown();
        $this->em->close();
        $this->em = null;
    }

    /**
     * @group repository
     * @group repositoryDataCapteurParJour
     */
    public function testAfficheToutesDonneesInsererParJour()
    {
        $dcj = $this->em->getRepository(DataCapteurParJour::class)
            ->getAll();

            $this->assertInternalType('array', $dcj);
        foreach($dcj as $dataCapteurParJour) {
            $this->assertInstanceOf(DataCapteurParJour::class,$dataCapteurParJour);
            $this->assertObjectHasAttribute('id',$dataCapteurParJour);
            $this->assertAttributeSame('082df2a1-36e8-4cb1-8808-bb8b6022bba4', 'id', $dcj[0]);
            $this->assertAttributeSame('15eda8fd-2175-4a29-984e-c40b5b7defe8', 'id', $dcj[1]);
        }
    }
    /**
     *@group repository 
     *@group getBySendingDateTime
     */
    public function testGetBySendingDateTime()
    {
        $gety = $this->em->getRepository(DataCapteurParJour::class)->getBySendingDateTime('2018-10-08',1);
        
        foreach ($gety as $testGetBySendingDateTime) {
            $this->assertInstanceOf(DataCapteurParJour::class,$testGetBySendingDateTime);
            $this->assertObjectHasAttribute('id',$testGetBySendingDateTime);
            $this->assertAttributeSame('dd8f2731-18dd-4381-99d8-b1b30b9c22d1', 'id', $gety[0]);
        }
    }

    /**
     * @group repository
     * @group repositorytestfilterParFreshData
     */
    public function testFiltreParFreshData()
    {
        $datafiltre = $this->em->getRepository(DataCapteurParJour::class)
        ->getFreshData('2019-02-21');

            $this->assertInternalType('array', $datafiltre);
                foreach($datafiltre as $dataCapteurParFreshData) {
            $this->assertInstanceOf(DatacapteurParJour::class,$dataCapteurParFreshData);
            $this->assertObjectHasAttribute('id',$dataCapteurParFreshData);
            $this->assertAttributeSame("082df2a1-36e8-4cb1-8808-bb8b6022bba4",'id',$datafiltre[0]);
            $this->assertAttributeSame("1281fabb-fe89-426b-afa7-aed260d29c8e",'id',$datafiltre[1]);
                }
       
    }


    /**
     * @group repository
     * @group getBySiteCapteurStartDateEndDate
     */
    public function testGetBySiteCapteurStartDateEndDate()
    {
        $getBySite = $this->em->getRepository(DataCapteurParJour::class)
        ->getBySiteCapteurStartDateEndDate
            (1,"92f8a295-96d0-4fdc-b334-8da29c6d0612","2018-11","2019-02","gdvtevc85sz");  
        
        $this->assertInternalType('array', $getBySite);
            foreach($getBySite as $dataCapteurParFreshData) {
        $this->assertInstanceOf(DatacapteurParJour::class,$dataCapteurParFreshData);
        $this->assertObjectHasAttribute('id',$dataCapteurParFreshData);
        $this->assertAttributeSame("082df2a1-36e8-4cb1-8808-bb8b6022bba4",'id',$getBySite[0]);
        $this->assertAttributeSame("1281fabb-fe89-426b-afa7-aed260d29c8e",'id',$getBySite[1]);
            }
    }

    
    /**
     *@group repository 
     *@group testGroupByDatetime
    */
    public function testGroupByDate()
    {
        $get = $this->em->getRepository(DataCapteurParJour::class)
        ->GroupByDate("gdvtevc85sz",1);
        foreach ($get as $testGroupByDate) {
            $this->assertInstanceOf(DataCapteurParJour::class,$testGroupByDate);
            $this->assertObjectHasAttribute('id',$testGroupByDate);
            $this->assertAttributeSame('082df2a1-36e8-4cb1-8808-bb8b6022bba4', 'id', $get[0]);
            $this->assertAttributeSame('1281fabb-fe89-426b-afa7-aed260d29c8e', 'id', $get[1]);
        }
    }


    /**
     *@group repository
     *@group findBySendingDate
    */
    public function testFindBySendingDate()
    {
        $get = $this->em->getRepository(DataCapteurParJour::class)->findBySendingDate('2018-11-13','2019-02-18');
        // assert array length
        //$this->assertEquals([], $get);
        foreach ($get as $findBySendingDate) {
            $this->assertInstanceOf(DataCapteurParJour::class,$findBySendingDate);
            //$this->assertObjectHasAttribute('id',$findBySendingDate);
            //$this->assertAttributeSame('dc410093-8620-4275-9f83-96e58c5783b4', 'id', $get[2]);
        }
        $this->assertEquals('15eda8fd-2175-4a29-984e-c40b5b7defe8', $get[0]->getId());
        $this->assertEquals('f1217a77-4a59-4b28-9e72-28c171b824a0', $get[1]->getId());
    }


    /**
     * @group repository
     * @group getBySiteCapteur
     */
    public function testGetBySiteCapteur()
    {
      $SiteCapteur =  $this->em->getRepository(DataCapteurParJour::class)
      ->getBySiteCapteur(1,"92f8a295-96d0-4fdc-b334-8da29c6d0612","FD25FE-GG4HT5-GHRH");
      
        foreach ($SiteCapteur as $testGroupBySite) {
            $this->assertInstanceOf(DataCapteurParJour::class,$testGroupBySite);
            $this->assertAttributeSame('dd8f2731-18dd-4381-99d8-b1b30b9c22d1','id',$testGroupBySite);
        }
    }

    /**
     * @group repository
     * @group getByStartDateCapteur
     */
    public function testGetByStartDateCapteur()
    {
        $DateCapteur = $this->em->getRepository(DataCapteurParJour::class)
        ->getByStartDateCapteur("2018-11", "93288779-e43f-453c-a116-87b350b76550","FD25FE-GG4HT5-GHRH",1);
        
        foreach ($DateCapteur as $findByStartDateCapteur) {
            $this->assertInstanceOf(DataCapteurParJour::class,$findByStartDateCapteur);
        }
            $this->assertEquals('15eda8fd-2175-4a29-984e-c40b5b7defe8', $DateCapteur[0]->getId());
    }


    /**
     * @group repository
     * @group getByStartDate
     */
    public function testGetByStartDate()
    {
        $DateStart= $this->em->getRepository(DataCapteurParJour::class)
        ->getByStartDate("2019-01","FD25FE-GG4HT5-GHRH",1);
        
        foreach ($DateStart as $findByStartDate) {
            $this->assertInstanceOf(DataCapteurParJour::class,$findByStartDate);
        }
            $this->assertEquals('f1217a77-4a59-4b28-9e72-28c171b824a0', $DateStart[0]->getId());
    }


    /**
     * @group repository
     * @group compareGraph
     */
    public function testComparGraph()
    {
        $ComparGraph = $this->em->getRepository(DataCapteurParJour::class)
        ->compareGraph("2019-01","2019-02","93288779-e43f-453c-a116-87b350b76550",1,"gdvtevc85sz");
        
        foreach ($ComparGraph as $findByGraph) {
            $this->assertInstanceOf(DataCapteurParJour::class,$findByGraph);
        }
            $this->assertEquals('082df2a1-36e8-4cb1-8808-bb8b6022bba4', $ComparGraph[0]->getId());
            $this->assertEquals('1281fabb-fe89-426b-afa7-aed260d29c8e', $ComparGraph[1]->getId());
            $this->assertEquals('f1217a77-4a59-4b28-9e72-28c171b824a0', $ComparGraph[2]->getId());
    }


    /**
     * @group repository
     * @group getByStartDateEndDateCapteurSite
     */
    public function testGetByStartDateEndDateCapteurSite()
    {
        $getCapteurSite = $this->em->getRepository(DataCapteurParJour::class)
        ->getByStartDateEndDateCapteurSite("2018-10","2019-01","93288779-e43f-453c-a116-87b350b76550",1,"FD25FE-GG4HT5-GHRH");
        
        foreach ($getCapteurSite as $findByCapteurSite) {
            $this->assertInstanceOf(DataCapteurParJour::class,$findByCapteurSite);
        }
            $this->assertEquals('15eda8fd-2175-4a29-984e-c40b5b7defe8', $getCapteurSite[0]->getId());
            $this->assertEquals('f1217a77-4a59-4b28-9e72-28c171b824a0', $getCapteurSite[1]->getId());
    }
    

    /**
     * @group repository
     * @group getByStartDateEndDateSite
     */
    public function testGetByStartDateEndDateSite()
    {
        $getSite = $this->em->getRepository(DataCapteurParJour::class)
        ->getByStartDateEndDateSite("2018-10","2019-01",1,"FD25FE-GG4HT5-GHRH");
        
        foreach ($getSite as $findBySite) {
            $this->assertInstanceOf(DataCapteurParJour::class,$findBySite);
        }
            $this->assertEquals('15eda8fd-2175-4a29-984e-c40b5b7defe8', $getSite[0]->getId());
            $this->assertEquals('dd8f2731-18dd-4381-99d8-b1b30b9c22d1', $getSite[1]->getId());
            $this->assertEquals('f1217a77-4a59-4b28-9e72-28c171b824a0', $getSite[2]->getId());
    }


    /**
     * @group repository
     * @group getByStartDateEndDate
     */
    public function testGetStartDateEndDate()
    {
        
        $getDate = $this->em->getRepository(DataCapteurParJour::class)
        ->getByStartDateEndDate("2018-10","2019-01","FD25FE-GG4HT5-GHRH",1);
        
        foreach ($getDate as $findByDate) {
            $this->assertInstanceOf(DataCapteurParJour::class,$findByDate);
        }
            $this->assertEquals('15eda8fd-2175-4a29-984e-c40b5b7defe8', $getDate[0]->getId());
            $this->assertEquals('dd8f2731-18dd-4381-99d8-b1b30b9c22d1', $getDate[1]->getId());
            $this->assertEquals('f1217a77-4a59-4b28-9e72-28c171b824a0', $getDate[2]->getId());
        
    }


    /**
     * @group repository
     * @group getByStartDateEndDateCapteur
     * 
     */
    public function testGetByStartDateEndDateCapteur()
    {
        
        $getDateCapteur = $this->em->getRepository(DataCapteurParJour::class)
        ->getByStartDateEndDateCapteur("2018-10","2019-01","93288779-e43f-453c-a116-87b350b76550","FD25FE-GG4HT5-GHRH",1);
        
        foreach ($getDateCapteur as $findByDateCapteur) {
            $this->assertInstanceOf(DataCapteurParJour::class,$findByDateCapteur);
        }
            $this->assertEquals('15eda8fd-2175-4a29-984e-c40b5b7defe8', $getDateCapteur[0]->getId());
            $this->assertEquals('f1217a77-4a59-4b28-9e72-28c171b824a0', $getDateCapteur[1]->getId());   
    }
}

