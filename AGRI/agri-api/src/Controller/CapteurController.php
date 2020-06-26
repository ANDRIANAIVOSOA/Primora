<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Common\Annotations\Annotation\IgnoreAnnotation;
use App\Entity\Capteur;
use App\Form\CapteurType;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use App\Service\ValidationErrorService;
use FOS\RestBundle\Controller\Annotations as Rest;
use App\Entity\Media;
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
class CapteurController extends AbstractController
{
    /**
     * @apiHeader {String} Authorization Bearer token
     * @Rest\View(statusCode=200)
     * @Rest\Get("/api/capteur/device/{device_id}")
     */
    public function getIndex($device_id)
    {
        $em = $this->getDoctrine()->getManager();
        $acFromDevice = $em->getRepository('App:Device')->find($device_id);
        if (!$acFromDevice) {
            return new View(['success' => false,
              'code' => 404,
              'message' => 'Device inexistant',
          ]
          , Response::HTTP_NOT_FOUND);
        }
        $accessCode = $acFromDevice->getAccessCode()->getId();
        $capteurs = $em->getRepository('App:Capteur')->findByAccessCode($accessCode);
        return new View(['success' => true,
          'code' => 200,
          'message' => 'Success',
          'data' => [
            'capteurs' => $capteurs
          ]
          ]
          , Response::HTTP_OK);
    }

    /**
     * @apiHeader {String} Authorization Bearer token
     * @Rest\Get("/api/capteur/{id}")
     */
    public function getById($id)
    {
        $em = $this->getDoctrine()->getManager();
        $capteur = $em->getRepository('App:Capteur')->find($id);

        if (!$capteur) {
          return new View(['success' => true,
            'code' => 404,
            'message' => 'Success',
            'data' => [
              'capteurs' => $capteur
            ]
          ]
          , Response::HTTP_NOT_FOUND);
        }

        return new View(['success' => true,
          'code' => 200,
          'message' => 'Success',
          'data' => $capteur]
          , Response::HTTP_OK);
    }

    /**
     * @apiHeader {String} Authorization Bearer token
     * @Rest\Get("/api/capteur/ac/{id}")
     */
    public function getByAccessCode($id)
    {
        $em = $this->getDoctrine()->getManager();
        $capteurs = $em->getRepository('App:Capteur')->findByAccessCode($id);

        if (!$capteurs) {
          return new View(['success' => true,
            'code' => 404,
            'message' => 'Success',
            'data' => [
              'capteurs' => $capteurs
            ]
          ]
          , Response::HTTP_NOT_FOUND);
        }

        return new View(['success' => true,
          'code' => 200,
          'message' => 'Success',
          'data' => ['capteurs' => $capteurs]
       ]
          , Response::HTTP_OK);
    }

    /**
     * @api {post} /api/capteur/add
     * @apiHeader {String} Authorization Bearer token
     * @Rest\Post("/api/capteur/add")
     */
    public function postAdd(Request $request)
    {
        $capteur = new Capteur();
        $form = $this->createForm(CapteurType::class, $capteur);
        $data = $request->request->all();
        $form->submit($data);
        $em = $this->getDoctrine()->getManager();

        if ($form->isSubmitted()) {
            $em->persist($capteur);
            $em->flush();
        }
        return new View([
            'success' => true,
            'code' => 200,
            'message' => 'Capteur enregistré avec succès',
            'data' => $data
        ],
        Response::HTTP_OK);
    }

    /**
     * @apiHeader {String} Authorization Bearer token
     * @Rest\View(statusCode=200)
     * @Rest\Put("/api/capteur/{id}")
     */
    public function putDataCapteur(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $capteur = $em->getRepository('App:Capteur')->find($id);
        $form = $this->createForm(CapteurType::class, $capteur);
        $data = $request->request->all();
        $form->submit($data);
        if ($form->isSubmitted()) {
            $em->flush();
        }
    }


    /**
     * @apiHeader {String} Authorization Bearer token
     * @Rest\View(statusCode=200)
     * @Rest\Delete("/api/capteur/{id}")
     */
    public function deleteCapteur($id)
    {
        $em = $this->getDoctrine()->getManager();
        $capteur = $em->getRepository('App:Capteur')->find($id);
        try {
            $em->remove($capteur);
            $em->flush();
        } catch (Exception $e) {
            throw new Exception($e->getMessage());
        }

        return new View(['success' => true,
            'code' => 200,
            'message' => 'Deleted',
            'data' => $capteur]
        , Response::HTTP_OK);
    }
}