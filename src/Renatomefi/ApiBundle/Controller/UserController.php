<?php

namespace Renatomefi\ApiBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Security\Core\Authentication\Token\AnonymousToken;

class UserController extends Controller
{

    public function logoutAction()
    {
        $this->get('security.token_storage')->getToken()->eraseCredentials();
        return $this->infoAction();
    }

    public function infoAction()
    {
        $auth = $this->get('security.authorization_checker');
        $secToken = $this->get('security.token_storage')->getToken();
        $oauthToken = $this->get('fos_oauth_server.access_token_manager.default');

        $user = null;
        $client = null;

        if (!$secToken instanceof AnonymousToken) {
            if ($accessToken = $oauthToken->findTokenByToken($secToken->getToken())) {
                $c = $accessToken->getClient();
                $client = [
                    'name' => $c->getName(),
                    'id' => $c->getPublicId()
                ];
            }
        }

        if ($u = $this->getUser()) {
            $user = [
                'username' => $u->getUsername(),
                'email' => $u->getEmail(),
                'roles' => $u->getRoles(),
            ];
        }

        $info = [
            'authenticated' => $secToken->isAuthenticated(),
            'authenticated_fully' => $auth->isGranted('IS_AUTHENTICATED_FULLY'),
            'authenticated_anonymously' => $auth->isGranted('IS_AUTHENTICATED_ANONYMOUSLY'),
            'role_user' => $auth->isGranted('ROLE_USER'),
            'role_admin' => $auth->isGranted('ROLE_ADMIN'),
            'role_anonymous' => $auth->isGranted('IS_AUTHENTICATED_ANONYMOUSLY'),
            'client' => $client,
            'user' => $user
        ];

        return new JsonResponse(
            json_decode(json_encode($info))
        );
    }
}
