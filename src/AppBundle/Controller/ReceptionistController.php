<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

use AppBundle\Entity\Receptionist;

use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class ReceptionistController extends Controller
{
    /**
     * @Route("/receptionists", name="receptionist_list")
     */
    public function indexAction(Request $request)
    {
        $receptionists = $this->getDoctrine()->getRepository('AppBundle:Receptionist')->findAll();

        return $this->render('receptionist/index.html.twig', [
            'receptionists'    => $receptionists
        ]);
    }

    /**
     * @Route("/receptionist/create", name="receptionist_create")
     */
    public function createAction(Request $request)
    {
        $receptionist = new Receptionist();

        $form = $this->createFormBuilder($receptionist)
            ->add('name'    , TextType::class, array(
                 'attr' => array('class' => 'form-control', 'style' => 'margin-bottom: 15px')
            ))
            ->add('telNo' , TextType::class, array(
                 'attr' => array('class' => 'form-control', 'style' => 'margin-bottom: 15px')
            ))
            ->add('address' , TextType::class, array(
                 'attr' => array('class' => 'form-control', 'style' => 'margin-bottom: 15px')
            ))
            ->add('save', SubmitType::class, array(
                'label' => 'Create Receptionist', 
                'attr'  => array('class' => 'btn btn-primary')
            ))
            ->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $receptionist = $form->getData();

            $em = $this->getDoctrine()->getManager();
            $em->persist($receptionist);
            $em->flush();

            $this->addFlash('success', 'Receptionist was successfully created.');

            return $this->redirectToRoute('receptionist_list');
        }

        return $this->render('receptionist/create.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/receptionist/edit/{id}", name="receptionist_edit")
     */
    public function editAction($id, Request $request)
    {
        $receptionist = $this->getDoctrine()->getRepository('AppBundle:Receptionist')->find($id);

        if(!$receptionist){
            throw $this->createNotFoundException("No Receptionist found with id of " . $id);
        }

        $receptionist->setName($receptionist->getName());
        $receptionist->setTelNo($receptionist->getTelNo());
        $receptionist->setAddress($receptionist->getAddress());

        $form = $this->createFormBuilder($receptionist)
            ->add('name'    , TextType::class, array(
                 'attr' => array('class' => 'form-control', 'style' => 'margin-bottom: 15px')
            ))
            ->add('telNo' , TextType::class, array(
                 'attr' => array('class' => 'form-control', 'style' => 'margin-bottom: 15px')
            ))
            ->add('address' , TextType::class, array(
                 'attr' => array('class' => 'form-control', 'style' => 'margin-bottom: 15px')
            ))
            ->add('save', SubmitType::class, array(
                'label' => 'Edit Receptionist', 
                'attr'  => array('class' => 'btn btn-primary')
            ))
            ->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $em = $this->getDoctrine()->getManager();
            
            $em->flush();

            $this->addFlash('success', 'Receptionist was successfully edited.');

            return $this->redirectToRoute('receptionist_list');
        }

        return $this->render('receptionist/edit.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/receptionist/delete/{id}", name="receptionist_delete")
     */
    public function deleteAction($id, Request $request)
    {
        $receptionist = $this->getDoctrine()->getRepository('AppBundle:Receptionist')->find($id);

        if(!$receptionist){
            throw $this->createNotFoundException("No Receptionist found with id of " . $id);
        }

        $form = $this->createFormBuilder($receptionist)
            ->add('save', SubmitType::class, array(
                'label' => 'Delete Receptionist', 
                'attr'  => array('class' => 'btn btn-primary')
            ))
            ->getForm();

        $form->handleRequest($request);

        if ( $request->getMethod() == 'POST' ) {

            $em = $this->getDoctrine()->getManager();
            
            $em->remove($receptionist);

            $em->flush();

            $this->addFlash('success', 'Receptionist was successfully deleted.');
        }

        return $this->redirectToRoute('receptionist_list');
    }
}
