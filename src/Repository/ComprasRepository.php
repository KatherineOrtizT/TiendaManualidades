<?php

namespace App\Repository;

use App\Entity\Compras;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Compras>
 *
 * @method Compras|null find($id, $lockMode = null, $lockVersion = null)
 * @method Compras|null findOneBy(array $criteria, array $orderBy = null)
 * @method Compras[]    findAll()
 * @method Compras[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ComprasRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Compras::class);
    }

    public function save(Compras $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Compras $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }


    /**
    * Devuelve el número de ventas realizadas hoy.
    * @return int
    */
    public function getVentasHoy(): int
    {
        return $this->createQueryBuilder('c')
        ->select('COUNT(c.id)')
        ->join('c.idPedido', 'p')
        ->where('p.fecha = :fecha_actual')
        ->setParameter('fecha_actual', new \DateTime())
        ->getQuery()
        ->getSingleScalarResult();
    }


    /**
    * Devuelve el número de ventas total.
    * @return int
    */
    public function getTotalVentas(): int
    {
        return $this->createQueryBuilder('c')
            ->select('COUNT(c.id)')
            ->getQuery()
            ->getSingleScalarResult();
    }


    /**
     * @return float
     */
    public function getIngresosHoy(): float
    {
        return (float) $this->createQueryBuilder('c')
        ->select('SUM(c.unidades * c.precio_compra)')
        ->join('c.idPedido', 'p')
        ->where('p.fecha = :fecha')
        ->setParameter('fecha', new \DateTime())
        ->getQuery()
        ->getSingleScalarResult();
    }


    /**
     * @return float
     */
    public function getTotalIngresos(): float
    {
        return (float) $this->createQueryBuilder('c')
            ->select('SUM(c.unidades * c.precio_compra)')
            ->getQuery()
            ->getSingleScalarResult();
    }

}
