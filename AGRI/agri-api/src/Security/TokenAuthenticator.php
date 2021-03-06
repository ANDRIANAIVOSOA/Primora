<?php
namespace App\Security;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Guard\AbstractGuardAuthenticator;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\User\UserProviderInterface;

class TokenAuthenticator extends AbstractGuardAuthenticator
{
    
    public function getCredentials(Request $request)
    {
        if (!$token = $request->headers->get('X-API-KEY')) {

            $token = null;
        }

        return array(
            'token' => $token,
        );
    }

    public function getUser($credentials, UserProviderInterface $userProvider)
    {
        $apiKey = $credentials['token'];

        if (null === $apiKey) {
            return;
        }

        return $userProvider->loadUserByUsername($apiKey);
    }

    public function checkCredentials($credentials, UserInterface $user)
    {
        return true;
    }

    public function onAuthenticationSuccess(Request $request, TokenInterface $token, $providerKey)
    {
        return null;
    }

    public function onAuthenticationFailure(Request $request, AuthenticationException $exception)
    {

        if (!$token = $request->headers->get('X-API-KEY')){
            $data = array(
                'error' => Response::HTTP_UNAUTHORIZED,
                'message' => 'Vous n\'êtes pas autorisé à executer ce requête'
            );

            return new JsonResponse($data, Response::HTTP_UNAUTHORIZED);
        }

        $data = array(
        	'error'	=> $exception->getCode(),
            'message' => strtr($exception->getMessageKey(), $exception->getMessageData())
        );

        return new JsonResponse($data, Response::HTTP_FORBIDDEN);
    }

    public function start(Request $request, AuthenticationException $authException = null)
    {

    }

    public function supportsRememberMe()
    {
        return false;
    }

    public function supports(Request $request) {
        return $request;
    }
}