<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

use AppBundle\Entity\Bill;

use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;

class BillController extends Controller
{
    /**
     * @Route("/bills", name="bill_list")
     */
    public function indexAction(Request $request)
    {
        $bills = $this->getDoctrine()->getRepository('AppBundle:Bill')->findAll();

        return $this->render('bill/index.html.twig', [
            'bills'    => $bills
        ]);
    }

    /**
     * @Route("/bill/create", name="bill_create")
     */
    public function createAction(Request $request)
    {
        $bill = new Bill;

        $form = $this->createFormBuilder($bill)
            ->add('customerId' , EntityType::class, array(
                 'class' => 'AppBundle:Customer',
                 'choice_label' => 'name',
                 'attr'  => array('class' => 'form-control', 'style' => 'margin-bottom: 15px')
            ))
            ->add('save', SubmitType::class, array(
                'label' => 'Create Bill', 
                'attr'  => array('class' => 'btn btn-primary')
            ))
            ->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $bill = $form->getData();

            $em = $this->getDoctrine()->getManager();
            $em->persist($bill);
            $em->flush();

            $this->addFlash('success', 'Bill was successfully created.');

            return $this->redirectToRoute('bill_list');
        }

        return $this->render('bill/create.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/bill/edit/{id}", name="bill_edit")
     */
    public function editAction($id, Request $request)
    {
        $bill = $this->getDoctrine()->getRepository('AppBundle:Bill')->find($id);

        if(!$bill){
            throw $this->createNotFoundException("No Bill found with id of " . $id);
        }

        $bill->setCustomerId($bill->getCustomerId());

        $form = $this->createFormBuilder($bill)
            ->add('customerId' , EntityType::class, array(
                 'class' => 'AppBundle:Customer',
                 'choice_label' => 'name',
                 'attr'  => array('class' => 'form-control', 'style' => 'margin-bottom: 15px')
            ))
            ->add('save', SubmitType::class, array(
                'label' => 'Edit Bill', 
                'attr'  => array('class' => 'btn btn-primary')
            ))
            ->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $em = $this->getDoctrine()->getManager();
            
            $em->flush();

            $this->addFlash('success', 'Bill was successfully edited.');

            return $this->redirectToRoute('bill_list');
        }

        return $this->render('bill/edit.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/bill/delete/{id}", name="bill_delete")
     */
    public function deleteAction($id, Request $request)
    {
        $bill = $this->getDoctrine()->getRepository('AppBundle:Bill')->find($id);

        if(!$bill){
            throw $this->createNotFoundException("No Bill found with id of " . $id);
        }

        $form = $this->createFormBuilder($bill)
            ->add('save', SubmitType::class, array(
                'label' => 'Delete Bill', 
                'attr'  => array('class' => 'btn btn-primary')
            ))
            ->getForm();

        $form->handleRequest($request);

        if ( $request->getMethod() == 'POST' ) {

            $em = $this->getDoctrine()->getManager();
            
            $em->remove($bill);

            $em->flush();

            $this->addFlash('success', 'Bill was successfully deleted.');
        }

        return $this->redirectToRoute('bill_list');
    }
}
