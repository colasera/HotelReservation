<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

use AppBundle\Entity\HouseKeeping;

use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class HouseKeepingController extends Controller
{
    /**
     * @Route("/house_keepings", name="house_keeping_list")
     */
    public function indexAction(Request $request)
    {
        $house_keepings = $this->getDoctrine()->getRepository('AppBundle:HouseKeeping')->findAll();

        return $this->render('house_keeping/index.html.twig', [
            'house_keepings'    => $house_keepings
        ]);
    }

    /**
     * @Route("/house_keeping/create", name="house_keeping_create")
     */
    public function createAction(Request $request)
    {
        $house_keeping = new HouseKeeping();

        $form = $this->createFormBuilder($house_keeping)
            ->add('name' , TextType::class, array(
                 'attr' => array('class' => 'form-control', 'style' => 'margin-bottom: 15px')
            ))
            ->add('location' , TextType::class, array(
                 'attr' => array('class' => 'form-control', 'style' => 'margin-bottom: 15px')
            ))
            ->add('save', SubmitType::class, array(
                'label' => 'Create House Keeping', 
                'attr'  => array('class' => 'btn btn-primary')
            ))
            ->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $house_keeping = $form->getData();

            $em = $this->getDoctrine()->getManager();
            $em->persist($house_keeping);
            $em->flush();

            $this->addFlash('success', 'House Keeping was successfully created.');

            return $this->redirectToRoute('house_keeping_list');
        }

        return $this->render('house_keeping/create.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/house_keeping/edit/{id}", name="house_keeping_edit")
     */
    public function editAction($id, Request $request)
    {
        $house_keeping = $this->getDoctrine()->getRepository('AppBundle:HouseKeeping')->find($id);

        if(!$house_keeping){
            throw $this->createNotFoundException("No House Keeping found with id of " . $id);
        }

        $house_keeping->setName($house_keeping->getName());
        $house_keeping->setLocation($house_keeping->getLocation());

        $form = $this->createFormBuilder($house_keeping)
            ->add('name' , TextType::class, array(
                 'attr' => array('class' => 'form-control', 'style' => 'margin-bottom: 15px')
            ))
            ->add('location' , TextType::class, array(
                 'attr' => array('class' => 'form-control', 'style' => 'margin-bottom: 15px')
            ))
            ->add('save', SubmitType::class, array(
                'label' => 'Edit House Keeping', 
                'attr'  => array('class' => 'btn btn-primary')
            ))
            ->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $em = $this->getDoctrine()->getManager();
            
            $em->flush();

            $this->addFlash('success', 'House Keeping was successfully edited.');

            return $this->redirectToRoute('house_keeping_list');
        }

        return $this->render('house_keeping/edit.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/house_keeping/delete/{id}", name="house_keeping_delete")
     */
    public function deleteAction($id, Request $request)
    {
        $house_keeping = $this->getDoctrine()->getRepository('AppBundle:HouseKeeping')->find($id);

        if(!$house_keeping){
            throw $this->createNotFoundException("No House Keeping found with id of " . $id);
        }

        $form = $this->createFormBuilder($house_keeping)
            ->add('save', SubmitType::class, array(
                'label' => 'Delete House Keeping', 
                'attr'  => array('class' => 'btn btn-primary')
            ))
            ->getForm();

        $form->handleRequest($request);

        if ( $request->getMethod() == 'POST' ) {

            $em = $this->getDoctrine()->getManager();
            
            $em->remove($house_keeping);

            $em->flush();

            $this->addFlash('success', 'House Keeping was successfully deleted.');
        }

        return $this->redirectToRoute('house_keeping_list');
    }
}
