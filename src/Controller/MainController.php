<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Routing\Requirement\Requirement;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class MainController extends AbstractController
{
    #[Route('/api', name: 'app_main', methods: ['GET'])]
    public function index(UserRepository $repo, SerializerInterface $ser): JsonResponse

    {

        $users = $repo->findAll();
        $usersjson = $ser->serialize($users, 'json', ['groups' => 'show_product']);

        return new JsonResponse($usersjson, Response::HTTP_OK, [], true);
    }
    #[Route('/api/{id}', name: 'app_Get', methods: ['GET'], requirements: ['id' => Requirement::DIGITS])]
    public function UserGet(User $user, SerializerInterface $ser): JsonResponse

    {
        $userjson = $ser->serialize($user, 'json', ['groups' => 'show_product']);
        return new JsonResponse($userjson, Response::HTTP_OK, [], true);
    }

    #[Route('/api/{id}', name: 'app_Delete', methods: ['DELETE'], requirements: ['id' => Requirement::DIGITS])]
    public function UserDelete(User $user, EntityManagerInterface $em): JsonResponse

    {
        if ($user) {
            $em->remove($user);
            $em->flush();
            return new JsonResponse(null, Response::HTTP_OK);
        } else {
            return new JsonResponse(null, Response::HTTP_NOT_FOUND);
        }
    }

    #[Route('api', name: 'app_create', methods: ['POST'])]
    public function CreateUser(Request $request, SerializerInterface $serialzer, EntityManagerInterface $em, ValidatorInterface $validator): JsonResponse
    {

        $user = $serialzer->deserialize($request->getContent(), User::class, 'json');
        $errors = $validator->validate($user);
        if ($errors->count() > 0) {
            return new JsonResponse($serialzer->serialize($errors, 'json'), JsonResponse::HTTP_BAD_REQUEST, [], true);
        }
        $user->setRole('ROLE_User');
        $em->persist($user);
        $em->flush();
        //retourner le user crée
        $userjson = $serialzer->serialize($user, 'json', ['groups' => 'show_product']);
        return new JsonResponse($userjson, Response::HTTP_CREATED, [], true);
    }
}
