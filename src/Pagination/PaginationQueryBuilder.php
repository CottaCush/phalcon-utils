<?php

namespace App\Library;

use Phalcon\Paginator\Adapter\QueryBuilder;

class PaginationQueryBuilder extends QueryBuilder
{
    public function getPaginate()
    {
        $paginate = parent::getPaginate();

        $builder = $this->getQueryBuilder();

        if ($groupBy = $builder->getGroupBy()) {
            $result = $builder->columns("COUNT(DISTINCT " . $groupBy . ") as total")
                ->groupBy(null)
                ->orderBy(null)
                ->getQuery()
                ->execute()
                ->getFirst();

            $paginate->total_items = $result->total;
            $paginate->total_pages = ceil($paginate->total_items / $this->getLimit());
        }

        return $paginate;
    }
}