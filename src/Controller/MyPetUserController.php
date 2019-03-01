<?php

namespace App\Controller;

// BDD de test
// const DEFAULT_URL = 'https://pimpofpet.firebaseio.com/';
// const DEFAULT_TOKEN = 'FAGswNvJdDthRMHLSjhD84t8EcWjJhKiqZk9kQNN';
// const DEFAULT_PATH = '/User';

// BDD Firebase En lien avec l'appli mobile
const DEFAULT_URL = 'https://mypetphase2.firebaseio.com/';
const DEFAULT_TOKEN = '57RCM6y5VLc4SCNG3QsXvebuMikYG7xbWtNdRmlZ';
const DEFAULT_PATH = '/Users';

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
     * @param MyPetUserRepository $myPetUserRepository
     * @return Response
     */
    public function index(MyPetUserRepository $myPetUserRepository): Response
    {

        return $this->render('my_pet_user/index.html.twig', [
            'my_pet_users' => $myPetUserRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="my_pet_user_new", methods={"GET","POST"})
     * @param Request $request
     * @param MyPetUserRepository $myPetUserRepository
     * @return Response
     */
    public function new(Request $request, MyPetUserRepository $myPetUserRepository): Response
    {
        $myPetUser = new MyPetUser();
        $form = $this->createForm(MyPetUserType::class, $myPetUser);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $myPetUser = $myPetUserRepository->add($myPetUser);

            return $this->render('my_pet_user/show.html.twig', [
                'my_pet_user' => $myPetUser,
            ]);

        }

        return $this->render('my_pet_user/new.html.twig', [
            'my_pet_user' => $myPetUser,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="my_pet_user_show", methods={"GET"})
     * @Entity("myPetUser", expr="repository.findOne(id)")
     * @param MyPetUser $myPetUser
     * @return Response
     */
    public function show(MyPetUser $myPetUser): Response
    {

        return $this->render('my_pet_user/show.html.twig', [
            'my_pet_user' => $myPetUser,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="my_pet_user_edit", methods={"GET","POST"})
     * @Entity("myPetUser", expr="repository.findOne(id)")
     * @param Request $request
     * @param MyPetUser $myPetUser
     * @param MyPetUserRepository $myPetUserRepository
     * @return Response
     */
    public function edit(Request $request, MyPetUser $myPetUser, MyPetUserRepository $myPetUserRepository): Response
    {
        $form = $this->createForm(MyPetUserType::class, $myPetUser);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $myPetUserRepository->update($myPetUser);

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
     * @Entity("myPetUser", expr="repository.delete(id)")
     * @param Request $request
     * @param String $MyPetUserId
     * @param MyPetUserRepository $myPetUserRepository
     * @return Response
     */
    public function delete(Request $request, String $MyPetUserId, MyPetUserRepository $myPetUserRepository): Response
    {
        if ($this->isCsrfTokenValid('delete' . $MyPetUserId, $request->request->get('_token'))) {

            $MyPetUserId = $myPetUserRepository->delete($MyPetUserId);

            return $this->redirectToRoute('my_pet_user_index', [
                'id' => $MyPetUserId,
            ]);

        }

        return $this->redirectToRoute('my_pet_user_delete', [
            'id' => $MyPetUserId,
        ]);
    }
}
