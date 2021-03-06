<?php

namespace App\Repository;

use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method User|null find($id, $lockMode = null, $lockVersion = null)
 * @method User|null findOneBy(array $criteria, array $orderBy = null)
 * @method User[]    findAll()
 * @method User[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, User::class);
    }

    public function findListQuestion(User $user)
    {
        $dql= "SELECT q,s
                FROM App\Entity\Question q
                JOIN q.User s
                where q.User= :user
                 ORDER BY q.id DESC";
        $query = $this->getEntityManager()->createQuery($dql);
        $query->setParameter(":user",$user);
        $query->setMaxResults(200);
        $question= $query->getResult();
        return $question;
    }

    public function findListMessage(User $user)
    {
        $dql= "SELECT q,s
                FROM App\Entity\Message q
                JOIN q.User s
                where q.User= :user
                 ORDER BY q.id DESC";
        $query = $this->getEntityManager()->createQuery($dql);
        $query->setParameter(":user",$user);
        $query->setMaxResults(200);
        $question= $query->getResult();
        return $question;
    }

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

    public function findListQuestionQB()
    {
        $qb =$this->createQueryBuilder('q');
        $qb->andWhere('q.status = :status');
        $qb->orderBy('q.dateCreated', 'DESC');
        $qb->join('q.user_id','s');
        $qb->addSelect('s');
        $qb->setParameter('status','deting');
        $qb->setFirstResult(0);
        $qb->setMaxResults(200);
        $query = $qb->getQuery();
        $question = $query->getResult();
        return $question;
    }

    // /**
    //  * @return User[] Returns an array of User objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('u')
            ->andWhere('u.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('u.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?User
    {
        return $this->createQueryBuilder('u')
            ->andWhere('u.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
