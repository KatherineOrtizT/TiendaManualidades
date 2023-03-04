<?php

namespace App\Repository;

use App\Entity\Pedidos;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Pedidos>
 *
 * @method Pedidos|null find($id, $lockMode = null, $lockVersion = null)
 * @method Pedidos|null findOneBy(array $criteria, array $orderBy = null)
 * @method Pedidos[]    findAll()
 * @method Pedidos[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PedidosRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Pedidos::class);
    }

    public function save(Pedidos $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Pedidos $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }


    public function ultimosCincoPedidos(): array
    {
        return $this->createQueryBuilder('p')
            ->select('p.id as id_pedido, p.fecha, u.Nombre, u.Apellidos, SUM(c.unidades * c.precio_compra) as importe, COUNT(c.id) as tiene_compras')
            ->join('p.idUsuario', 'u')
            ->leftJoin('p.compras', 'c')
            ->groupBy('p.id')
            ->orderBy('p.fecha', 'DESC')
            ->setMaxResults(5)
            ->getQuery()
            ->getResult();
    }

}
