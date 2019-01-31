<?php

namespace App\Repository;

use App\Entity\Question;
use App\Entity\Sujet;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Question|null find($id, $lockMode = null, $lockVersion = null)
 * @method Question|null findOneBy(array $criteria, array $orderBy = null)
 * @method Question[]    findAll()
 * @method Question[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class QuestionRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Question::class);
    }
    public function findListQuestion()
    {
        $dql= "SELECT q,s,m
                FROM App\Entity\Question q
                JOIN q.Sujet s
                LEFT JOIN q.message m
                where q.status= :statut 
                 ORDER BY q.id DESC";
        $query = $this->getEntityManager()->createQuery($dql);
        $query->setMaxResults(200);
        $question= $query->getResult();
        return $question;
    }

    public function findListQuestionQB()
    {
        $qb =$this->createQueryBuilder('q');
        $qb->andWhere('q.status = :status');
        $qb->orderBy('q.dateCreated', 'DESC');
        $qb->join('q.Sujet','s');
        $qb->addSelect('s');
        $qb->setParameter('status','deting');
        $qb->setFirstResult(0);
        $qb->setMaxResults(200);
        $query = $qb->getQuery();
        $question = $query->getResult();
        return $question;
    }

    // /**
    //  * @return Question[] Returns an array of Question objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('q')
            ->andWhere('q.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('q.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Question
    {
        return $this->createQueryBuilder('q')
            ->andWhere('q.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
