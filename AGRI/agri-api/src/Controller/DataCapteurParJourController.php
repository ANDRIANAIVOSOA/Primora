<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use FOS\RestBundle\Controller\Annotations as Rest;
use App\Form\DataCapteurParJourType;
use App\Entity\DataCapteurParJour;
use App\Entity\FreshDataNotification;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use FOS\RestBundle\View\View;
use Exception;

/**
 * @IgnoreAnnotation("api")
 * @IgnoreAnnotation("apiName")
 * @IgnoreAnnotation("apiGroup")
 * @IgnoreAnnotation("apiParam")
 * @IgnoreAnnotation("apiSuccess")
 * @IgnoreAnnotation("apiError")
 * @IgnoreAnnotation("apiHeaderExample")
 * @IgnoreAnnotation("apiParamExample")
 * @IgnoreAnnotation("apiSuccessExample")
 * @IgnoreAnnotation("apiErrorExample")
 * @IgnoreAnnotation("apiHeader")
 */
class DataCapteurParJourController extends AbstractController
{
    /**
     * @api {get} /api/data-capteur/{sendingDateTimeStart}/{sendingDateTimeEnd}
     * @Rest\View(statusCode=200)
     * @Rest\Get("/api/data-capteur/{sendingDateTimeStart}/{sendingDateTimeEnd}")
     */
    public function getDataCapteurByDate($sendingDateTimeStart, $sendingDateTimeEnd)
    {
    	$em = $this->getDoctrine()->getManager();
        $capteurParJours = $em->getRepository('App:DataCapteurParJour')->findBySendingDate(new \DateTime($sendingDateTimeStart), new \DateTime($sendingDateTimeEnd));

        if (!$capteurParJours) {
            return new View(
                [
                    'success' => false,
                    'code' => 404,
                    'message' => 'Données introuvable',
                    'data' => $capteurParJours
                ]
            , Response::HTTP_NOT_FOUND);
        }

        $data = [];
        foreach ($capteurParJours as $key => $value) {
            $data[$value->getSendingDateTime()->format('Y-m-d H:i:s')][] = ['capteur' => $value->getCapteur()->getName(), 'level' => $value->getLevel()];
        }

        $result = [];
        foreach ($data as $key => $value) {
            $result[] = [$key => $value];
        }

        return new View(['success' => true,
            'code' => 200,
            'message' => 'Success',
            'data' => $result]
        , Response::HTTP_OK);
    }

    /**
     * @api {get} /api/data-capteur/{capteur}
     * @Rest\View(statusCode=200)
     * @Rest\Get("/api/data-capteur/{capteur}")
     */
    public function getDataCapteurByCapteur($capteur)
    {
        $em = $this->getDoctrine()->getManager();
        $capteurParJours = $em->getRepository('App:DataCapteurParJour')->findByCapteur($capteur);

        if (!$capteurParJours) {
            return new View([
                'success' => false,
                'code' => 404,
                'message' => 'Donnés introuvable',
                'data' => $capteurParJours
                ]
            , Response::HTTP_NOT_FOUND); 
        }

        return new View(['success' => true,
            'code' => 200,
            'message' => 'Success',
            'data' => $capteurParJours]
        , Response::HTTP_OK);
    }

    /**
     * @api {get} /api/data-capteur/{capteur}
     * @Rest\View(statusCode=200)
     * @Rest\Post("/api/data-capteur/filter")
     */
    public function getFilteredDataCapteur(Request $request)
    {
        $em = $this->getDoctrine()->getManager();

        $startDate = $request->request->get('startDate');
        $endDate = $request->request->get('endDate');
        $capteur = $request->request->get('capteur');
        $site = $request->request->get('site');
        $yearFilter = $request->request->get('yearFilter');
        $em = $this->getDoctrine()->getManager();

        $email = $request->request->get('email');
        $currentUser = $em->getRepository('App:User')->findByEmail($email);
        if (!$currentUser) {
            return new View(['success' => false,
              'code' => 404,
              'message' => 'User inexistant',
            ]
          , Response::HTTP_NOT_FOUND);
        }

        $accessCode = $currentUser[0]->getAccessCode()->getId();
        $devices = $em->getRepository('App:Device')->findByAccessCode($accessCode);

        if (!$devices) {
            return new View(
                [
                    'success' => false,
                    'code' => 404,
                    'message' => 'Devices introuvable',
                    'data' => $devices
                ]
            , Response::HTTP_NOT_FOUND);
        }

        $deviceList = [];
        foreach ($devices as $key => $value) {
            $deviceList[] = $value->getId();
        }

        $capteurParJours = [];
        foreach ($deviceList as $deviceId) {
            $capteurParJours[] = $this->filterCapteurParJour($startDate, $endDate, $capteur, $deviceId, $site);
        }

        $data = [];
        if ($yearFilter) {
            foreach ($deviceList as $deviceId) {
                $capteurParJours[] = $this->compareGraph($startDate, $endDate, $capteur, $deviceId, $site);
            }

            foreach ($capteurParJours as $key => $value) {
                foreach ($value as $k => $v) {
                    $data[$v->getSendingDateTime()->format('d/m/Y H:i')][] = ['capteur' => $v->getCapteur()->getName(), 'level' => $v->getLevel(), 'sendingDateTime' => $v->getSendingDateTime(), 'yearMonth' =>  $v->getCapteur()->getName() .' - '. $v->getSendingDateTime()->format('M Y')];
                }
            }
        } else {
            foreach ($capteurParJours as $key => $value) {
                foreach ($value as $k => $v) {
                    $data[$v->getSendingDateTime()->format('Y-m-d H:i:00')][] = ['capteur' => $v->getCapteur()->getName(), 'level' => $v->getLevel()];
                }
            }
        }

        $result = [];
        foreach ($data as $key => $value) {
            $result[] = [$key => $value];
        }

        return new View(['success' => true,
            'code' => 200,
            'message' => 'Success',
            'data' => $result]
        , Response::HTTP_OK);
    }

