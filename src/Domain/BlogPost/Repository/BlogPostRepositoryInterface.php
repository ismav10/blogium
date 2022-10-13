<?php

namespace App\Domain\BlogPost\Repository;

use App\Domain\BlogPost\BlogPost;

interface BlogPostRepositoryInterface
{
    public function save(BlogPost $entity, bool $flush = false): void;

    public function remove(BlogPost $entity, bool $flush = false): void;

    public function find($id, $lockMode = null, $lockVersion = null);

    public function findBy(array $criteria, ?array $orderBy = null, $limit = null, $offset = null);
}