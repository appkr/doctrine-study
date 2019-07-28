<?php

namespace App;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Tools\Setup;

class TestCase extends \PHPUnit\Framework\TestCase
{
    /** @var EntityManagerInterface */
    protected $em;

    /** @var EntityRepository */
    protected $repository;

    protected function setUp(): void
    {
        parent::setUp();
        $this->em = $this->createEntityManager();
        $this->repository = $this->em->getRepository(Product::class);
    }

    private function createEntityManager()
    {
        $isDevMode = true;
        $config = Setup::createAnnotationMetadataConfiguration([__DIR__ . "/src"], $isDevMode);
        $conn = [
            'driver' => 'pdo_sqlite',
            'path' => __DIR__ . '/../db.sqlite',
        ];

        return EntityManager::create($conn, $config);
    }
}