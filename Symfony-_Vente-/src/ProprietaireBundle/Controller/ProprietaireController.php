<?php

namespace ProprietaireBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Routing\Annotation\Route;
use ProprietaireBundle\Entity\Proprietaire;
use ProprietaireBundle\Form\ProprietaireType;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;

class ProprietaireController extends Controller
{
    /**
     * @Route("/")
     */
    public function indexAction()
    {
        return $this->render('@AcmeUser/Default/index.html.twig');
    }

    /**
     * @Route("")
     */
    public function tousAction()
    {
        $em = $this->getDoctrine()->getManager();
        $pro = $em->getRepository('ProprietaireBundle:Proprietaire')->findAll();
        return $this->render('@Proprietaire/Proprietaire/prop.html.twig',[
            'proprie'=>$pro
        ]);
    }


    public function editAction(Request $request,$id){
        $em = $this->getDoctrine()->getManager();
        $req = $em->getRepository('ProprietaireBundle:Proprietaire')->find(1);

        $form = $this->createForm(ProprietaireType::class,$req);
        $form->handleRequest($request);
        if($request->getMethod('POST') && $form->isSubmitted()){
            $em->flush();
            return $this->redirectToRoute('tous_prop');
        }
        
        return $this->render('@Proprietaire/Proprietaire/edit.html.twig',
    ['editform'=>$form->createView()]
    );
    }
}
