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
use App\Entity\Site;
use App\Form\SiteType;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use App\Service\ValidationErrorService;
use FOS\RestBundle\Controller\Annotations as Rest;
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
class SiteController extends AbstractController
{
    /**
     * @apiHeader {String} Authorization Bearer token
     * @Rest\View(statusCode=200)
     * @Rest\Get("/api/site/ac/{ac}")
     */
    public function getIndex($ac)
    {

        $em = $this->getDoctrine()->getManager();
        $site = $em->getRepository('App:Site')->findByAccessCode($ac);
        if (!$site) {
            return new View([
              'success' => false,
              'code' => 404,
              'message' => 'Site introuvable',
              'data' => [
                'site' => $site
              ]
              ]
              , Response::HTTP_NOT_FOUND);
        }

        return new View(['success' => true,
          'code' => 200,
          'message' => 'Success',
          'data' => [
            'site' => $site
          ]
          ]
          , Response::HTTP_OK);
    }
    
    /**
     * @api {post} /api/site/add
     * @apiHeader {String} Authorization Bearer token
     * @Rest\Post("/api/site/add")
     */
    public function postAdd(Request $request)
    {
        $site = new Site();
        $form = $this->createForm(SiteType::class, $site);
        $data = $request->request->all();
        $form->submit($data);
        $em = $this->getDoctrine()->getManager();

        if ($form->isSubmitted()) {
            $em->persist($site);
            $em->flush();
        }
        return new View(['success' => true, 'code' => 200, 
            'message' => 'la site est  enregistré avec succès', 
            'data' => $data
        ], 
        Response::HTTP_OK);
    }


    /**
     * @api {post} /api/site/put
     * @apiHeader {String} Authorization Bearer token
     * @Rest\Put("/api/site/{id}")
     */
    public function putSite(Request $request, $id){
        $em = $this->getDoctrine()->getManager();
    	$site = $em->getRepository('App:Site')->find($id);
        $form = $this->createForm(SiteType::class, $site);
        $data = $request->request->all();
        
        $form->submit($data);
        if ($form->isSubmitted()) {
        $em->persist($site);
        $em->flush();
          return new View(['success' => true,
          'code' => 200,
          'message' => 'Site modifié avec Success',
          'data' => $data]
         , Response::HTTP_OK);
}
}   

    /**
     * @apiHeader {String} Authorization Bearer token
     * @Rest\View(statusCode=200)
     * @Rest\Delete("/api/siteDeleted/{id}")
     */
    public function deleteSite($id)
      {
         $em = $this->getDoctrine()->getManager();
         $siteDeleted = $em->getRepository(Site::class)->find($id);
         
            $em->remove($siteDeleted);
            $em->flush();

        return new View(['success' => true,
            'code' => 200,
            'message' => 'Site supprimée avec success',
            'data' => $siteDeleted]
        , Response::HTTP_OK);
    }
  }