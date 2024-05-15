<?php

namespace App\Controller;

use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

/**
    * @Route("/api")
*/
class UserController extends AbstractController
{
    /**
     * @Route("/register", name="app_user", methods= {"POST"})
     */
    public function index(Request $request, SerializerInterface $serializer, UserPasswordHasherInterface $passwordHasher): JsonResponse
    {
        $user = new User();
        $data = json_decode($request->getContent(),true);
        $user->setEmail($data["email"]);
        $hashedPassword = $passwordHasher->hashPassword(
            $user,
            $data["password"]
        );
        $user->setPassword($hashedPassword);
        $em = $this->getDoctrine()->getManager();
        $em->persist($user);
        $em->flush();
        return new JsonResponse(json_encode("{'connexion':'validé'}"), Response::HTTP_OK, ['accept' => 'json'], true);
    }
}
