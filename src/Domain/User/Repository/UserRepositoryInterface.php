<?php

namespace App\Domain\User\Repository;

use App\Domain\User\User;

interface UserRepositoryInterface
{
    public function save(User $entity, bool $flush = false): void;

    public function remove(User $entity, bool $flush = false): void;

    public function find($id, $lockMode = null, $lockVersion = null);

    public function findBy(array $criteria, ?array $orderBy = null, $limit = null, $offset = null);
}