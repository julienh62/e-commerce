<?php

namespace App\Repository;

use App\Entity\Products;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Products>
 *
 * @method Products|null find($id, $lockMode = null, $lockVersion = null)
 * @method Products|null findOneBy(array $criteria, array $orderBy = null)
 * @method Products[]    findAll()
 * @method Products[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ProductsRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Products::class);
    }

    public function save(Products $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Products $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function findProductsPaginated(int $page, string $slug, int $limit = 6): array
    // si pas de limit ce sera 6 résultzts qui sont envoyés
    // limit est le nbre d'article maxi par page
    {
       $limit = abs($limit);
       //abs valeur absolu pour limit tjrs>0

       $result = [];

       $query = $this->getEntityManager()->createQueryBuilder()
       // c t p category et produit
       //on selectionne ce qu il ya dans category et product
       //de la table product jointe à categories par l'intermédiaire du champ categories
       //qui ont comme categori le slug qu'on lui passera
         ->select('c', 'p')
         ->from('App\Entity\Products', 'p')
         ->join('p.categories', 'c')
         ->where("c.slug = '$slug'")
         ->setMaxResults($limit)
         ->setFirstResult(($page * $limit) - $limit);
        // si 2 articles par page, le 5eme article est sur la page 3

        $paginator = new Paginator($query);
        $data = $paginator->getQuery()->getResult();
        //dd($data);

           // dd($query->getQuery()->getResult());


        // on verifie qu'on a bien des données
        if(empty($data)){
           return $result;
       }
           //on calcule le nombre de pages
           //ceil fait l'arrondi au supérieur
           //paginator cont me donne le nombre de page
          $pages = ceil($paginator->count() / $limit);

    // On remplit le tableau
        $result['data'] = $data;
        $result['pages'] = $pages;
        $result['page'] = $page;
        $result['limit'] = $limit;
       return $result;
    }


//    /**
//     * @return Products[] Returns an array of Products objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('p')
//            ->andWhere('p.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('p.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Products
//    {
//        return $this->createQueryBuilder('p')
//            ->andWhere('p.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
