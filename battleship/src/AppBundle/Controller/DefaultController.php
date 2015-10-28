<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class DefaultController extends Controller
{
    /**
     * @Route("/", name="homepage")
     */
    public function indexAction(Request $request)
    {
        /**
         * @var \AppBundle\Entity\Grid
         */
        $grid = $this->get('grid');
        return $this->render('AppBundle:Default:index.html.twig', array(
            'grid' => $grid->show(),
        ));
    }

    /**
     * @Route("/resetgrid", name="resetgrid")
     */
    public function resetGridAction(Request $request)
    {
        /**
         * @var \AppBundle\Entity\Grid
         */
        $grid = $this->get('grid');
        return $this->render('AppBundle:Default:index.html.twig', array(
            'grid' => $grid->reset(),
        ));
    }
}
