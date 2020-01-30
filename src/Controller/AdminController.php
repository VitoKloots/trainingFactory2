<?php

namespace App\Controller;

use App\Entity\Training;
use App\Form\TrainingType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Require ROLE_ADMIN for *every* controller method in this class
 * .
 * @Route("/admin", name="admin_")
 * @IsGranted("ROLE_ADMIN")
 */

class AdminController extends AbstractController
{
    /**
     * @Route("/new", name="training_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $training = new Training();
        $form = $this->createForm(TrainingType::class, $training);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($training);
            $entityManager->flush();

            return $this->redirectToRoute('training_index');
        }

        return $this->render('admin/new.html.twig', [
            'training' => $training,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="training_show", methods={"GET"})
     */
    public function show(Training $training): Response
    {
        return $this->render('admin/show.html.twig', [
            'training' => $training,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="training_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Training $training): Response
    {
        $form = $this->createForm(TrainingType::class, $training);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('training_index');
        }

        return $this->render('admin/edit.html.twig', [
            'training' => $training,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="training_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Training $training): Response
    {
        if ($this->isCsrfTokenValid('delete'.$training->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($training);
            $entityManager->flush();
        }

        return $this->redirectToRoute('training_index');
    }
}
