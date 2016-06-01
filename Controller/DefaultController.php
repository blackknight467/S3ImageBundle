<?php

namespace blackknight467\S3ImageBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction()
    {
        return $this->render('S3ImageBundle:Default:index.html.twig');
    }
}
