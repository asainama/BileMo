<?php

namespace App\Repository;

use Pagerfanta\Pagerfanta;
use Doctrine\ORM\QueryBuilder;
use Pagerfanta\Doctrine\ORM\QueryAdapter;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;

abstract class AbstractRepository extends ServiceEntityRepository
{
    protected function paginate(QueryBuilder $qb, $limit = 5, $offset = 0, $page)
    {
        if (0 > $limit ||  0 > $offset) {
            throw new \LogicException("$limit & $offset must be greater than 0.");
        }

        $pager = new Pagerfanta(new QueryAdapter($qb));
        $currentPage = ceil(($offset + 1) / $limit);
        $pager
            ->setCurrentPage($currentPage)
            ->setMaxPerPage((int) $limit)
            ->setCurrentPage($page);

        return $pager;
    }
}
