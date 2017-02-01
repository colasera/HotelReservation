<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

use AppBundle\Entity\Inventory;

use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class InventoryController extends Controller
{
    /**
     * @Route("/inventories", name="inventory_list")
     */
    public function indexAction(Request $request)
    {
        $inventories = $this->getDoctrine()->getRepository('AppBundle:Inventory')->findAll();

        return $this->render('inventory/index.html.twig', [
            'inventories'    => $inventories
        ]);
    }

    /**
     * @Route("/inventory/create", name="inventory_create")
     */
    public function createAction(Request $request)
    {
        $inventory = new Inventory();

        $form = $this->createFormBuilder($inventory)
            ->add('type'    , TextType::class, array(
                 'attr' => array('class' => 'form-control', 'style' => 'margin-bottom: 15px')
            ))
            ->add('status' , TextType::class, array(
                 'attr' => array('class' => 'form-control', 'style' => 'margin-bottom: 15px')
            ))
            ->add('save', SubmitType::class, array(
                'label' => 'Create Inventory', 
                'attr'  => array('class' => 'btn btn-primary')
            ))
            ->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $inventory = $form->getData();

            $em = $this->getDoctrine()->getManager();
            $em->persist($inventory);
            $em->flush();

            $this->addFlash('success', 'Inventory was successfully created.');

            return $this->redirectToRoute('inventory_list');
        }

        return $this->render('inventory/create.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/inventory/edit/{id}", name="inventory_edit")
     */
    public function editAction($id, Request $request)
    {
        $inventory = $this->getDoctrine()->getRepository('AppBundle:Inventory')->find($id);

        if(!$inventory){
            throw $this->createNotFoundException("No Inventory found with id of " . $id);
        }

        $inventory->setType($inventory->getType());
        $inventory->setStatus($inventory->getStatus());

        $form = $this->createFormBuilder($inventory)
            ->add('type'    , TextType::class, array(
                 'attr' => array('class' => 'form-control', 'style' => 'margin-bottom: 15px')
            ))
            ->add('status' , TextType::class, array(
                 'attr' => array('class' => 'form-control', 'style' => 'margin-bottom: 15px')
            ))
            ->add('save', SubmitType::class, array(
                'label' => 'Edit Inventory', 
                'attr'  => array('class' => 'btn btn-primary')
            ))
            ->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $em = $this->getDoctrine()->getManager();
            
            $em->flush();

            $this->addFlash('success', 'Inventory was successfully edited.');

            return $this->redirectToRoute('inventory_list');
        }

        return $this->render('inventory/edit.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/inventory/delete/{id}", name="inventory_delete")
     */
    public function deleteAction($id, Request $request)
    {
        $inventory = $this->getDoctrine()->getRepository('AppBundle:Inventory')->find($id);

        if(!$inventory){
            throw $this->createNotFoundException("No Inventory found with id of " . $id);
        }

        $form = $this->createFormBuilder($inventory)
            ->add('save', SubmitType::class, array(
                'label' => 'Delete Inventory', 
                'attr'  => array('class' => 'btn btn-primary')
            ))
            ->getForm();

        $form->handleRequest($request);

        if ( $request->getMethod() == 'POST' ) {

            $em = $this->getDoctrine()->getManager();
            
            $em->remove($inventory);

            $em->flush();

            $this->addFlash('success', 'Inventory was successfully deleted.');
        }

        return $this->redirectToRoute('inventory_list');
    }
}
