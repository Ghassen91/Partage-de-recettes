<?php

namespace App\Repository;

use App\DTO\RecetteCriteria;
use App\Entity\Recette;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Recette>
 *
 * @method Recette|null find($id, $lockMode = null, $lockVersion = null)
 * @method Recette|null findOneBy(array $criteria, array $orderBy = null)
 * @method Recette[]    findAll()
 * @method Recette[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class RecetteRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Recette::class);
    }

    public function save(Recette $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Recette $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }


    /**
     * Permet de récupérer les 25 dernières recettes
     */
    public function findLatest(): array
    {
        return $this
            ->createQueryBuilder('recette')
            ->setMaxResults(25)
            ->orderBy('recette.createdAt', 'DESC')
            ->getQuery()
            ->getResult();
    }


    /**
     * permet de rechercher des recettes par critères de recherche
     */
    public function findAllByCriteria(RecetteCriteria $criteria): array
    {
        //on crée le query builder, on pagine, on limite et on trie par ordre décroissant
        $qb = $this
            ->createQueryBuilder('recette')
            ->orderBy("recette.{$criteria->orderBy}", $criteria->direction)
            ->setMaxResults($criteria->limit)
            ->setFirstResult($criteria->limit * ($criteria->page - 1));

        //s'il y a recherche par nom
        if($criteria->titre){
            $qb
                ->andWhere('recette.titre LIKE :titre')
                ->setParameter('titre', "%{$criteria->titre}%");
        }

        //s'il y a recherche par utilisateur
        if($criteria->user){
            $qb
                ->leftJoin('recette.user', 'user')
                ->andWhere("CONCAT(user.firstname, CONCAT(' ', CONCAT(user.lastname))) LIKE :user")
                ->setParameter('user' , "%{$criteria->user}%");
        }

        //Je retourne le résultat
        return $qb->getQuery()->getResult();
    }

//    /**
//     * @return Recette[] Returns an array of Recette objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('r')
//            ->andWhere('r.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('r.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Recette
//    {
//        return $this->createQueryBuilder('r')
//            ->andWhere('r.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}