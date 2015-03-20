<?php

namespace Renatomefi\TestBundle\Rest;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class AssertFirewall
 * @package Renatomefi\TestBundle\Rest
 */
class AssertFirewall
{

    /**
     * Default request and possible responses used for testing firewalls
     * @var array
     */
    protected $defaultRequestAndResponse = [
        Request::METHOD_POST => Response::HTTP_UNAUTHORIZED,
        Request::METHOD_PUT => Response::HTTP_METHOD_NOT_ALLOWED,
        Request::METHOD_PATCH => Response::HTTP_METHOD_NOT_ALLOWED,
        Request::METHOD_DELETE => Response::HTTP_UNAUTHORIZED,
        Request::METHOD_GET => [Response::HTTP_NOT_FOUND, Response::HTTP_OK]
    ];

    /**
     * @param array $methods
     */
    public function __construct($methods = [])
    {
        $this->defaultRequestAndResponse = array_merge($this->defaultRequestAndResponse, $methods);
    }

    /**
     * @param $request
     * @param $response
     * @return $this
     */
    public function setRequest($request, $response)
    {
        $this->defaultRequestAndResponse[$request] = $response;

        return $this;
    }

    /**
     * @param $request
     * @return null
     */
    public function getRequest($request)
    {
        return ($this->defaultRequestAndResponse[$request]) ? $this->defaultRequestAndResponse[$request] : null;
    }

    /**
     * @return array
     */
    public function getAsDataProvider()
    {
        $dataProvider = [];

        foreach($this->defaultRequestAndResponse as $request => $response) {
            $dataProvider[] = [$request, $response];
        }

        return $dataProvider;
    }
}