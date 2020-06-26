<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\User;
use App\Entity\AccessCode;
use Lexik\Bundle\JWTAuthenticationBundle\Exception\JWTDecodeFailureException;
use Lexik\Bundle\JWTAuthenticationBundle\Exception\JWTEncodeFailureException;
use Lexik\Bundle\JWTAuthenticationBundle\TokenExtractor\AuthorizationHeaderTokenExtractor;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Doctrine\Common\Annotations\Annotation\IgnoreAnnotation;
use Lexik\Bundle\JWTAuthenticationBundle\Encoder\JWTEncoderInterface;
use Symfony\Component\Security\Core\Encoder\EncoderFactory;
use Symfony\Component\Security\Core\Encoder\EncoderFactoryInterface;
use FOS\UserBundle\Form\Factory\FormFactory;
use FOS\UserBundle\Form\Factory\FactoryInterface;
use FOS\UserBundle\Model\UserManagerInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use FOS\UserBundle\Event\GetResponseUserEvent;
use FOS\UserBundle\FOSUserEvents;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use FOS\RestBundle\Controller\Annotations as Rest;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use FOS\RestBundle\View\View;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use FOS\UserBundle\Util\TokenGeneratorInterface;
use FOS\UserBundle\Mailer\Mailer;
use FOS\UserBundle\Event\FilterUserResponseEvent;

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
class LoginController extends AbstractController
{
    private $formFactory;
    private $dispatcher;
    private $userManager;
    private $mailer;
    private $factory;
    private $encoder;

    public function __construct(FactoryInterface $formFactory, EventDispatcherInterface $dispatcher, UserManagerInterface $userManager, Mailer $mailer, JWTEncoderInterface $encoder, EncoderFactoryInterface $factory)
    {
        $this->formFactory = $formFactory;
        $this->dispatcher = $dispatcher;
        $this->userManager = $userManager;
        $this->mailer = $mailer;
        $this->factory = $factory;
        $this->encoder = $encoder;
    }

    /**
     * @param Request $request
     * @return JsonResponse|Response
     *
     * @Rest\Post("/api/login")
     */
    public function createToken(Request $request)
    {

        $email = $request->request->get('email');
        $password = isset($request->request->get("plainPassword")['first']) ? $request->request->get("plainPassword")['first'] : $request->request->get("password");

        $user = $this->getDoctrine()
            ->getRepository('App:User')
            ->findOneBy(['email' => $email]);

        if (!$user) {
            $response = new JsonResponse([
                'success' => false,
                'code' => 404,
                'message' => 'Utilisateur Introuvable',
                'error' => 'Utilisateur Introuvable'
            ]);
            $response->setStatusCode(Response::HTTP_NOT_FOUND);
            return $response;
        }
        
        $encoders = $this->factory->getEncoder($user);
        $valid = $encoders->isPasswordValid($user->getPassword(), $password, $user->getSalt());
        if (!$valid) {
            $response = new JsonResponse([
                'success' => false,
                'message' => 'Mot de passe invalide',
                'error' => 'Mot de passe invalide'
                ]);
            $response->setStatusCode(Response::HTTP_FORBIDDEN);
            return $response;
        }

        $token = $this->encoder
            ->encode([
                'id'=> $user->getId(),
                'username' => $user->getUserName()
            ]);

        // Return generated token
        return new JsonResponse(['token' => $token]);
    }

    /**
     * @param Request $request
     * @return JsonResponse|Response
     *
     * @Rest\Post("/api/register")
     */
    public function registerNewUser(Request $request,ValidatorInterface $validator)
    {
        $password = $request->request->get('plainPassword')['first'];
        $isStrong = $this->passwordStrength($password);
        if (!$isStrong) {
            $response = new JsonResponse([
                'success' => false,
                'message' => 'Erreur',
                'errors' => [
                    0 => 'Le mot de passe doit contenir au minimum 8 caractères, une lettre en minuscule, une lettre en majuscule et un chiffre']
                ]);
            $response->setStatusCode(Response::HTTP_NOT_FOUND);
            return $response;
        }
        $em = $this->getDoctrine()->getManager();
        $accessCode = $em->getRepository('App:AccessCode')->find($request->request->get('accessCode'));
        $response = new Response();
        if (!$accessCode) {
            $response = new JsonResponse(['success' => false, 'message' => 'Erreur', 'errors' => [0 => 'Code d\'access invalide']]);
            $response->setStatusCode(Response::HTTP_NOT_FOUND);
            return $response;
        }
        
        
        $user = $this->userManager->createUser();
        $event = new GetResponseUserEvent($user, $request);
        $this->dispatcher->dispatch(FOSUserEvents::REGISTRATION_INITIALIZE, $event);

        if (null !== $event->getResponse()) {
            return $event->getResponse();
        }
 
        $form = $this->formFactory->createForm(array('csrf_protection' => false, 'allow_extra_fields'=> true));
        $form->setData($user);
        $this->processForm($request->request->all(), $form);

        if ($form->isValid()) {
            $event = new FormEvent($form, $request);
            $this->dispatcher->dispatch(
                          FOSUserEvents::REGISTRATION_SUCCESS, $event
                       );
 
            $this->userManager->updateUser($user);
            $this->createToken($request);
            $response = new JsonResponse(['success' => true, 'message' => 'Utilisateur crée avec succès']);
            
        } else {
            $errors = [];
            foreach ($form->getErrors(true) as $error) {
                $errors[] = $error->getMessage();
            }

            $response = new JsonResponse(['success' => false, 'message' => 'Erreur de validation', 'errors' => $errors]);
            $response->setStatusCode(Response::HTTP_NOT_FOUND);
        }
        return $this->setBaseHeaders($response);
    }

