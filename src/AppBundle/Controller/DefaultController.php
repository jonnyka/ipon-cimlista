<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Process\Process;

class DefaultController extends Controller
{
    /**
     * @Route("/test/", name="test")
     * @Template()
     */
    public function testAction()
    {
        return array();
    }

    /**
     * @Route("/", name="welcome")
     * @Template()
     */
    public function welcomeAction()
    {
        return array();
    }
}

