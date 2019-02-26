<?php

namespace App\Controller;

const DEFAULT_URL = 'https://pimpofpet.firebaseio.com/';
const DEFAULT_TOKEN = 'FAGswNvJdDthRMHLSjhD84t8EcWjJhKiqZk9kQNN';
const DEFAULT_PATH = '/user';

use App\Entity\MyPetUser;
use App\Form\MyPetUserType;
use App\Repository\MyPetUserRepository;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Entity;

/**
 * @Route("/user")
 */
class MyPetUserController extends AbstractController
{
    /**
     * @Route("/", name="my_pet_user_index", methods={"GET"})
     */
    public function index(MyPetUserRepository $myPetUserRepository): Response
    {

        return $this->render('my_pet_user/index.html.twig', [
            'my_pet_users' => $myPetUserRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="my_pet_user_new", methods={"GET","POST"})
     */
    public function new(Request $request, MyPetUserRepository $myPetUserRepository): Response
    {
        $myPetUser = new MyPetUser();
        $form = $this->createForm(MyPetUserType::class, $myPetUser);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $myPetUserRepository->add($myPetUser);

        }

        return $this->render('my_pet_user/new.html.twig', [
            'my_pet_user' => $myPetUser,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="my_pet_user_show", methods={"GET"})
     * @Entity("myPetUser", expr="repository.findOne(id)")
     */
    public function show(MyPetUser $myPetUser): Response
    {

        return $this->render('my_pet_user/show.html.twig', [
            'my_pet_user' => $myPetUser,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="my_pet_user_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, MyPetUser $myPetUser): Response
    {
        $form = $this->createForm(MyPetUserType::class, $myPetUser);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('my_pet_user_index', [
                'id' => $myPetUser->getId(),
            ]);
        }

        return $this->render('my_pet_user/edit.html.twig', [
            'my_pet_user' => $myPetUser,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="my_pet_user_delete", methods={"DELETE"})
     */
    public function delete(Request $request, MyPetUser $myPetUser): Response
    {
        if ($this->isCsrfTokenValid('delete' . $myPetUser->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($myPetUser);
            $entityManager->flush();
        }

        return $this->redirectToRoute('my_pet_user_index');
    }
}
