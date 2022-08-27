<?php

namespace App\Controller;

use App\Entity\Classroom;
use App\Form\ClassroomType;
use App\Repository\ClassroomRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ClassroomController extends AbstractController
{
    /**
     * @Route("/classroom", name="app_classroom")
     */
    public function index(): Response
    {
        return $this->render('classroom/index.html.twig', [
            'controller_name' => 'ClassroomController',
        ]);
    }

    /**
     * @Route("/affiche" , name = "affiche")
     */
    public function Affiche(ClassroomRepository $repo)
    {
        //$repo = $this->getDoctrine()->getRepository(Classroom::class);
        $classroom = $repo->findAll();

        return $this->render('classroom/Affiche.html.twig', [
        'classroom' => $classroom,
    ]);
    }

    /**
     * @Route("/delete/{id}" , name = "delete")
     */
    public function delete($id, ClassroomRepository $repo)
    {
        $classroom = $repo->find($id);
        $em = $this->getDoctrine()->getManager();
        $em->remove($classroom);
        $em->flush();

        return $this->redirectToRoute('affiche');
    }

    /**
     * @Route ("/ajout" , name="ajout")
     */
    public function Add(Request $request)
    {
        $classroom = new Classroom();
        $form = $this->createForm(ClassroomType::class, $classroom);
        $form->add('save', SubmitType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($classroom);
            $em->flush();
        }

        return $this->render('classroom/ajout.html.twig', ['form' => $form->createView()]);
    }

    /**
     * @Route("/update/{id}" , name="update")
     */
    public function update($id, ClassroomRepository $repo, Request $request)
    {
        $classroom = $repo->find($id);
        $form = $this->createForm(ClassroomType::class, $classroom);
        $form->add('update', SubmitType::class);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->flush();

            return $this->redirectToRoute('affiche');
        }

        return $this->render('classroom/update.html.twig', ['form' => $form->createView()]);
    }
}
