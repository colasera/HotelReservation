<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

use AppBundle\Entity\Customer;

use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;

class CustomerController extends Controller
{
	/**
	 * @Route("/customers", name="customer_list")
	 */
	public function indexAction(Request $request)
	{
		$customers = $this->getDoctrine()->getRepository('AppBundle:Customer')->findAll();

		return $this->render('customer/index.html.twig', [
			'customers'    => $customers
		]);
	}

	/**
	 * @Route("/customer/create", name="customer_create")
	 */
	public function createAction(Request $request)
	{
		$customer = new Customer();

		$form = $this->createFormBuilder($customer)
			->add('name'    , TextType::class, array(
				 'attr' => array('class' => 'form-control', 'style' => 'margin-bottom: 15px')
			))
			->add('telNo' , TextType::class, array(
				 'attr' => array('class' => 'form-control', 'style' => 'margin-bottom: 15px')
			))
			->add('address' , TextType::class, array(
				 'attr' => array('class' => 'form-control', 'style' => 'margin-bottom: 15px')
			))
			->add('roomNo' , EntityType::class, array(
				 'class' => 'AppBundle:Room',
				 'choice_label' => 'id',
				 'attr'  => array('class' => 'form-control', 'style' => 'margin-bottom: 15px')
			))
			->add('save', SubmitType::class, array(
				'label' => 'Create Customer',
				'attr'  => array('class' => 'btn btn-primary')
			))
			->getForm();

		$form->handleRequest($request);

		if ($form->isSubmitted() && $form->isValid()) {

			$customer = $form->getData();

			dump($customer);

			//$customer->setRoomNo($customer->getRoomNo());

			$em = $this->getDoctrine()->getManager();
			$em->persist($customer);
			$em->flush();

			$this->addFlash('success', 'Customer was successfully created.');

			return $this->redirectToRoute('customer_list');
		}

		return $this->render('customer/create.html.twig', [
			'form' => $form->createView()
		]);
	}

	/**
	 * @Route("/customer/edit/{id}", name="customer_edit")
	 */
	public function editAction($id, Request $request)
	{
		$em = $this->getDoctrine()->getManager();

		$customer = $em->getRepository('AppBundle:Customer')->find($id);

		//$customer = $this->getDoctrine()->getRepository('AppBundle:Customer')->find($id);

        if(!$customer){
            throw $this->createNotFoundException("No Customer found with id of " . $id);
        }

        $customer->setName($customer->getName());
        $customer->setTelNo($customer->getTelNo());
        $customer->setAddress($customer->getAddress());
        $customer->setRoomNo($customer->getRoomNo());

        dump($customer);
        
        $form = $this->createFormBuilder($customer)
			->add('name'    , TextType::class, array(
				 'attr' => array('class' => 'form-control', 'style' => 'margin-bottom: 15px')
			))
			->add('telNo' , TextType::class, array(
				 'attr' => array('class' => 'form-control', 'style' => 'margin-bottom: 15px')
			))
			->add('address' , TextType::class, array(
				 'attr' => array('class' => 'form-control', 'style' => 'margin-bottom: 15px')
			))
			->add('roomNo' , EntityType::class, array(
				 'class' => 'AppBundle:Room',
				 'choice_label' => 'id',
				 'attr'  => array('class' => 'form-control', 'style' => 'margin-bottom: 15px')
			))
			->add('save', SubmitType::class, array(
				'label' => 'Edit Customer',
				'attr'  => array('class' => 'btn btn-primary')
			))
			->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $em = $this->getDoctrine()->getManager();
            
            $em->flush();

            $this->addFlash('success', 'Customer was successfully edited.');

            return $this->redirectToRoute('customer_list');
        }

        return $this->render('customer/edit.html.twig', [
            'form' => $form->createView()
        ]);
	}

	/**
	 * @Route("/customer/delete/{id}", name="customer_delete")
	 */
	public function deleteAction($id, Request $request)
	{
		$customer = $this->getDoctrine()->getRepository('AppBundle:Customer')->find($id);

        if(!$customer){
            throw $this->createNotFoundException("No Customer found with id of " . $id);
        }

        if ( $request->getMethod() == 'POST' ) {

            $em = $this->getDoctrine()->getManager();
            
            $em->remove($customer);

            $em->flush();

            $this->addFlash('success', 'Customer was successfully deleted.');
        }

        return $this->redirectToRoute('customer_list');
	}
}
