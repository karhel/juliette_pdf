<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserFormType;
use App\Service\PdfGenerator;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

final class UserController extends AbstractController
{
    #[Route('/user', name: 'app_user')]
    public function index(UserRepository $userRepository): Response
    {
        return $this->render('user/index.html.twig', [
            'users' => $userRepository->findAll(),
        ]);
    }

    #[Route('/user/create', name: 'app_user_create')]
    public function create(Request $request, EntityManagerInterface $entityManager): Response
    {
        $user = new User();
        $userFormCreate = $this->createForm(UserFormType::class, $user);

        $userFormCreate->handleRequest($request);

        if($userFormCreate->isSubmitted() && $userFormCreate->isValid())
        {
            $entityManager->persist($user);
            $entityManager->flush();

            // avec le code HTTP 307 en direction, il y a forward du contenu du $_POST et donc de la partie formulaire d'inscription
            // et donc possibilité de récupérer la valeur du champ "password"
            return $this->redirectToRoute('app_user_create_pdf', [], 307);
        }

        return $this->render('user/create.html.twig', [
            'userFormCreate' => $userFormCreate
        ]);
    }

    #[Route('/user/create-pdf', name: 'app_user_create_pdf', methods: ['POST'])]
    public function generateCreatePdf(Request $request, UserRepository $userRepository, PdfGenerator $pdfGenerator): Response
    {
        $password = $request->get('user_form')['password'];

        // Je renvois le fichier sous la forme d'un stream
        return $pdfGenerator->getStreamResponse("<h1>Hello World! $password</h1>", 'file.pdf');
    }
}
