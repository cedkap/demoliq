<?php

namespace App\Controller;

use App\Form\MessageType;
use http\Message;
use mysql_xdevapi\Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class MessageController extends AbstractController
{
    /**
     * @Route("/message", name="message")
     */
    public function index()
    {
        return $this->render('message/index.html.twig', [
            'controller_name' => 'MessageController',
        ]);
    }


    // lister les messages
    /**
     * @Route(
     *     "/message",
     *     name="message_list",
     *      methods={"GET"}
     *     )
     */

    public function list()
    {
        //select * from question where status = 'deting' order by supports desc limit 1000
       // $questionRepository = $this->getDoctrine()->getRepository(Question::class);
        $MessageRepository = $this->getDoctrine()->getRepository(Message::class);
        $message = $MessageRepository->findAll();
        //$question = $questionRepository->findAll();
        return $this->render('question/details.html.twig',['message'=>$message]);
    }
}
