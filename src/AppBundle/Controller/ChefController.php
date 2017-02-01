<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

use AppBundle\Entity\Chef;

use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class ChefController extends Controller
{
    /**
     * @Route("/chefs", name="chef_list")
     */
    public function indexAction(Request $request)
    {
        $chefs = $this->getDoctrine()->getRepository('AppBundle:Chef')->findAll();

        return $this->render('chef/index.html.twig', [
            'chefs'    => $chefs
        ]);
    }

    /**
     * @Route("/chef/create", name="chef_create")
     */
    public function createAction(Request $request)
    {
        $chef = new Chef();

        $form = $this->createFormBuilder($chef)
            ->add('name'    , TextType::class, array(
                 'attr' => array('class' => 'form-control', 'style' => 'margin-bottom: 15px')
            ))
            ->add('location' , TextType::class, array(
                 'attr' => array('class' => 'form-control', 'style' => 'margin-bottom: 15px')
            ))
            ->add('save', SubmitType::class, array(
                'label' => 'Create Chef', 
                'attr'  => array('class' => 'btn btn-primary')
            ))
            ->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $chef = $form->getData();

            $em = $this->getDoctrine()->getManager();
            $em->persist($chef);
            $em->flush();

            $this->addFlash('success', 'Chef was successfully created.');

            return $this->redirectToRoute('chef_list');
        }

        return $this->render('chef/create.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/chef/edit/{id}", name="chef_edit")
     */
    public function editAction($id, Request $request)
    {
        $chef = $this->getDoctrine()->getRepository('AppBundle:Chef')->find($id);

        if(!$chef){
            throw $this->createNotFoundException("No Chef found with id of " . $id);
        }

        $chef->setName($chef->getName());
        $chef->setLocation($chef->getLocation());

        $form = $this->createFormBuilder($chef)
            ->add('name'    , TextType::class, array(
                 'attr' => array('class' => 'form-control', 'style' => 'margin-bottom: 15px')
            ))
            ->add('location' , TextType::class, array(
                 'attr' => array('class' => 'form-control', 'style' => 'margin-bottom: 15px')
            ))
            ->add('save', SubmitType::class, array(
                'label' => 'Edit Chef', 
                'attr'  => array('class' => 'btn btn-primary')
            ))
            ->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $em = $this->getDoctrine()->getManager();
            
            $em->flush();

            $this->addFlash('success', 'Chef was successfully edited.');

            return $this->redirectToRoute('chef_list');
        }

        return $this->render('chef/edit.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/chef/delete/{id}", name="chef_delete")
     */
    public function deleteAction($id, Request $request)
    {
        $chef = $this->getDoctrine()->getRepository('AppBundle:Chef')->find($id);

        if(!$chef){
            throw $this->createNotFoundException("No Chef found with id of " . $id);
        }

        $form = $this->createFormBuilder($chef)
            ->add('save', SubmitType::class, array(
                'label' => 'Delete Chef', 
                'attr'  => array('class' => 'btn btn-primary')
            ))
            ->getForm();

        $form->handleRequest($request);

        if ( $request->getMethod() == 'POST' ) {

            $em = $this->getDoctrine()->getManager();
            
            $em->remove($chef);

            $em->flush();

            $this->addFlash('success', 'Chef was successfully deleted.');
        }

        return $this->redirectToRoute('chef_list');
    }
}
