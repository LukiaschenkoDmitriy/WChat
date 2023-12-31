<?php

namespace App\Repository;

use App\Entity\ChatFile;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<ChatFile>
 *
 * @method ChatFile|null find($id, $lockMode = null, $lockVersion = null)
 * @method ChatFile|null findOneBy(array $criteria, array $orderBy = null)
 * @method ChatFile[]    findAll()
 * @method ChatFile[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ChatFileRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ChatFile::class);
    }

//    /**
//     * @return ChatFile[] Returns an array of ChatFile objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('c')
//            ->andWhere('c.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('c.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?ChatFile
//    {
//        return $this->createQueryBuilder('c')
//            ->andWhere('c.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
