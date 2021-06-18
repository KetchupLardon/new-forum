<?php

namespace App\Controller;

use App\Entity\Topic;
use App\Entity\Report;
use App\Entity\Comment;
use App\Form\TopicType;
use App\Form\ReportType;
use App\Form\CommentType;
use App\Repository\ReportRepository;
use App\Repository\CommentRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * @Route("/topic")
 */
class TopicController extends AbstractController
{

    /**
     * @Route("/new", name="topic_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $topic = new Topic();
        $form = $this->createForm(TopicType::class, $topic);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $user = $this->getUser();
            $entityManager = $this->getDoctrine()->getManager();
            $topic->setUser($user);
            $entityManager->persist($topic);
            $entityManager->flush();

            return $this->redirectToRoute('forum');
        }

        return $this->render('topic/new.html.twig', [
            'topic' => $topic,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="topic_show", methods={"GET", "POST"})
     */
    public function show(Topic $topic, Request $request, CommentRepository $commentRepository, ReportRepository $reportRepository): Response
    {
        $user = $this->getUser();
        $comment = new Comment();
        $form = $this->createForm(CommentType::class, $comment);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $comment->setTopic($topic);
            $comment->setUser($user);
            $comment->setUpdatedAt(new \DateTime());
            $entityManager->persist($comment);
            $entityManager->flush();
            return $this->redirect($request->getUri());
        }

        
        $report = new Report();
        $formReport = $this->createForm(ReportType::class, $report);
        $formReport->handleRequest($request);

        if ($formReport->isSubmitted() && $formReport->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $report->setTopic($topic);
            $report->setUser($user);
            $entityManager->persist($report);
            $entityManager->flush();

            return $this->redirectToRoute('forum');
        }
        
        $comments = $commentRepository->findBy([
            "topic" =>$topic
        ]);
        
        $reports = $reportRepository->findOneBy([
            "topic" =>$topic
        ]);

        return $this->render('topic/show.html.twig', [
            'topic' => $topic,
            'form' => $form->createView(),
            'formReport' => $formReport->createView(),
            'comments' => $comments,
            "user" => $user,
            "report" => $reports
        ]);
    }

    /**
     * @Route("/{id}/edit", name="topic_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Topic $topic): Response
    {
        $form = $this->createForm(TopicType::class, $topic);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $topic->setUpdatedAt(new \DateTime());
            $entityManager->flush();

            return $this->redirectToRoute('forum');
        }

        return $this->render('topic/edit.html.twig', [
            'topic' => $topic,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="topic_delete", methods={"POST"})
     */
    public function delete(Request $request, Topic $topic): Response
    {
        if ($this->isCsrfTokenValid('delete'.$topic->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($topic);
            $entityManager->flush();
        }

        return $this->redirectToRoute('forum');
    }
}