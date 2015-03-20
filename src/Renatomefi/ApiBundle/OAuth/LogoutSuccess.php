<?php

namespace Renatomefi\ApiBundle\OAuth;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use FOS\OAuthServerBundle\Model\AccessTokenManagerInterface;
use FOS\OAuthServerBundle\Model\RefreshTokenManagerInterface;
use Symfony\Component\Security\Http\Logout\DefaultLogoutSuccessHandler;
use Symfony\Component\Security\Http\HttpUtils;

/**
 * Class LogoutSuccess
 * @package Renatomefi\ApiBundle\OAuth
 */
class LogoutSuccess extends DefaultLogoutSuccessHandler
{

    /**
     * @var AccessTokenManagerInterface
     */
    protected $accessTokenManager;
    /**
     * @var RefreshTokenManagerInterface
     */
    protected $refreshTokenManager;

    /**
     * {@inheritdoc}
     * @param AccessTokenManagerInterface $accessTokenManager
     * @param RefreshTokenManagerInterface $refreshTokenManager
     * @param HttpUtils $httpUtils
     * @param string $targetUrl
     */
    public function __construct(AccessTokenManagerInterface $accessTokenManager, RefreshTokenManagerInterface $refreshTokenManager, HttpUtils $httpUtils, $targetUrl = '/')
    {
        parent::__construct($httpUtils, $targetUrl);

        $this->accessTokenManager = $accessTokenManager;
        $this->refreshTokenManager = $refreshTokenManager;
    }

    /**
     * {@inheritdoc}
     * @param Request $request
     * @return Response
     */
    public function onLogoutSuccess(Request $request)
    {

        if ($accessToken = $this->accessTokenManager->findTokenByToken($request->get('access_token'))) {
            $this->accessTokenManager->deleteToken($accessToken);
        }

        if ($accessToken = $this->accessTokenManager->findTokenByToken($request->cookies->get('access_token'))) {
            $this->accessTokenManager->deleteToken($accessToken);
        }

        if ($accessToken = $request->server->get('HTTP_AUTHORIZATION')) {
            if ($accessTokenObj = $this->accessTokenManager->findTokenByToken(substr($accessToken, 7))) {
                $this->accessTokenManager->deleteToken($accessTokenObj);
            }
        }

        if ($refreshToken = $this->refreshTokenManager->findTokenByToken($request->cookies->get('refresh_token'))) {
            $this->refreshTokenManager->deleteToken($refreshToken);
        }

        $request->headers->remove('Authorization');
        $request->server->remove('HTTP_AUTHORIZATION');


        return Response::create();
//        return parent::onLogoutSuccess($request);

    }
}