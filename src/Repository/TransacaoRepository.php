<?php

namespace App\Repository;

use App\Entity\Transacao;
use App\Entity\Usuario;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Collections\Order;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Transacao>
 */
class TransacaoRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Transacao::class);
    }



    /**
         * @return ?Usuario
         */
        public function findByUsuarioId($id_conta): ?Usuario
{
    return $this->createQueryBuilder('t')
        ->where('t.id = :id')
        ->setParameter('id', $id_conta)
        ->getQuery()
        ->getOneOrNullResult();
}


        public function findByContaOrigemOrContaDestino($id_conta): ?Usuario
{
    return $this->createQueryBuilder('t')
        ->where('t.contaOrigem = :id')
        ->orWhere('t.contaDestino = :id')
        ->setParameter('id', $id_conta)
        ->orderBy('t.daHora', 'DESC')
        ->getQuery()
        ->getOneOrNullResult();
}


    //    /**
    //     * @return Transacao[] Returns an array of Transacao objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('t')
    //            ->andWhere('t.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('t.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?Transacao
    //    {
    //        return $this->createQueryBuilder('t')
    //            ->andWhere('t.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
