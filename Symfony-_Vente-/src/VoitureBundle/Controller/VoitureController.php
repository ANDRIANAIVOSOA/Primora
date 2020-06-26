<?php

namespace VoitureBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use VoitureBundle\Entity\Voiture;

class VoitureController extends Controller
{
    /**
     * @Route("/")
     */
    public function indexAction()
    {
        $flashbag = $this->get('session')->getFlashBag();
        $voit = new Voiture();
        $repository = $this->getDoctrine()
            ->getManager()
            ->getRepository('VoitureBundle:Voiture');
        $voit = $repository->findAll();
        
        $securityContext = $this->container->get('security.authorization_checker');
            if ($securityContext->isGranted('IS_AUTHENTICATED_REMEMBERED')) {

                return $this->render('@Voiture/Voiture/voitacceuil.html.twig',
                ['voiture'=>$voit]
               
            );
            } else {//message d'information
                $flashbag->add('info', "Vous devez vous connecter pour pouvoir afficher cette page.");
                            return $this->redirectToRoute('fos_user_security_login');
            }
    }


    public function ParprixAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
    
        if($request->getMethod() == 'POST'){
            $voiture = $request->request->get('prix');
            $cate = new Voiture();
            $repository = $this ->getDoctrine()
                                ->getManager()
                                ->getRepository('VoitureBundle:Voiture');
            $cate = $repository->Prix($voiture);
    
            return $this->render('@Voiture/Voiture/prix.html.twig', array('voiture'=>$cate));
        }
    }

    public function ParlibelleAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
    
        if($request->getMethod() == 'POST'){
            $voiture = $request->request->get('cherche');
            $cate = new Voiture();
            $repository = $this ->getDoctrine()
                                ->getManager()
                                ->getRepository('VoitureBundle:Voiture');
            $cate = $repository->Recherche($voiture);
    
            return $this->render('@Voiture/Voiture/cherche.html.twig', array('voiture'=>$cate));
        }
    }

    public function PardecroissantAction()//Prix en ordre Croissant
    {
        $cate = new Voiture();
        $repository = $this
        ->getDoctrine()
        ->getManager()
        ->getRepository('VoitureBundle:Voiture');
      
      $cate = $repository->findBy(array(),array('prix' => 'DESC'));;
     
        return $this->render('@Voiture/Voiture/voitacceuil.html.twig', array('voiture'=>$cate));
    }

    public function ParcroissantAction()//Prix en ordre Croissant
    {
        $cate = new Voiture();
        $repository = $this
        ->getDoctrine()
        ->getManager()
        ->getRepository('VoitureBundle:Voiture');
      
      $cate = $repository->findBy(array(),array('prix' => 'ASC'));;
     
      return $this->render('@Voiture/Voiture/voitacceuil.html.twig', array('voiture'=>$cate));
    }


    public function ajouterAction($id, Request $request)
    {
        $session = $request->getSession();
        if(!$session->has('panier')) 
        $session->set('panier', array());
        $panier = $session->get('panier');
    
        if(array_key_exists($id, $panier)){
            if($request->query->get('qte') != null) 
            $panier[$id] = $request->query->get('qte');
        }
        else{
            if($request->query->get('qte') != null)
            $panier[$id] = $request->query->get('qte');
            else
            $panier[$id] = 1;
        }
        $session->set('panier', $panier);
    
        return $this->redirect($this->generateUrl('panier_voit'));
    }
    
    public function panierAction(Request $request)
    {
        $session = $request->getSession();
        //$session->remove('panier');
        //die();
        if(!$session->has('panier')) $session->set('panier', array());
        $em = $this->getDoctrine()->getManager();
        $produits = $em->getRepository('VoitureBundle:Voiture')->findArray(array_keys($session->get('panier')));
    
        return $this->render('@AcmeUser/Default/panier.html.twig', array('produit'=>$produits,
                                                                            'panier'=>$session->get('panier')));
    }

    public function supprimerAction(Request $request, $id)
{
    $session = $request->getSession();
    $panier = $session->get('panier');

    if(array_key_exists($id, $panier)){
        unset($panier[$id]);
        $session->set('panier', $panier);
    }
    return $this->redirect($this->generateUrl('panier_voit'));
}

public function PayerAction(Request $request)
{
    $session = $request->getSession();
    $panier = $session->get('panier');
    $session->remove('panier');
    //return new Response('Achat payer avec succès !!! '); 

    return $this->redirect($this->generateUrl('panier'));
}

}
