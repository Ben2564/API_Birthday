<?php

namespace App\Controller;

use App\Entity\Birthday;
use App\Repository\BirthdayRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;

/**
    * @Route("/api")
*/
class BirthdayController extends AbstractController
{
    /**
     * @Route("/birthday", name="app_get_birthday", methods= {"GET"})
    */
    public function getBirthday(EntityManagerInterface $entityManager, SerializerInterface $serializer): JsonResponse
    {
        $user = $this->getUser();
        $birthList = $serializer->serialize($entityManager->getRepository(Birthday::class)->findBy(['user' => $user]), 'json');
        return new JsonResponse($birthList, Response::HTTP_OK, ['accept' => 'json'], true);
    }

    /**
     * @Route("/birthday/{id}", name="app_get_birthday_one", methods= {"GET"})
    */
    public function getBirthdayId(BirthdayRepository $birthdayRepository, SerializerInterface $serializer, int $id): JsonResponse
    {
        $user = $this->getUser();
        $birthList = $serializer->serialize($birthdayRepository->findOneBy(['user' => $user, 'id' => $id]), 'json');
        return new JsonResponse($birthList, Response::HTTP_OK, ['accept' => 'json'], true);
    }

    /**
     * @Route("/birthday/{id}", name="app_del_birthday", methods= {"DELETE"})
    */
    public function delBirthdayId( SerializerInterface $serializer, int $id): JsonResponse
    {
        $user = $this->getUser();
        $em = $this->getDoctrine()->getManager();
        $birthday = $em->getRepository(Birthday::class)->findOneBy(['user' => $user, 'id' => $id]);
        $em->remove($birthday);
        $em->flush();
        return new JsonResponse($serializer->serialize($birthday, 'json'), Response::HTTP_OK, ['accept' => 'json'], true);
    }

    /**
     * @Route("/birthday", name="app_post_birthday", methods= {"POST"})
    */
    public function postBirthdayId(Request $request, SerializerInterface $serializer): JsonResponse
    {
        $user = $this->getUser();
        $birthday = new Birthday();
        $data = json_decode($request->getContent(),true);
        $birthday->setName($data["name"]);
        $birthday->setBirthday(new \DateTime($data["birthday"]));
        $birthday->setUser($user);
        $em = $this->getDoctrine()->getManager();
        $em->persist($birthday);
        $em->flush();
        return new JsonResponse($serializer->serialize($birthday, 'json'), Response::HTTP_OK, ['accept' => 'json'], true);
    }

    /**
     * @Route("/birthday/{id}", name="app_patch_birthday", methods= {"PATCH"})
    */
    public function patchBirthdayId(BirthdayRepository $birthdayRepository, Request $request, SerializerInterface $serializer, int $id): JsonResponse
    {
        $birthday = $birthdayRepository->findOneBy(['id' => $id]);
        $user = $this->getUser();
        $data = json_decode($request->getContent(),true);
        if(isset($data["name"])){
            $birthday->setName($data["name"]);
        }
        if(isset($data["birthday"])){
            $birthday->setBirthday(new \DateTime($data["birthday"]));
        }
        $em = $this->getDoctrine()->getManager();
        $em->flush();
        return new JsonResponse($serializer->serialize($birthday, 'json'), Response::HTTP_OK, ['accept' => 'json'], true);
    }
}
