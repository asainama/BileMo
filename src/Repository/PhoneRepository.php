<?php

namespace App\Repository;

use App\Entity\Phone;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Phone|null find($id, $lockMode = null, $lockVersion = null)
 * @method Phone|null findOneBy(array $criteria, array $orderBy = null)
 * @method Phone[]    findAll()
 * @method Phone[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PhoneRepository extends AbstractRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Phone::class);
    }

    public function search($term, $order = 'desc', $limit = 5, $offset = 1, $page = 1)
    {
        $qb = $this
            ->createQueryBuilder('p')
            ->select('p')
            ->orderBy('p.brand', $order);

        if ($term) {
            $qb
                ->where('p.brand LIKE ?1')
                ->setParameter(1, "%$term%");
        }

        return $this->paginate($qb, $limit, $offset, $page);
    }
}
