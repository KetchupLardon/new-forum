<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Repository\TopicRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ForumController extends AbstractController
{
    /**
     * @Route("/", name="forum")
     */
    public function index(TopicRepository $topicRepository): Response
    {
        $user = $this->getUser();
        return $this->render('forum/index.html.twig', [
            'topics' => $topicRepository->findAll(),
            "user" => $user,
        ]);
    }

    /**
     * @Route("/ajouter", name="ajouter")
     */
    public function topicForum(): Response
    {
        return $this->render('forum/topic-form.html.twig');
    }

    /**
     * @Route("/topic/2", name="topic_show")
     */
    public function show(): Response
    {
        return $this->render('forum/topic-form.html.twig');
    }
}
