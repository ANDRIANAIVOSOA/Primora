<?php

namespace VetementBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Routing\Annotation\Route;
use VetementBundle\Entity\Vetement;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class VetementController extends Controller
{
    /**
     * @Route("/")
     */
    public function indexAction()
    {
        $flashbag = $this->get('session')->getFlashBag();
        $vete = new vetement();
        $repository = $this->getDoctrine()
            ->getManager()
            ->getRepository('VetementBundle:Vetement');
        $vete = $repository->findAll();

        $securityContext = $this->container->get('security.authorization_checker');
            if ($securityContext->isGranted('IS_AUTHENTICATED_REMEMBERED')) {

                return $this->render('@Vetement/Vetement/vetementaccueil.html.twig',
                ['vetement'=>$vete]
            );
            } else {//message d'information
                $flashbag->add('info', "Vous devez vous connecter pour pouvoir afficher cette page.");
                            return $this->redirectToRoute('fos_user_security_login');
                        }
    }

    public function VeteprixAction(Request $request)
    {
        
        $em = $this->getDoctrine()->getManager();
    
        if($request->getMethod() == 'POST'){
            $chausse = $request->request->get('monnaie');
            $cate = new Vetement();
            $repository = $this ->getDoctrine()
                                ->getManager()
                                ->getRepository('VetementBundle:Vetement');
            $cate = $repository ->Prix($chausse);
    
            return $this->render('@Vetement/Vetement/prixvete.html.twig', array('vetement'=>$cate));
        }
    }
      
    
    public function ParlibelleAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
    
        if($request->getMethod() == 'POST'){
            $vetem = $request->request->get('cherche');
            $cate = new Vetement();
            $repository = $this ->getDoctrine()
                                ->getManager()
                                ->getRepository('VetementBundle:Vetement');
            $cate = $repository->Cherche($vetem);
   
            return $this->render('@Vetement/Vetement/cherchevetem.html.twig',array('vetement'=>$cate));
        }
    }


    public function PardecroissantAction()//Prix en ordre deCroissant
    {
        $cate = new Vetement();
        $repository = $this
        ->getDoctrine()
        ->getManager()
        ->getRepository('VetementBundle:Vetement');
      
      $cate = $repository->findBy(array(),array('prix' => 'DESC'));;
    
        return $this->render('@Vetement/Vetement/vetementaccueil.html.twig', array('vetement'=>$cate));
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
    
        return $this->redirect($this->generateUrl('panier_vete'));
    }
    
    public function panierAction(Request $request)
    {
        $session = $request->getSession();
        //$session->remove('panier');
        //die();
        if(!$session->has('panier')) $session->set('panier', array());
        $em = $this->getDoctrine()->getManager();
        $produits = $em->getRepository('VetementBundle:Vetement')->findArray(array_keys($session->get('panier')));
    
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
    return $this->redirect($this->generateUrl('panier_vete'));
}

public function PayerAction(Request $request)
{
    $session = $request->getSession();
    $panier = $session->get('panier');
    $session->remove('panier');
    //return new Response('Achat payer avec succès !!! '); 

    return $this->redirect($this->generateUrl('panier'));
}



    public function ParcroissantAction()//Prix en ordre Croissant
    {
        $cate = new Vetement();
        $repository = $this
        ->getDoctrine()
        ->getManager()
        ->getRepository('VetementBundle:Vetement');
      
      $cate = $repository->findBy(array(),array('prix' => 'ASC'));;
    
        return $this->render('@Vetement/Vetement/vetementaccueil.html.twig', array('vetement'=>$cate));
    }


}
