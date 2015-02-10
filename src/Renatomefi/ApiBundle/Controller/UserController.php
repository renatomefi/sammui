<?php

namespace Renatomefi\ApiBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;

class UserController extends Controller
{
    public function indexAction($name)
    {
        return $this->render('ApiBundle:Default:index.html.twig', array('name' => $name));
    }

    public function infoAction()
    {
        $auth = $this->get('security.authorization_checker');
        $token = $this->get('security.token_storage')->getToken();
        $user = null;

        if ($u = $this->getUser()) {
            $user = [
                'username' => $u->getUsername(),
                'email' => $u->getEmail(),
                'roles' => $u->getRoles(),
            ];
        }

        $info = [
            'autenticated' => $token->isAuthenticated(),
            'autenticated_fully' => $auth->isGranted('IS_AUTHENTICATED_FULLY'),
            'autenticated_anonymously' => $auth->isGranted('IS_AUTHENTICATED_ANONYMOUSLY'),
            'role_user' => $auth->isGranted('ROLE_USER'),
            'role_admin' => $auth->isGranted('ROLE_ADMIN'),
            'user' => $user
        ];
        return new JsonResponse(
            json_decode(json_encode($info))
        );
    }
}
