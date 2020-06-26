<?php

namespace CommentaireBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use CommentaireBundle\Entity\Message;
use CommentaireBundle\Form\MessageType;
use CommentaireBundle\Form\MessageReponseType;

class CommentaireController extends Controller
{
    /**
     * @Route("")
     */
    public function indexAction()
    {
        $flashbag = $this->get('session')->getFlashBag();
        $em = $this->getDoctrine()->getManager();
        $com = $em->getRepository('CommentaireBundle:Message')->findAll();

        $securityContext = $this->container->get('security.authorization_checker');
        if ($securityContext->isGranted('IS_AUTHENTICATED_REMEMBERED')) {
            return $this->render('@Commentaire/Message/liste.html.twig',
            [
                'mess'=>$com
            ]
            );         
        } else {
            $flashbag->add('info', "Vous devez vous connecter pour pouvoir afficher cette page.");
            return $this->redirectToRoute('fos_user_security_login');
        }
    }

    public function addAction(Request $request)
    {

        $em = $this->getDoctrine()->getManager();
        $com = new Message();
        $form = $this->createForm(MessageType::class, $com);
        $form->handleRequest($request);
       /* if (isset($_POST['message'])) { AJAX 
            $com->setMessage($_POST['message']);
            $em->persist($com);
            $em->flush();
            return new JsonResponse($_POST);
            die();
        }*/
        if ($form->isSubmitted() && $form->isValid()) {

            $em->persist($com);
            $em->flush();
            return $this->redirectToRoute('message');
        }
        return $this->render('@Commentaire\Message\index.html.twig', [
            'formComment' => $form->createView()
        ]);
    }

    public function supAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $comment = $em->getRepository('CommentaireBundle:Message')->find($id);
        if ($request->getMethod('GET')) {
            $em->remove($comment);
            $em->flush();

            return $this->redirectToRoute('message');
        }
    }

    public function ajoutAction(Request $request, $id){
        $meme = $this->getDoctrine()->getManager();
        $one = $meme->getRepository('CommentaireBundle:Message')->find($id);

        $em = $this->getDoctrine()->getManager();
        $com = new Message();
        $form = $this->createForm(MessageReponseType::class, $com);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($com);
            $em->flush();
            return $this->redirectToRoute('message');
        }
        return $this->render('@Commentaire\Message\repond.html.twig',[
            'repondre'=>$one,
            'formComment' => $form->createView()
        ]);
    }
}
