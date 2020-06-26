<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Common\Annotations\Annotation\IgnoreAnnotation;
use App\Entity\FreshDataNotification;
use App\Form\FreshDataNotificationType;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use App\Service\ValidationErrorService;
use FOS\RestBundle\Controller\Annotations as Rest;
use App\Entity\Media;
use FOS\RestBundle\View\View;

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
class FreshDataNotificationController extends AbstractController
{
    /**
     * @apiHeader {String} Authorization Bearer token
     * @Rest\View(statusCode=200)
     * @Rest\Get("/api/fresh-data")
     */
    public function getIndex()
    {
        $em = $this->getDoctrine()->getManager();
        $freshData = $em->getRepository('App:FreshDataNotification')->findAll();
        if (!$freshData) {
          return new View([
            'success' => false,
            'code' => 404,
            'message' => 'No fresh data',
            'data' => [
              'freshDatas' => $freshData
            ]
            ]
            , Response::HTTP_NOT_FOUND);
        }

        return new View(['success' => true,
          'code' => 200,
          'message' => 'Success',
          'data' => [
            'freshDatas' => $freshData
          ]
          ]
          , Response::HTTP_OK);
    }

    /**
     * @apiHeader {String} Authorization Bearer token
     * @Rest\Get("/api/fresh-data/{id}")
     */
    public function getByUserId($id)
    {
        $em = $this->getDoctrine()->getManager();
        $freshData = $em->getRepository('App:FreshDataNotification')->getFreshData($id);
        return new View([
          'success' => true,
          'code' => 200,
          'message' => 'Success',
          'data' => [
            'freshData' => $freshData
          ]]
          , Response::HTTP_OK);
    }

    /**
     * @apiHeader {String} Authorization Bearer token
     * @Rest\View(statusCode=200)
     * @Rest\Put("/api/fresh-capteur/{id}")
     */
    public function putFreshData(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $capteur = $em->getRepository('App:FreshDataNotification')->find($id);
        $form = $this->createForm(FreshDataNotificationType::class, $capteur);
        $data = $request->request->all();
        $form->submit($data);
        if ($form->isSubmitted()) {
            $em->flush();
        }
    }

    /**
     * @api {get} /api/new-data/{freshData}
     * @Rest\View(statusCode=200)
     * @Rest\Get("/api/new-data/{freshData}")
     */
    public function getFreshData($freshData)
    {
        $em = $this->getDoctrine()->getManager();
        $capteurParJours = $em->getRepository('App:DataCapteurParJour')->getFreshData(new \DateTime($freshData));
        $data = [];
        foreach ($capteurParJours as $key => $value) {
            $data[$value->getSendingDateTime()->format('Y-m-d H:00:00')][] = $value;
        }

        $freshData = $em->getRepository('App:FreshDataNotification')->findAll();
        foreach ($freshData as $key => $value) {
            $em->remove($value);
            $em->flush();
        }


        return new View(['success' => true,
            'code' => 200,
            'message' => 'Success',
            'data' => $data]
        , Response::HTTP_OK);
    }

    /**
     * @apiHeader {String} Authorization Bearer token
     * @Rest\View(statusCode=200)
     * @Rest\Delete("/api/capteur/{id}")
     
    public function deleteCapteur(Request $request, $id)
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
    }*/
}