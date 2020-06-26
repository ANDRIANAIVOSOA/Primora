<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Common\Annotations\Annotation\IgnoreAnnotation;
use App\Entity\Device;
use App\Form\DeviceType;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use App\Service\ValidationErrorService;
use FOS\RestBundle\Controller\Annotations as Rest;
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
class DeviceController extends AbstractController
{
    /**
     * @apiHeader {String} Authorization Bearer token
     * @Rest\View(statusCode=200)
     * @Rest\Get("/web/device/{ac}")
     */
    public function getIndex($ac)
    {

        $em = $this->getDoctrine()->getManager();
        $accessCode = $em->getRepository('App:AccessCode')->find($ac);
        if (!$accessCode) {
          return new View(['success' => true,
            'code' => 404,
            'message' => 'Code d\'access Introuvable',
            'data' => [
              'accessCode' => $accessCode
            ]
            ]
            , Response::HTTP_OK);
        }

        $device = $em->getRepository('App:Device')->getByAccessCode($accessCode);

        return new View(['success' => true,
          'code' => 200,
          'message' => 'Success',
          'data' => [
            'devices' => $device
          ]
          ]
          , Response::HTTP_OK);
    }

    /**
     * @apiHeader {String} Authorization Bearer token
     * @Rest\Get("/api/device/{id}")
     */
    public function getById($id)
    {
        $em = $this->getDoctrine()->getManager();
        $device = $em->getRepository('App:Device')->find($id);
        if (!$device) {
            
          return new View([
            'success' => false,
            'code' => 404,
            'message' => 'Device Introuvable',
            'data' => [
              'device' => $device
            ]]
            , Response::HTTP_NOT_FOUND);
        }

        return new View(['success' => true,
          'code' => 200,
          'message' => 'Success',
          'data' => [
          	'device' => $device
          ]]
          , Response::HTTP_OK);
    }

    /**
     * @api {post} /api/device/add
     * @apiHeader {String} Authorization Bearer token
     * @Rest\Post("/api/device/add")
     */
    public function postAdd(Request $request)
    {

        $device = new Device();
        $form = $this->createForm(DeviceType::class, $device);
        $data = $request->request->all();
        $form->submit($data);
        $em = $this->getDoctrine()->getManager();
        
        if ($form->isSubmitted()) {
            $accessCode = $em->getRepository('App:AccessCode')->find($request->request->get('accessCode'));
            if (!$accessCode) {
                return new View(
                    [
                    'success' => false,
                    'code' => 404,
                    'message' => 'Code d\'access inexistant',
                    ], Response::HTTP_NOT_FOUND);
            }
            $em->persist($device);
            $em->flush();
            $capteur = $em->getRepository('App:Capteur')->findByAccessCode($request->request->get('accessCode'));
            return new View(
                [
                'success' => true,
                'code' => 200,
                'message' => 'Device enregistré avec succès',
                'data' => ['capteurs' => $capteur]
                ], Response::HTTP_OK);
        }

    }

    /**
     * @apiHeader {String} Authorization Bearer token
     * @Rest\View(statusCode=200)
     * @Rest\Put("/api/device/{id}")
     */
    public function putDevice(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $device = $em->getRepository('App:Device')->find($id);
        $form = $this->createForm(DeviceType::class, $device);
        $data = $request->request->all();
        $form->submit($data);
        if ($form->isSubmitted()) {
            $em->flush();
            return new View(['success' => true,
                'code' => 200,
                'message' => 'Success',
                'data' => $data
            ]
            , Response::HTTP_OK);
        }
    }

    /**
     * @apiHeader {String} Authorization Bearer token
     * @Rest\View(statusCode=200)
     * @Rest\Delete("/api/device/{id}")
     */
    public function deleteDevice($id)
    {
        $em = $this->getDoctrine()->getManager();
        $device = $em->getRepository('App:Device')->find($id);
        try {
            $em->remove($device);
            $em->flush();
        } catch (Exception $e) {
            throw new Exception($e->getMessage());
              
        }

        return new View(['success' => true,
            'code' => 200,
            'message' => 'Deleted',
            'data' => $device]
        , Response::HTTP_OK);
    }
}
