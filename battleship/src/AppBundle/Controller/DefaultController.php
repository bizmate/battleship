<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Hit;
use AppBundle\Exceptions\NoShotSquareException;
use AppBundle\Form\HitType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;

class DefaultController extends Controller
{
    /**
     * @Route("/", name="homepage")
     */
    public function indexAction(Request $request)
    {
        /**
         * Ideally should set the controller as a service and pass the dependencies through
         * the constructor as arguments but for the purpose of this battle we use the
         * DI containter directly from the parent framework controller
         *
         * @var \AppBundle\Entity\Grid
         */
        $grid = $this->get('grid');
        $ships = $grid->getShips();



        /*
         * Created a form but going to use a raw approach for now
         * $hit = new Hit();

        $form = $this->createForm(new HitType(), $hit, array(
            'action' => $this->generateUrl('hit'),
            'method' => 'POST',
        ));*/


        return $this->render('AppBundle:Default:index.html.twig', array(
            'grid' => $grid->show(),
            'ships' => $ships,
            'battleshipSquares' => $grid->getBattleship()->getSquaresOccupied(),
            'destroyerSquares' => $grid->getDestroyer()->getSquaresOccupied(),
            'alphas' => range('A', 'Z'),
            //'form'=>$form
            'hitAction' => $this->generateUrl('hit'),
            'completed' => $grid->isCompleted(),
            'hitsCount' => $grid->getHitsCount()
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
        $grid->reset();

        $this->addFlash('notice','Game reset - New game started ' );

        return $this->redirectToRoute('homepage');
    }

    /**
     * @Route("/show", name="show")
     */
    public function showAction(Request $request)
    {
        /**
         * @var \AppBundle\Entity\Grid
         */
        $grid = $this->get('grid');
        return $this->render('AppBundle:Default:show.html.twig', array(
            'grid' => $grid->show(),
            'shipsSquares' => $grid->getShipsSquaresPositions(),
            'alphas' => range('A', 'Z'),
            'completed' => $grid->isCompleted()
        ));
    }

    /**
     * @Route("/hit", name="hit")
     * @Method({"POST"})
     */
    public function hitAction(Request $request)
    {
        $this->alreadyComplete();
        $hitFormat = '/^[A-J][0-9]$/';
        $hitVal =$request->get('hit');
        // raw validation - should sit somewhere else or use FormTypes
        if(!preg_match ( $hitFormat  , $hitVal ) == 1 )
        {
            $this->addFlash('notice','Hit values are from A0 to J9 - Format NOT matched : ' . $hitVal );
            return $this->redirectToRoute('homepage');
        }

        $grid = $this->get('grid');

        try{
            $result = $grid->hit($hitVal);
        }
        catch(NoShotSquareException $e){
            $result = ' - Already hit or missed this Square PLEASE TRY OTHER SQUARES -';
        }

        $this->addFlash('notice','Hit result : ' . $result  . ' original : ' . $hitVal . ' Hits count: ' . $grid->getHitsCount());
        return $this->redirectToRoute('homepage');
    }

    private function alreadyComplete()
    {
        $grid = $this->get('grid');
        if($grid->isCompleted())
        {
            $this->addFlash(
                'notice','Game already completed in ' . $grid->getHitsCount() . ' hits. Please visit reset link'
            );
            return $this->redirectToRoute('homepage');
        }
    }
}
