<?php

namespace Up\Gateway\Persistence;

use Doctrine\DBAL\DriverManager;
use Doctrine\DBAL\Exception;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Exception\MissingMappingDriverImplementation;
use Doctrine\ORM\ORMSetup;
use Doctrine\Common\EventManager;
use Symfony\Component\Cache\Adapter\ArrayAdapter;
use Gedmo\Timestampable\TimestampableListener;

class DatabaseFactory implements IDatabase
{
    /**
     * @var EntityManager
     */
    private EntityManager $entityManager;

    /**
     * @throws MissingMappingDriverImplementation
     * @throws Exception
     */
    public function getConnection(): EntityManager
    {
        $paths = ['../Core/Domain/Entities'];

        $dbParams = [
            'driver'   => 'pdo_mysql',
            'host'     => $_ENV['MYSQL_HOST'],
            'user'     => $_ENV['MYSQL_USER'],
            'password' => $_ENV['MYSQL_PASSWORD'],
            'dbname'   => $_ENV['MYSQL_DATABASE'],
        ];

        $config = ORMSetup::createAnnotationMetadataConfiguration($paths, true);

        $cache = new ArrayAdapter(1);
        $config->setQueryCache($cache);
        $config->setResultCache($cache);
        $config->setMetadataCache($cache);

        $connection = DriverManager::getConnection($dbParams, $config);

        $evm = new EventManager();
        $timestampableListener = new TimestampableListener();
        $evm->addEventSubscriber($timestampableListener);

        $this->entityManager = new EntityManager($connection, $config, $evm);

        return $this->entityManager;
    }

    public function getRepository(string $class): EntityRepository
    {
        return $this->entityManager->getRepository($class);
    }
}
