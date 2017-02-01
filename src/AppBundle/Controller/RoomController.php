<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

use AppBundle\Entity\Room;

use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

use Doctrine\DBAL\Exception\ForeignKeyConstraintViolationException;

class RoomController extends Controller
{
    /**
     * @Route("/rooms", name="room_list")
     */
    public function indexAction(Request $request)
    {
        $rooms = $this->getDoctrine()->getRepository('AppBundle:Room')->findAll();

        return $this->render('room/index.html.twig', [
            'rooms'    => $rooms
        ]);
    }

    /**
     * @Route("/room/create", name="room_create")
     */
    public function createAction(Request $request)
    {
        $room = new Room();

        $form = $this->createFormBuilder($room)
            ->add('location' , TextType::class, array(
                 'attr' => array('class' => 'form-control', 'style' => 'margin-bottom: 15px')
            ))
            ->add('save', SubmitType::class, array(
                'label' => 'Create Room', 
                'attr'  => array('class' => 'btn btn-primary')
            ))
            ->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $room = $form->getData();

            $em = $this->getDoctrine()->getManager();
            $em->persist($room);
            $em->flush();

            $this->addFlash('success', 'Room was successfully created.');

            return $this->redirectToRoute('room_list');
        }

        return $this->render('room/create.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/room/edit/{id}", name="room_edit")
     */
    public function editAction($id, Request $request)
    {
        $room = $this->getDoctrine()->getRepository('AppBundle:Room')->find($id);

        if(!$room){
            throw $this->createNotFoundException("No Room found with id of " . $id);
        }

        $room->setLocation($room->getLocation());

        $form = $this->createFormBuilder($room)
            ->add('location' , TextType::class, array(
                 'attr' => array('class' => 'form-control', 'style' => 'margin-bottom: 15px')
            ))
            ->add('save', SubmitType::class, array(
                'label' => 'Edit Room', 
                'attr'  => array('class' => 'btn btn-primary')
            ))
            ->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $em = $this->getDoctrine()->getManager();
            
            $em->flush();

            $this->addFlash('success', 'Room was successfully edited.');

            return $this->redirectToRoute('room_list');
        }

        return $this->render('room/edit.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/room/delete/{id}", name="room_delete")
     */
    public function deleteAction($id, Request $request)
    {
        $room = $this->getDoctrine()->getRepository('AppBundle:Room')->find($id);

        if(!$room){
            throw $this->createNotFoundException("No Room found with id of " . $id);
        }

        $form = $this->createFormBuilder($room)
            ->add('save', SubmitType::class, array(
                'label' => 'Delete Room', 
                'attr'  => array('class' => 'btn btn-primary')
            ))
            ->getForm();

        $form->handleRequest($request);

        if ( $request->getMethod() == 'POST' ) {

            $em = $this->getDoctrine()->getManager();
            
            try 
            {
                $em->remove($room);

                $em->flush();

                $this->addFlash('success', 'Room was successfully deleted.');
            } 
            catch(ForeignKeyConstraintViolationException $e) 
            {
                $this->addFlash('failure', 'Room can not be deleted. Maybe it is already used by a customer.');
            }
        }

        return $this->redirectToRoute('room_list');
    }
}
