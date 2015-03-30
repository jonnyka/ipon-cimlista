<?php

namespace UserBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Cookie;
use Symfony\Component\HttpFoundation\Response;

class UserController extends Controller
{
    /**
     * Displays a simple form to login.
     *
     * @Template()
     *
     * @return array
     */
    public function loginAction()
    {
        $userAgent = 1; // The Browser is supported.
        $csrfToken = $this->container->has('form.csrf_provider')
            ? $this->container->get('form.csrf_provider')->generateCsrfToken('authenticate')
            : null;

        if ($this->getUser()){
            $cookies = $this->get('request')->cookies;
            if (!$cookies->has('msgExp')){
                if ( preg_match( "/MSIE/", $_SERVER["HTTP_USER_AGENT"] ) ) {
                    $userAgent = 0; // The browser not supported.
                    $cookie = new Cookie('msgExp', 'value', time() + 86400, '/');
                    $response = new Response();
                    $response->headers->setCookie($cookie);
                    $response->sendHeaders();
                    return array(
                        'csrf_token' => $csrfToken,
                        'user_agent' => $userAgent,
                    );
                }
            }
        }

        return array(
            'csrf_token' => $csrfToken,
            'user_agent' => $userAgent,
        );
    }
}