    /**
     * @param  Request $request
     * @param  FormInterface $form
     */
    private function processForm($request, FormInterface $form)
    {

        $data = $request;
        if ($data === null) {
            throw new BadRequestHttpException();
        }
        $form->submit($data);
    }

   /**
     * Set base HTTP headers.
     *
     * @param Response $response
     *
     * @return Response
     */
    private function setBaseHeaders(Response $response)
    {
        $response->headers->set('Content-Type', 'application/json');
        $response->headers->set('Access-Control-Allow-Origin', '*');
 
        return $response;
    }

    /**
     * @param Request $request
     * @return JsonResponse|Response
     *
     * @Rest\Post("/web/change-password")
     */
    public function putChangePassword(Request $request, UserPasswordEncoderInterface $encoder){
        $em = $this->getDoctrine()->getManager();
        $user = $this->getUser();
        $currentPassword = $request->request->get('current_password');
        $passwordValid = $encoder->isPasswordValid($user,$currentPassword );
        if($passwordValid){
            ($request->request->get('new_password')) ? $user->setPassword($encoder->encodePassword($user, $request->request->get('new_password'))) : '';
            if ($request->request->get('new_password') != $request->request->get('confirm_new_password')) {
                $response = new JsonResponse(['success' => false, 'message' => 'Erreur', 'errors' => 'Les deux nouveau mot de passe sont différents']);
                $response->setStatusCode(Response::HTTP_NOT_FOUND);
                return $response;
            }
            $em->persist($user);
            $em->flush();
        }else{
            $response = new JsonResponse(['success' => false, 'message' => 'Erreur', 'errors' => 'Ancien mot de passe invalide']);
            $response->setStatusCode(Response::HTTP_NOT_FOUND);
            return $response;
        }
        $response = new JsonResponse(['success' => true, 'message' => 'Votre mot de passe a été changé avec succès!']);
        $response->setStatusCode(Response::HTTP_OK);
        return $response;
    }

    /**
     * * @Rest\Post("/api/forgot-password/{email}", name="reset_password")
     */
    public function forgotPasswordRequest($email,TokenGeneratorInterface $tokenGenerator)
    {
        $user = $this->userManager->findUserByEmail($email);
        if (null === $user) {
            throw $this->createNotFoundException();
        }

        if ($user->isPasswordRequestNonExpired($this->getParameter('fos_user.resetting.token_ttl'))) {
            throw new BadRequestHttpException('Vous avez déjà démandé une initialisation de mot de passe.');
        }

        if (null === $user->getConfirmationToken()) {
            $user->setConfirmationToken($tokenGenerator->generateToken());
        }

        $this->mailer->sendResettingEmailMessage($user);
        $user->setPasswordRequestedAt(new \DateTime());
        $this->userManager->updateUser($user);

        $response = new JsonResponse(['success' => true, 'code' => 200, 'message' => 'Un email a été envoyé à l\'adresse que vous avez renseigner!']);
        $response->setStatusCode(Response::HTTP_OK);
        return $response;
    }

    /**
     * @api {get} /api/user/{$id}
     * @Rest\View(statusCode=200)
     * @Rest\Get("/web/user/{id}")
     */
    public function getCurrentUser($id)
    {
        $em = $this->getDoctrine()->getManager();
        $user = $em->getRepository('App:User')->find($id);
        return new View(['success' => true,
            'code' => 200,
            'message' => 'Success',
            'data' => $user]
        , Response::HTTP_OK);
    }

    /**
     * @param Request $request
     * @return JsonResponse|Response
     *
     * @Rest\Put("/web/user")
     */
    public function putUser(Request $request)
    {
        $user = $this->getUser();
        $event = new GetResponseUserEvent($user, $request);
        $this->dispatcher->dispatch(FOSUserEvents::PROFILE_EDIT_INITIALIZE, $event);

        if (null !== $event->getResponse()) {
            return $event->getResponse();
        }

        $form = $this->formFactory->createForm(['csrf_protection' => false]);
        $form->setData($user);
        $this->processForm($request->request->all(), $form);
        $response = [];
        if (!$form->isValid()) {
            $errors = [];
            foreach ($form->getErrors(true) as $error) {
                $errors[] = $error->getMessage();
            }

            $response = new JsonResponse(['success' => false, 'message' => 'Erreur de validation', 'errors' => $errors]);
            $response->setStatusCode(Response::HTTP_NOT_FOUND);
        }

        $event = new FormEvent($form, $request);
        $this->dispatcher->dispatch(FOSUserEvents::PROFILE_EDIT_SUCCESS, $event);

        $this->userManager->updateUser($user);

        if (null === $response) {
            $response = new JsonResponse(['success' => false, 'code' => 400, 'errors' => 'La modification n\'a pas réussi', 'message' => 'Error']);
            return $response;
        }

        $this->dispatcher->dispatch(FOSUserEvents::PROFILE_EDIT_COMPLETED, new FilterUserResponseEvent($user, $request, $response));

        $response = new JsonResponse(['success' => true, 'code' => 200, 'message' => 'Modification réussi']);
        return $response;
    }

    function passwordStrength($password) {
        $password_length = 8;
        $returnVal = True;
        if ( strlen($password) < $password_length ) {
            $returnVal = False;
        }
        if ( !preg_match("#[0-9]+#", $password) ) {
            $returnVal = False;
        }
        if ( !preg_match("#[a-z]+#", $password) ) {
            $returnVal = False;
        }
        if ( !preg_match("#[A-Z]+#", $password) ) {
            $returnVal = False;
        }
        return $returnVal;
    }
}
