<?php

namespace App\Controller;

use App\Repository\TopicRepository;
use App\Repository\ReportRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ForumController extends AbstractController
{
    /**
     * @Route("/", name="forum")
     */
    public function index(EntityManagerInterface $em): Response
    {
        $user = $this->getUser();
        
        $conn = $em->getConnection();
        $sql = '
        SELECT t.id, t.user_id as userId, t.title, t.content, t.created_at as createdAt, t.updated_at as updatedAt, r.topic_id as topicId, is_reported as isReported FROM topic t
        LEFT JOIN report r ON t.id = r.topic_id
        ';
        $stmt = $conn->prepare($sql);
        $stmt->execute();
        $topics = $stmt->fetchAllAssociative();

        return $this->render('forum/index.html.twig', [
            'topics' => $topics,
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
