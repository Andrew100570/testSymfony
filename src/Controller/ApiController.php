<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Application;

class ApiController extends AbstractController
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    #[Route('/claims', name: 'claims', methods: ['GET'])]
    public function index(Request $request): Response
    {
        if ($request->query->get('token') !== 'dplp31qppIkvoxr3lIqsX77BrUrhDhsg9GFk9atO') {
            return JsonResponse::fromJsonString("HTTP_PRECONDITION_FAILED" , JsonResponse::HTTP_PRECONDITION_FAILED );
        }
        $applications = $this->entityManager->getRepository(Application::class)->findAll();

        foreach ($applications as $application) {

            $arr['id'] = $application->getId();
            $arr['discription'] = $application->getDiscription();
            $arr['time_of_creation'] = $application->getTimeOfCreation();
            $arr['lst_edit_time'] = $application->getLstEditTime();
            $arr['username'] = $application->getUsername();
            $arr['filename'] = $application->getFilename();
            $arr['status'] = $application->getStatus();
            $arr['comments'] = $application->getComments();
            $newArr[] = $arr;
        }

        return JsonResponse::fromJsonString(json_encode($newArr));
    }

}
