<?php

namespace App\Repository;

use App\Entity\Producto;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Producto>
 *
 * @method Producto|null find($id, $lockMode = null, $lockVersion = null)
 * @method Producto|null findOneBy(array $criteria, array $orderBy = null)
 * @method Producto[]    findAll()
 * @method Producto[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ProductoRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Producto::class);
    }

    public function save(Producto $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Producto $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }


    /**
     * @return Producto[] Returns an array of Producto objects
     */
    public function search($value): array
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.nombre Like :val')
            ->setParameter('val','%'.$value.'%')
            ->orderBy('p.id', 'ASC')
            ->setMaxResults(20)
            ->getQuery()
            ->getResult()
        ;
    }

    /**
     * @return Producto[] Returns an array of Producto objects
     */
    public function findProductsByNovelty(): array
    {
        return $this->createQueryBuilder('p')
            ->where('p.fechaCreacion >= :fecha_limite')
            ->setParameter('fecha_limite', new \DateTime('-15 days'))
            ->orderBy('p.fechaCreacion', 'DESC')
            ->setMaxResults(8)
            ->getQuery()
            ->getResult();
    }

    /**
     * @return Producto[] Returns an array of Producto objects
     */
    public function findProductsByDiscount(): array
    {
        return $this->createQueryBuilder('p')
            ->orderBy('p.discount', 'DESC')
            ->setMaxResults(8)
            ->getQuery()
            ->getResult();
    }


    /**
     * @return Producto[] Returns an array of Producto objects
     */
    public function findTopSellingProducts(): array
    {
        return $this->createQueryBuilder('p')
            ->select('p, COUNT(c.id) AS HIDDEN sold')
            ->leftJoin(Compras::class, 'c', 'WITH', 'c.idProducto = p.id')
            ->groupBy('p.id')
            ->orderBy('sold', 'DESC')
            ->setMaxResults(8)
            ->getQuery()
            ->getResult();
    }

}
