<?php

namespace ChaussureBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use ChaussureBundle\Entity\Chaussure;


class ChaussureController extends Controller
{
    /**
     * @Route("/")
     */
    public function indexAction()
    {
        $flashbag = $this->get('session')->getFlashBag();
        $chau = new Chaussure();
        $tous = $this->getDoctrine()
                        ->getManager()
                        ->getRepository('ChaussureBundle:Chaussure');
                        $chau = $tous->findAll();

         $securityContext = $this->container->get('security.authorization_checker');
            if ($securityContext->isGranted('IS_AUTHENTICATED_REMEMBERED')) {
                    return $this->render('@Chaussure/Chaussure/chaussacceuil.html.twig', [
                         'tous' =>$chau
                            ]);
            } else {//message d'information
                $flashbag->add('info', "Vous devez vous connecter pour pouvoir afficher cette page.");
                            return $this->redirectToRoute('fos_user_security_login');
            }
       
    }


    public function ParcroissantAction()//Prix en ordre Croissant
    {
        $cate = new Chaussure();
        $repository = $this
        ->getDoctrine()
        ->getManager()
        ->getRepository('ChaussureBundle:Chaussure');
      
      $cate = $repository->findBy(array(),array('prix' => 'ASC'));;
     
        return $this->render('@Chaussure/Chaussure/chaussacceuil.html.twig', array('tous'=>$cate));
    }


    public function PardecroissantAction()//Prix en ordre Croissant
    {
        $cate = new Chaussure();
        $repository = $this
        ->getDoctrine()
        ->getManager()
        ->getRepository('ChaussureBundle:Chaussure');
      
      $cate = $repository->findBy(array(),array('prix' => 'DESC'));;
     
        return $this->render('@Chaussure/Chaussure/chaussacceuil.html.twig', array('tous'=>$cate));
    }


    public function ParlibelleAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
    
        if($request->getMethod() == 'POST'){
            $chausse = $request->request->get('search');
            $cate = new Chaussure();
            $repository = $this ->getDoctrine()
                                ->getManager()
                                ->getRepository('ChaussureBundle:Chaussure');
            $cate = $repository->Recherche($chausse);
    
            return $this->render('@Chaussure/Chaussure/recherchechau.html.twig', array('produit'=>$cate));
        }
    }

    public function ParprixAction(Request $request)
    {
        
        $em = $this->getDoctrine()->getManager();
    
        if($request->getMethod() == 'POST'){
            $chausse = $request->request->get('argent');
            $cate = new Chaussure();
            $repository = $this ->getDoctrine()
                                ->getManager()
                                ->getRepository('ChaussureBundle:Chaussure');
            $cate = $repository ->Prix($chausse);
    
            return $this->render('@Chaussure/Chaussure/prixchau.html.twig', array('produit'=>$cate));
        }
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
    
        return $this->redirect($this->generateUrl('panier'));
    }
    
    public function panierAction(Request $request)
    {
        $session = $request->getSession();
        if(!$session->has('panier')) $session->set('panier', array());
        $em = $this->getDoctrine()->getManager();
        $produits = $em->getRepository('ChaussureBundle:Chaussure')->findArray(array_keys($session->get('panier')));

    //ici le mail de validation
        $message =  \Swift_Message::newInstance()
                    ->setSubject('Validation de votre commande')
                    ->setFrom(array('Gehjaniaina@gmail.com' => 'Gehja'))//mandefa
                    ->setTo(array('Gehjaniaina@gmail.com' => 'Erick'))//andefasana
                    ->setCharset('utf-8')
                    ->setContentType('text/html')
                    ->setBody($this->renderView('@Chaussure/Chaussure/validation.html.twig'));
        $this->get('mailer')->send($message);
    
        return $this->render('@AcmeUser/Default/panier.html.twig', array('produit'=>$produits,
                                                                            'panier'=>$session->get('panier')));
    }


    public function pdfAction(Request $request)
    {
        $session = $request->getSession();
        if(!$session->has('panier')) $session->set('panier', array());
        $em = $this->getDoctrine()->getManager();
        $produits = $em->getRepository('ChaussureBundle:Chaussure')->findArray(array_keys($session->get('panier')));
    
        $html =  $this->renderView('@AcmeUser/Default/impression.html.twig', array('produit'=>$produits,
                                                                            'panier'=>$session->get('panier')));

        return new Response(
            $this->get('knp_snappy.pdf')->getOutputFromHtml($html),
            200,
            array(
                'Content-Type'          => 'application/pdf',
                'Content-Disposition'   => 'attachment; filename="facture.pdf"',
                'orientation' => 'landscape',
                'encoding' => 'UTF-8',
                'images' => true,

            )
        );            
    }


    public function supprimerAction(Request $request, $id)
{
    $session = $request->getSession();
    $panier = $session->get('panier');

    if(array_key_exists($id, $panier)){
        unset($panier[$id]);
        $session->set('panier', $panier);
    }
    return $this->redirect($this->generateUrl('panier'));
}

public function PayerAction(Request $request)
{
    $session = $request->getSession();
    $panier = $session->get('panier');
    $session->remove('panier');
    //return new Response('Achat  payer avec succès !!! '); 

    return $this->redirect($this->generateUrl('panier'));
}

}
