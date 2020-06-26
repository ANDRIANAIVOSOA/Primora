<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Common\Annotations\Annotation\IgnoreAnnotation;
use App\Entity\AccessCode;
use App\Form\AccessCodeType;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use App\Service\ValidationErrorService;
use FOS\RestBundle\Controller\Annotations as Rest;
use App\Entity\Media;
use FOS\RestBundle\View\View;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use App\Controller\DefaultController;

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
class AccessCodeController extends DefaultController
{
    /**
     * @api {post} /api/access-code/add
     * @apiHeader {String} Authorization Bearer token
     * @Rest\Post("/api/access-code/add")
     */
    public function postAdd(Request $request)
    {
        $accessCode = new AccessCode();
        $form = $this->createForm(AccessCodeType::class, $accessCode);
        $data = $request->request->all();
        $form->submit($data);
        $em = $this->getDoctrine()->getManager();

        if ($form->isSubmitted()) {
            $em->persist($accessCode);
            $em->flush();
        }
        return new View(['success' => true, 'code' => 200, 'message' => 'Code d\'acces enregistré avec succès', 'data' => $data], Response::HTTP_OK);
    }


    /**
     * @api {post} /api/access-code/put
     * @apiHeader {String} Authorization Bearer token
     * @Rest\Post("/api/access-code/put/{id}")
     */
    public function put(Request $request,$id){
        $em = $this->getDoctrine()->getManager();
    	$oneAccessCode = $em->getRepository(AccessCode::class)->find($id);
        $form = $this->createForm(AccessCodeType::class, $oneAccessCode);
        $data = $request->request->all();
        
       $form->submit($data);
        if ($form->isSubmitted()) {
            $em->persist($oneAccessCode);
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
     * @Rest\Get("/api/access-code/list")
     */
    public function getAccessCode(EntityManagerInterface $em)
    {

        // $em = $this->getDoctrine()->getManager();
        $accessCode = $em->getRepository(AccessCode::class)->findAll();
        return new View(['success' => true,
          'code' => 200,
          'message' => 'Success',
          'data' => [
            'accesscode' => $accessCode
          ]
          ]
          , Response::HTTP_OK);
    }

    /**
     * @apiHeader {String} Authorization Bearer token
     * @Rest\View(statusCode=200)
     * @Rest\Delete("/api/access-code/{id}")
     */
    public function deleteAccessCode($id)
    {
        $em = $this->getDoctrine()->getManager();
        $accessCode = $em->getRepository('App:AccessCode')->find($id);
        try {
            $em->remove($accessCode);
            $em->flush();
        } catch (Exception $e) {
            throw new Exception($e->getMessage());
        }
        return new View(['success' => true,
            'code' => 200,
            'message' => 'Supprimé avec success']
        , Response::HTTP_OK);
    }
}