    public function compareGraph($startDate = null, $endDate = null, $capteur, $deviceId = null, $site = null) {
        $em = $this->getDoctrine()->getManager();
        $capteurParJours = [];
        $capteurParJours = $em->getRepository('App:DataCapteurParJour')->compareGraph($startDate, $endDate, $capteur, $site, $deviceId);
        return $capteurParJours;
    }

    public function filterCapteurParJour($startDate = null, $endDate = null, $capteur = null, $deviceId = null, $site = null) {
        $em = $this->getDoctrine()->getManager();
        $capteurParJours = [];
        if ($startDate && $endDate && $capteur && $site) {
            $capteurParJours = $em->getRepository('App:DataCapteurParJour')->getByStartDateEndDateCapteurSite($startDate, $endDate, $capteur, $site, $deviceId);
        } elseif ($startDate && $endDate && $capteur) {
            $capteurParJours = $em->getRepository('App:DataCapteurParJour')->getByStartDateEndDateCapteur($startDate, $endDate, $capteur, $deviceId);
        } elseif ($startDate && $endDate && $site) {
            $capteurParJours = $em->getRepository('App:DataCapteurParJour')->getByStartDateEndDateSite($startDate, $endDate, $site, $deviceId);
        }
         elseif ($startDate && $endDate) {
            $capteurParJours = $em->getRepository('App:DataCapteurParJour')->getByStartDateEndDate($startDate, $endDate, $deviceId);
        } elseif ($startDate && $capteur) {
            $capteurParJours = $em->getRepository('App:DataCapteurParJour')->getByStartDateCapteur($startDate, $capteur, $deviceId);
        } elseif ($startDate) {
            $capteurParJours = $em->getRepository('App:DataCapteurParJour')->getByStartDate($startDate, $deviceId);
        } elseif ($capteur) {
            $capteurParJours = $em->getRepository('App:DataCapteurParJour')->findByCapteur($capteur, $deviceId);
        } elseif ($site && $capteur && $startDate && $endDate){
            $capteurParJours = $em->getRepository('App:DataCapteurParJour')->getBySiteCapteurStartDateEndDate($site, $capteur, $startDate, $endDate, $deviceId);
        } elseif ($site && $capteur) {
            $capteurParJours = $em->getRepository('App:DataCapteurParJour')->getBySiteCapteur($site, $capteur, $deviceId);
        } else {
            $capteurParJours = $em->getRepository('App:DataCapteurParJour')->findAll();
        }
        return $capteurParJours;
    }


    /**
     * @api {get} /api/data-capteur-bycapteur
     * @Rest\View(statusCode=200)
     * @Rest\Get("/api/data-capteur-bycapteur")
     */
    public function getGroupedDataCapteur(Request $request)
    {
        $em = $this->getDoctrine()->getManager();

        $email = $request->query->get('email');
        $currentUser = $em->getRepository('App:User')->findByEmail($email);
        if (!$currentUser) {
            return new View(['success' => false,
              'code' => 404,
              'message' => 'User inexistant',
            ]
          , Response::HTTP_NOT_FOUND);
        }

        $accessCode = $currentUser[0]->getAccessCode()->getId();
        $devices = $em->getRepository('App:Device')->findByAccessCode($accessCode);

        if (!$devices) {
            return new View(
                [
                    'success' => false,
                    'code' => 404,
                    'message' => 'Devices introuvable',
                    'data' => $devices
                ]
            , Response::HTTP_NOT_FOUND);
        }

        $deviceList = [];
        foreach ($devices as $key => $value) {
            $deviceList[] = $value->getId();
        }

        $site = $request->query->get('site');
        $capteurParJours = [];
        foreach ($deviceList as $value) {
            $capteurParJours[] = $em->getRepository('App:DataCapteurParJour')->groupByDate($value, $site);
        }

        $data = [];
        foreach ($capteurParJours as $key => $value) {
            foreach ($value as $k => $v) {
                $data[$v->getSendingDateTime()->format('d/m/Y H:i')][] = ['capteur' => $v->getCapteur()->getName(), 'level' => $v->getLevel()];
            }
        }

        $result = [];
        foreach ($data as $key => $value) {
            $result[] = [$key => $value];
        }
        return new View(['success' => true,
            'code' => 200,
            'message' => 'Success',
            'data' => $result]
        , Response::HTTP_OK);
    }

