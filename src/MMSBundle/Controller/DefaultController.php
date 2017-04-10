<?php

namespace MMSBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction()
    {
        return $this->render('MMSBundle:Default:index.html.twig');
    }
    public function ShopAction()
    {
        return $this->render('MMSBundle:Default:index.html.twig');
    }
}
