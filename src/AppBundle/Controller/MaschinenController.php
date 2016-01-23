<?php

namespace AppBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use AppBundle\Form\MaschineType;
use AppBundle\Entity\Maschine;
use AppBundle\Entity\Stoerung;
use Ob\HighchartsBundle\Highcharts\Highchart;

/**
 * Maschinen nach Abteilung auflisten
 *
 * @Route("/maschinen")
 * @Security("is_granted('benutzer_verwalten')")
 */
class MaschinenController extends Controller
{

    /**
     * Maschinen einer Abteilung auflisten
     *
     * @Route("/list/{abteilung}",  defaults={"abteilung" = null}, name="maschinen_index")
     * @Method("GET")
     * @Template()
     */
    public function indexAction($abteilung)
    {
        $em = $this->getDoctrine()->getManager();

        $abteilungen = $em->getRepository('AppBundle:Abteilung')
                          ->findBy(array(), array('name' => 'asc'));

        if( $abteilung !== null ) {
	        $maschinen = $em->getRepository('AppBundle:Maschine')
	                        ->findBy(
	    						array('abteilung' => $abteilung)
	    					);
        } else {
        	$maschinen = array();
        }

        return array(
        	'aktive_abteilung' => $abteilung,
            'abteilungen'=> $abteilungen,
            'maschinen' => $maschinen
        );
    }
    /**
     * Maschine bearbeiten
     *
     * @Route("/edit/{maschine_id}",  name="maschinen_edit")
     * @Method({"GET","POST"})
     * @Template()
     */
    public function editAction(Request $request, $maschine_id)
    {
        $em = $this->getDoctrine()->getManager();
        $maschine = $em->getRepository('AppBundle:Maschine')->find($maschine_id);

        if (!$maschine) {
            throw $this->createNotFoundException('Maschine ID '.$maschine_id.' existiert nicht (mehr).');
        }
        $form = $this->createForm(new MaschineType(), $maschine );
        $form->add('submit', 'submit', array('label' => 'Speichern'));
        $form->handleRequest($request);

        if ($form->isValid()) {
        	$em->flush();
            return $this->redirect($this->generateUrl('maschinen_index',
            	          array('abteilung' => $maschine->getAbteilung()->getId() )
            	          ));
		}

        return array(
        	'form' => $form->createView(),
            'maschine'=> $maschine
        );
    }
    /**
     * Maschine erstellen
     *
     * @Route("/newmachine/{abteilung_id}",  name="maschinen_new")
     * @Method({"GET","POST"})
     * @Template()
     */
    public function newAction(Request $request, $abteilung_id)
    {
        $em = $this->getDoctrine()->getManager();
        $abteilung = $em->getRepository('AppBundle:Abteilung')->find($abteilung_id);

        if (!$abteilung) {
            throw $this->createNotFoundException('Abteilung ID '.$abteilung_id.' existiert nicht.');
        }

        $maschine = new Maschine();
        $maschine->setAbteilung( $abteilung );

        $form = $this->createForm(new MaschineType( array('zeige_abteilung' => false) ), $maschine );
        $form->add('submit', 'submit', array('label' => 'Anlegen'));
        $form->handleRequest($request);

        if ($form->isValid()) {
        	$em->persist($maschine);
        	$em->flush();
            return $this->redirect($this->generateUrl('maschinen_index',
            	          array('abteilung' => $maschine->getAbteilung()->getId() )
            	          ));
		}

        return array(
        	'form' => $form->createView(),
            'maschine'=> $maschine,
            'abteilung' => $abteilung
        );
    }
    /**
     * Maschine erstellen
     *
     * @Route("/viewmaschine/{maschine_id}",  name="maschinen_view")
     * @Method({"GET"})
     * @Template()
     */
    public function viewAction($maschine_id)
    {
        $em = $this->getDoctrine()->getManager();
        $maschine = $em->getRepository('AppBundle:Maschine')->find($maschine_id);

        if (!$maschine) {
            throw $this->createNotFoundException('Maschine ID '.$maschine_id.' existiert nicht (mehr).');
        }

        return array(
            'maschine'=> $maschine
        );
    }
  /**
     * Maschinen Details anzeigen
     *
     * @Route("/viewdetails/{maschine_id}/{behobenstatus}",  name="maschinen_details")
     * @Method({"GET"})
     * @Template()
     */
    public function viewDetailsAction(Request $request,$maschine_id,$behobenstatus)
    {
    	$request = $this -> getrequest();
        $em = $this->getDoctrine()->getManager();
        $maschine = $em->getRepository('AppBundle:Maschine')->find($maschine_id);
        if (!$maschine) {
            throw $this->createNotFoundException('Maschine ID '.$maschine_id.' existiert nicht (mehr).');
        }

        $em = $this->getDoctrine()->getManager();
        $result = $em->getRepository('AppBundle:Stoerung')
                          ->findBy(array('maschine' => $maschine,
                                         'behoben'=> $behobenstatus),
                                   array('stStart'=>'DESC'));

        $paginator = $this -> get('knp_paginator');
        $stoerungen = $paginator->paginate( $result,
                                            $request -> query->get('page',1) , /*page number*/
                                            10);                                /*limit per page */

        return array(
            'stoerungen' => $stoerungen,
            'maschine' => $maschine,
            'behoben' => $behobenstatus,
            );
    }
    /**
     * Maschinen Auslastung anzeigen
     *
     * @Route("/grafik/{maschine_id}",  name="maschinen_grafik")
     * @Method({"GET"})
     * @Template()
     */
    public function grafikAction(Request $request,$maschine_id)
    {
   
    }
}