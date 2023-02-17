<?php

namespace Up\Gateway\Persistence;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityRepository;

interface IDatabase
{
    public function getConnection(): EntityManager;
    public function getRepository(string $class): EntityRepository;
}
