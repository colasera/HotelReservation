<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

use AppBundle\Entity\FoodItem;

use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class FoodItemController extends Controller
{
    /**
     * @Route("/food_items", name="food_item_list")
     */
    public function indexAction(Request $request)
    {
        $food_items = $this->getDoctrine()->getRepository('AppBundle:FoodItem')->findAll();

        return $this->render('food_item/index.html.twig', [
            'food_items'    => $food_items
        ]);
    }

    /**
     * @Route("/food_item/create", name="food_item_create")
     */
    public function createAction(Request $request)
    {
        $food_item = new FoodItem();

        $form = $this->createFormBuilder($food_item)
            ->add('name' , TextType::class, array(
                 'attr' => array('class' => 'form-control', 'style' => 'margin-bottom: 15px')
            ))
            ->add('save', SubmitType::class, array(
                'label' => 'Create Food Item', 
                'attr'  => array('class' => 'btn btn-primary')
            ))
            ->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $food_item = $form->getData();

            $em = $this->getDoctrine()->getManager();
            $em->persist($food_item);
            $em->flush();

            $this->addFlash('success', 'Food Item was successfully created.');

            return $this->redirectToRoute('food_item_list');
        }

        return $this->render('food_item/create.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/food_item/edit/{id}", name="food_item_edit")
     */
    public function editAction($id, Request $request)
    {
        $food_item = $this->getDoctrine()->getRepository('AppBundle:FoodItem')->find($id);

        if(!$food_item){
            throw $this->createNotFoundException("No Food Item found with id of " . $id);
        }

        $food_item->setName($food_item->getName());

        $form = $this->createFormBuilder($food_item)
            ->add('name' , TextType::class, array(
                 'attr' => array('class' => 'form-control', 'style' => 'margin-bottom: 15px')
            ))
            ->add('save', SubmitType::class, array(
                'label' => 'Edit Food Item', 
                'attr'  => array('class' => 'btn btn-primary')
            ))
            ->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $em = $this->getDoctrine()->getManager();
            
            $em->flush();

            $this->addFlash('success', 'Food Item was successfully edited.');

            return $this->redirectToRoute('food_item_list');
        }

        return $this->render('food_item/edit.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/food_item/delete/{id}", name="food_item_delete")
     */
    public function deleteAction($id, Request $request)
    {
        $food_item = $this->getDoctrine()->getRepository('AppBundle:FoodItem')->find($id);

        if(!$food_item){
            throw $this->createNotFoundException("No Food Item found with id of " . $id);
        }

        $form = $this->createFormBuilder($food_item)
            ->add('save', SubmitType::class, array(
                'label' => 'Delete Food Item', 
                'attr'  => array('class' => 'btn btn-primary')
            ))
            ->getForm();

        $form->handleRequest($request);

        if ( $request->getMethod() == 'POST' ) {

            $em = $this->getDoctrine()->getManager();
            
            $em->remove($food_item);

            $em->flush();

            $this->addFlash('success', 'Food Item was successfully deleted.');
        }

        return $this->redirectToRoute('food_item_list');
    }
}
