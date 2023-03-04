<?php

namespace App\Repository;

use App\Entity\Pregunta;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Pregunta>
 *
 * @method Pregunta|null find($id, $lockMode = null, $lockVersion = null)
 * @method Pregunta|null findOneBy(array $criteria, array $orderBy = null)
 * @method Pregunta[]    findAll()
 * @method Pregunta[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PreguntaRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Pregunta::class);
    }

    public function save(Pregunta $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Pregunta $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

}