    /**
     * @api {get} /api/data-capteur
     * @apiHeader {String} Authorization Bearer token
     * @Rest\View(statusCode=200)
     * @Rest\Get("/api/data-capteur")
     */
    public function getAllDataCapteur(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $email = $request->query->get('email');
        $currentUser = $em->getRepository('App:User')->findByEmail($email);
        if (!$currentUser) {
            return new View(['success' => false,
              'code' => 404,
              'message' => 'User inexistant',
            ]
          , Response::HTTP_NOT_FOUND);
        }
        
        $accessCode = $currentUser[0]->getAccessCode()->getId();
        $devices = $em->getRepository('App:Device')->findByAccessCode($accessCode);

        if (!$devices) {
            return new View(
                [
                    'success' => false,
                    'code' => 404,
                    'message' => 'Devices introuvable',
                    'data' => $devices
                ]
            , Response::HTTP_NOT_FOUND);
        }

        $deviceList = [];
        foreach ($devices as $key => $value) {
            $deviceList[] = $value->getId();
        }

        $site = $request->query->get('site');
        $capteurParJours = [];
        foreach ($deviceList as $value) {
            $capteurParJours[] = $em->getRepository('App:DataCapteurParJour')->groupByDate($value, $site);
        }
        
        $data = [];
        foreach ($capteurParJours as $key => $value) {
            foreach ($value as $k => $v) {
                $data[$v->getSendingDateTime()->format('Y-m-d H:i:00')][] = $v;
            }
        }

        return new View(['success' => true,
            'code' => 200,
            'message' => 'Success',
            'data' => $data
        ]
        , Response::HTTP_OK);
    }

    /**
     * @apiHeader {String} Authorization Bearer token
     * @Rest\View(statusCode=200)
     * @Rest\Put("/api/data-capteur")
     */
    public function deleteDataCapteur(Request $request)
    { 
        $sendingsDateTime = $request->request->get('sendingDateTime');
        $site = $request->request->get('site');
        $em = $this->getDoctrine()->getManager();
        foreach ($sendingsDateTime as $k => $sd) {
            $dt = (new \DateTime($sd))->format('Y-m-d');
            $dataCapteurParJour = $em->getRepository('App:DataCapteurParJour')->getBySendingDateTime($dt, $site);
            try {
                foreach ($dataCapteurParJour as $key => $value) {
                    if ($value->getIsDeleted() == 0) {
                        $value->setIsDeleted(1);
                    } else {
                        $value->setIsDeleted(0);
                    }
                }
                $em->flush();
            } catch (Exception $e) {
                throw new Exception($e->getMessage());
            }
        }
        return new View(['success' => true,
            'code' => 200,
            'message' => 'Success',
            'data' => []
            ]
        , Response::HTTP_OK);
    }

    /**
     * @api {post} /api/data-capteur/add
     * @apiHeader {String} Authorization Bearer token
     * @Rest\View()
     * @Rest\Post("/api/data-capteur/add")
     */
    public function postAdd(Request $request)
    {
        $data = [];
        $freshData = new \DateTime();
        $freshData->format('Y-m-d H:i:s');
        foreach ($request->request->all()['data_capteur_par_jour'] as $key => $value) {
            $data[] = [
                'capteur' => $value['capteur'],
                'deviceId' => $value['deviceId'],
                'level' => $value['level'],
                'sendingDateTime' => $value['sendingDateTime']
            ];
        }

        $capteurParJour = new DataCapteurParJour();
        $form = $this->createForm(DataCapteurParJourType::class, $capteurParJour);
        $em = $this->getDoctrine()->getManager();

        $freshDataNotif = new FreshDataNotification();
        $freshDataNotif->setFreshData($freshData);
        $em->persist($freshDataNotif);
        $em->flush();

        foreach ($data as $key => $value) {
            $capteurParJour = new DataCapteurParJour();
            try {
                $device = $em->getRepository('App:Device')->find($data[$key]['deviceId']);
                $site = $device->getSite()->getId();
                $capteurParJour->setDeviceId($device);
                $capteur = $em->getRepository('App:Capteur')->find($data[$key]['capteur']);
                $capteurParJour->setCapteur($capteur);
                $capteurParJour->setSendingDateTime(new \DateTime($data[$key]['sendingDateTime']));
                $capteurParJour->setLevel($data[$key]['level']);
                $capteurParJour->setSiteId($site);
                $capteurParJour->setFreshData($freshData);
                $em->persist($capteurParJour);
                $em->flush();
            } catch (Exception $e) {
                throw new Exception($form);
            }
        }

        return new View(['success' => true,
            'code' => 200,
            'message' => 'Success',
            'data' => $request->request->all()]
        , Response::HTTP_OK);
	}
}