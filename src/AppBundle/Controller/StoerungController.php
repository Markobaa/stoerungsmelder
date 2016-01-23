<?php

namespace AppBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use AppBundle\Entity\Stoerung;
use AppBundle\Entity\Maschine;
use AppBundle\Form\StoerungBeendenType;
use AppBundle\Form\StoerungType;

/**
 * Default controller. Hier finden alle Aktionen auf den Datensätzen statt
 *
 * @Route("/stoerungen")
 */
class StoerungController extends Controller
{
    /**
     * @Route("/aktuell", name="aktuelle_stoerungen")
     * @Template()
     */
    public function aktuellAction()
    {
        $em = $this->getDoctrine()->getManager();

        $aktuell = $em->getRepository('AppBundle:Stoerung')
                       ->findBy(array('behoben'=>false));

        return array(
            'stoerungen_aktuell'=>$aktuell,

        );
    }

    /**
     * @Route("/behoben/{abteilung}", defaults={"abteilung" = null}, name="behobene_stoerungen")
     * @Template()
     */
    public function behobenAction($abteilung)
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
         {
        $request = $this -> getrequest();
        $em = $this->getDoctrine()->getManager();
        $behoben = $em->getRepository('AppBundle:Stoerung')
                       ->findBy(array('behoben'=>true),
                                array('stStart'=>'DESC'));
        $paginator = $this -> get('knp_paginator');
        return array(
            'stoerungen_behoben'=>$paginator->paginate( $behoben, $request -> query->get('page',1), 15),
            'abteilungen' => $abteilungen,
            'aktive_abteilung' => $abteilung,
            'maschinen' => $maschinen   
       );
    }
}
    /**
     * @Route("/beenden/{id}", name="stoerung_beenden")
     * @Template()
     */
    public function stoerungBeendenAction($id)
    {
        $this->denyAccessUnlessGranted('stoerung_beenden');
        $em = $this->getDoctrine()->getManager();
        $entity = $em->getRepository('AppBundle:Stoerung')->find($id);
        $entity->setStEnd( new \DateTime() );
        $form = $this->createForm(new StoerungBeendenType(), $entity, array(
            'action' => $this->generateUrl('stoerung_beenden_save', array('id' => $entity->getId())),
            'method' => 'POST',
        ));

        $form->add('submit', 'submit', array('label' => 'beenden'));
        return array(
            'entity'      => $entity,
            'form'   => $form->createView()
        );
    }

    /**
     * @Route("/beenden/{id}/save", name="stoerung_beenden_save")
     * @Method("POST")
     * @Template()
     */
    public function stoerungBeendenSaveAction(Request $request,$id)
    {
        $this->denyAccessUnlessGranted('stoerung_beenden');
        $em = $this->getDoctrine()->getManager();
        $entity = $em->getRepository('AppBundle:Stoerung')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Stoerung entity.');
        }
        $entity->setBehoben(true);
        $form = $this->createForm(new StoerungBeendenType(), $entity);
        $form->add('submit', 'submit', array('label' => 'beenden'));
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em->flush();

            return $this->redirect($this->generateUrl('aktuelle_stoerungen') );
        }
        return $this->redirect($this->generateUrl('stoerung_beenden',array('id'=>$id)) );
    }

    /**
     * @Route("/neu", name="stoerung_neu")
     * @Template()
     */
    public function stoerungNeuAction()
    {
        $this->denyAccessUnlessGranted('stoerung_melden');
        $em = $this->getDoctrine()->getManager();
        $abteilungen = $em->getRepository('AppBundle:Abteilung')
                          ->findAll();

        return array(
            'abteilungen'=> $abteilungen
        );
    }

    /**
     * @Route("/neu/{maschineId}/{art}", name="stoerung_neu_save")
     * @Method("POST")
     * @Template()
     */
    public function stoerungNeuSaveAction(Request $request, $maschineId,$art)
    {
        $em = $this->getDoctrine()->getManager();
        $maschine = $em->getRepository('AppBundle:Maschine')->find($maschineId);
        $entity = new Stoerung();
        $form = $this->createForm(new StoerungType(), $entity);
        $form->handleRequest($request);
        $entity->setStStart( new \DateTime() );
        $entity->setStEnd( new \DateTime() );
        $entity->setBehoben(false);
        $entity->setMaschine( $maschine );
        $entity->setArt( $art );

        $dump = ($entity);
        $em->persist($entity);
        $em->flush();
        return $this->redirect($this->generateUrl('aktuelle_stoerungen') );
    }

    /**
     * @Route("/detail/{id}", name="stoerung_show")
     * @Template()
     */
    public function showAction(Request $request,$id)
    {
        $em = $this->getDoctrine()->getManager();
        $entity = $em->getRepository('AppBundle:Stoerung')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Stoerung entity.');
        }
        return array(
            'entity'=> $entity
        );
    }
}
