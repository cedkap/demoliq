<?php

namespace App\Command;

use App\Entity\Message;
use App\Entity\Question;
use App\Entity\Sujet;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;

use mysql_xdevapi\Exception;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class FixturesCommand extends Command
{
    protected static $defaultName = 'app:fixtures';
    protected  $em = null;

    public function __construct(EntityManagerInterface $em,?string $name = null)
    {
        $this->em =$em;
        parent::__construct($name);
    }

    protected function configure()
    {
        $this
            ->setDescription('Add a short description for your command')

        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $io = new SymfonyStyle($input, $output);
        $io ->text("Coucou");
        $io ->text("Loading ...");
        $faker = \Faker\Factory::create('fr_FR');

        $answer = $io->ask("Truncate all table..sure ? yes |no ","no");
        if ($answer != "yes"){
           $io->text("Avboeir");
           die();
        }

        $conn = $this->em->getConnection();
        $conn->query('SET FOREIGN_KEY_CHECKS = 0');
        $conn->query('TRUNCATE question');
        $conn->query('TRUNCATE sujet');
        $conn->query('TRUNCATE question_sujet');
        //
        $conn->query('SET FOREIGN_KEY_CHECKS = 1');
        $subjects = [
            "Affaires étrangères",
            "Affaires européennes",
            "Agriculture, alimentation, pêche",
            "Ruralité",
            "Aménagement du territoire",
            "Économie et finance",
            "Culture",
            "Communication",
            "Défense",
            "Écologie et développement durable",
            "Transports",
            "Logement",
            "Éducation",
            "Intérieur",
            "Outre-mer et collectivités territoriales",
            "Immigration",
            "Justice et Libertés",
            "Travail",
            "Santé",
            "Démocratie"
        ];
        //
        $allSubjects=[];
        foreach ($subjects as $subject){
            $sujet = new Sujet();
            $sujet->setTittle($subject);
            $sujet->setCreatedate(new \DateTime());
            $this->em->persist($sujet);
            //
            $allSubjects[]=$sujet;
        }
        $this->em->flush();

        for ($i=0; $i<10; $i++){
            $question = new Question();
            $question->setTitle($faker->sentence);
            $question->setDescription($faker->realText(200));
            $question->setStatus($faker->randomElement(['deting','voting','closed']));
            $question->setDateCreated($faker->dateTimeBetween('-1 year','now'));
            $question->setSupports($faker->optional(0.5,0)->numberBetween(0,47000000));

            $num = mt_rand(1,3);
            for ($b=0 ;$b<$num; $b++){
                $s = $faker->randomElement($allSubjects);

                    $question->addSujet($s);

            }
            $numMess = mt_rand(1,3);
            for ($b=0 ;$b<10; $b++){
                $message = new Message();
                $message->setContent($faker->realText(200));
                $message->setClaps(10);
                $this->setDateCreated(new \DateTime());
                $this->setIsPublished(1);

            }
            $this->em->persist($question);
        }
        $this->em->flush();
        $io->success("Done");
    }
}
