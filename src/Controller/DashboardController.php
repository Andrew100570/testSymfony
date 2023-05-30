<?php

namespace App\Controller;

use App\Entity\Application;
use App\Form\ApplicationFormType;
use App\Form\CommentFormType;
use App\Repository\ApplicationRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\Comment;


class DashboardController extends AbstractController
{

    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * @Route("/dashboard", name="dashboard")
     */
    public function index(): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        $array = ['admin' => 'ROLE_ADMIN', 'manager' => 'ROLE_MANAGER', 'client' => 'ROLE_CLIENT'];
        $roles = $this->getUser()->getRoles();
        if (!isset($roles)) {
            return $this->render('registration/register.html.twig', [
            ]);
        }
        $id = $this->getUser()->getId();
        foreach ($array as $key => $arr) {
            if (array_search($arr, $roles) !== false) {
                $route = $this->redirectToRoute($key, [
                    'slug' => $id,
                ]);
                if ($key === 'admin') {
                    return $route;
                } elseif ($key === 'manager') {
                    return $route;
                } else {
                    return $route;
                }
            }
        }
    }

    /**
     * @Route("/admin/{slug}", name="admin")
     */
    public function admin($slug): Response
    {
        return $this->content($slug, $param = 'admin');
    }

    /**
     * @Route("/manager/{slug}", name="manager")
     */
    public function manager($slug): Response
    {
        return $this->content($slug, $param = 'manager');
    }

    /**
     * @Route("/client/{slug}", name="client")
     */
    public function client($slug): Response
    {
        return $this->content($slug, $param = 'client');
    }

    public function content($id, $route)
    {
        $form = $this->createForm(ApplicationFormType::class, new Application(), [
            'action' => $this->generateUrl('application_form'),
        ]);


        return $this->render('dashboard/index.html.twig', [
            'id' => $id,
            'name' => $this->getUser()->getName(),
            'role' => $route,
            'formApplication' => $form->createView(),
            'applications' => $this->entityManager->getRepository(Application::class)->findAll(),


        ]);
    }

    #[Route('/application_form', name: 'application_form',methods: ['POST'])]

    /**
     * param Request $request
     * @param EntityManagerInterface $entityManager
     * @return Response
     */
    public function application_form(Request $request, EntityManagerInterface $entityManager)
    {
        $application = new Application();
        $form = $this->createForm(ApplicationFormType::class, $application);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $newName = $request->request->all()['application_form']['username'];
            $newName = explode('/', $newName);

            $application->setUsername('Роль:' . $newName[1] . ';ИД:' . $newName[2]);
            $application->setTimeOfCreation(new \DateTime());

            $path = $this->getParameter('kernel.project_dir') . '/public/uploads';
            $img = $request->files->get('application_form')['filename'];;
            $img->move($path);

            $entityManager->persist($application);
            $entityManager->flush();

        }

        return $route = $this->redirectToRoute($newName[1], [
            'slug' => $this->getUser()->getId(),
            'applications' => $this->entityManager->getRepository(Application::class)->findAll(),

        ]);
    }

    #[Route('/comment_form_post', name: 'comment_form_post',methods: ['POST'])]

    /**
     * param Request $request
     * @param EntityManagerInterface $entityManager
     * @return Response
     */
    public function comment_form_post(Request $request, EntityManagerInterface $entityManager)
    {
        $comment = new Comment();
        $form = $this->createForm(CommentFormType::class, $comment, [
            'action' => $this->generateUrl('comment_form_post'),
        ]);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $comment->setDateOfCreation(new \DateTime());

            $entityManager->persist($comment);
            $entityManager->flush();

        }
        $currentUrl = explode('/', $_SERVER['HTTP_REFERER']);


        return $route = $this->redirectToRoute('comment_form', [
            'slug' => $currentUrl[4],
            'applications' => $this->entityManager->getRepository(Application::class)->findAll(),
            'comments' => $this->entityManager->getRepository(Comment::class)->findAll()
        ]);
    }

    #[Route('/comment_form', name: 'comment_form/{slug}',methods: ['GET'])]

    /**
     * @Route("/comment_form/{slug}", name="comment_form")
     */
    public function comment_form()
    {

        $form = $this->createForm(CommentFormType::class, new Comment(), [
            'action' => $this->generateUrl('comment_form_post'),
        ]);

        $newName = explode('/', $_SERVER['REQUEST_URI']);

        return $this->render('dashboard/comment.html.twig', [
            'application' => $this->entityManager->getRepository(Application::class)->find(($newName[2])),
            'commentForm' => $form->createView(),
            'comments' => $this->entityManager->getRepository(Comment::class)->findAll()
        ]);
    }



}