<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

use AppBundle\Entity\Manager;

use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class ManagerController extends Controller
{
    /**
     * @Route("/managers", name="manager_list")
     */
    public function indexAction(Request $request)
    {
        $managers = $this->getDoctrine()->getRepository('AppBundle:Manager')->findAll();

        return $this->render('manager/index.html.twig', [
            'managers'    => $managers
        ]);
    }

    /**
     * @Route("/manager/create", name="manager_create")
     */
    public function createAction(Request $request)
    {
        $manager = new Manager();

        $form = $this->createFormBuilder($manager)
            ->add('name'    , TextType::class, array(
                 'attr' => array('class' => 'form-control', 'style' => 'margin-bottom: 15px')
            ))
            ->add('phoneNo' , TextType::class, array(
                 'attr' => array('class' => 'form-control', 'style' => 'margin-bottom: 15px')
            ))
            ->add('location', TextType::class, array(
                 'attr' => array('class' => 'form-control', 'style' => 'margin-bottom: 15px')
            ))
            ->add('save', SubmitType::class, array(
                'label' => 'Create Manager', 
                'attr'  => array('class' => 'btn btn-primary')
            ))
            ->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $manager = $form->getData();

            $em = $this->getDoctrine()->getManager();
            $em->persist($manager);
            $em->flush();

            $this->addFlash('success', 'Manager was successfully created.');

            return $this->redirectToRoute('manager_list');
        }

        return $this->render('manager/create.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/manager/edit/{id}", name="manager_edit")
     */
    public function editAction($id, Request $request)
    {
        $manager = $this->getDoctrine()->getRepository('AppBundle:Manager')->find($id);

        if(!$manager){
            throw $this->createNotFoundException("No Manager found with id of " . $id);
        }

        $manager->setName($manager->getName());
        $manager->setPhoneNo($manager->getPhoneNo());
        $manager->setLocation($manager->getLocation());

        $form = $this->createFormBuilder($manager)
            ->add('name'    , TextType::class, array(
                 'attr' => array('class' => 'form-control', 'style' => 'margin-bottom: 15px')
            ))
            ->add('phoneNo' , TextType::class, array(
                 'attr' => array('class' => 'form-control', 'style' => 'margin-bottom: 15px')
            ))
            ->add('location', TextType::class, array(
                 'attr' => array('class' => 'form-control', 'style' => 'margin-bottom: 15px')
            ))
            ->add('save', SubmitType::class, array(
                'label' => 'Edit Manager', 
                'attr'  => array('class' => 'btn btn-primary')
            ))
            ->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $em = $this->getDoctrine()->getManager();
            
            $em->flush();

            $this->addFlash('success', 'Manager was successfully edited.');

            return $this->redirectToRoute('manager_list');
        }

        return $this->render('manager/edit.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/manager/delete/{id}", name="manager_delete")
     */
    public function deleteAction($id, Request $request)
    {
        $manager = $this->getDoctrine()->getRepository('AppBundle:Manager')->find($id);

        if(!$manager){
            throw $this->createNotFoundException("No Manager found with id of " . $id);
        }

        $form = $this->createFormBuilder($manager)
            ->add('save', SubmitType::class, array(
                'label' => 'Delete Manager', 
                'attr'  => array('class' => 'btn btn-primary')
            ))
            ->getForm();

        $form->handleRequest($request);

        if ( $request->getMethod() == 'POST' ) {

            $em = $this->getDoctrine()->getManager();
            
            $em->remove($manager);

            $em->flush();

            $this->addFlash('success', 'Manager was successfully deleted.');
        }

        return $this->redirectToRoute('manager_list');
    }
}
