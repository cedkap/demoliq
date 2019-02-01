<?php

namespace App\Controller;

use App\Entity\Question;
use App\Entity\Message;
use App\Form\QuestionType;
use App\Form\MessageType;

use App\Repository\QuestionRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class QuestionController extends AbstractController
{

    /**
     * @Route(
     *     "/questions",
     *     name="question_list",
     *      methods={"GET"}
     *     )
     */

    public function list()
    {
        //select * from question where status = 'deting' order by supports desc limit 1000
        $questionRepository = $this->getDoctrine()->getRepository(Question::class);
        $question = $questionRepository->findListQuestionQB();
        /*$question = $questionRepository->findBy(
            ['status' => 'deting'], //where
            ['id' => 'DESC'],// order by
            1000,//limit
            0
        );*/
        //$question = $questionRepository->findAll();
        return $this->render('question/list.html.twig',['question'=>$question]);
    }

    /**
     * @Route("/questions/{id}", name="question_detail", requirements ={"id":"\d+"}, methods={"GET","POST"})
     *
     */

    public function details($id,Request $request)
    {
        $questionRepository = $this->getDoctrine()->getRepository(Question::class);
       // $question = $questionRepository->findOneBy(["id" =>$id]);
        $question = $questionRepository->find($id);
        if (!$question){
            throw  $this->createNotFoundException("Cette question n'existe pas");
        }

        //lister les messages
        $MessageRepository = $this->getDoctrine()->getRepository(Message::class);
        //$messageview = $MessageRepository->findAll();
     //   $messageview= $MessageRepository->findListQuestion();
        $messageview = $MessageRepository->findBy(['question'=>$question],['id' => 'DESC']);
        //Formulaire du messages
        //$question = new Question();
        $message =  new Message();
        //creation de l'association
        $message->setQuestion($question);
        //relation avec User
        $message->setUser($this->getUser());
        //creation des formulaire
        $messageForm = $this->createForm(MessageType::class,$message);
        //$questionForm  = $this->createForm(QuestionType::class,$question);
        //$questionForm->handleRequest($request);
        ;
        $messageForm->handleRequest($request);
        if ( $messageForm->isSubmitted()&& $messageForm->isValid()){
            $em= $this->getDoctrine()->getManager();
            $em-> persist($message);
            $em->flush();
            $this->addFlash('success','merci de votre contribution');
            //rediriger la page
            return $this->redirectToRoute('question_detail',['id'=>$question->getId()]);
        }

        //display
        return $this->render('question/details.html.twig',['question'=>$question,'message'=>$messageview,'messageFrom'=>$messageForm->createView()]);
    }

    /**
     * @Route("/questions/ajouter", name="question_add", methods={"GET","POST"})
     *
     */

    public function create(Request $request)
    {
        /*if (!$this->isGranted("ROLE_USER")){
            throw $this->createAccessDeniedException("Degage");
        }*/
       // $this->denyAccessUnlessGranted("ROLE_USER");*/

        $question = new Question();
        //Parametre du ID
        $question->setUser($this->getUser());
        $questionForm  = $this->createForm(QuestionType::class,$question);
        $questionForm->handleRequest($request);

        if ($questionForm ->isSubmitted() && $questionForm->isValid()){
            $em = $this->getDoctrine()->getManager();
            $em->persist($question);
            $em->flush();
            //
            $this->addFlash('success','merci pour votre ....');
            //rediriger la page
            return $this->redirectToRoute('question_detail',['id'=>$question->getId()]);
        }
        return $this->render('question/create.html.twig',['questionFrom'=>$questionForm->createView()]);
    }

    /**
     * @Route(
     *     "/messagelist",
     *     name="message_list",
     *      methods={"GET"}
     *     )
     */

    public function listMessage()
    {

    }

}
