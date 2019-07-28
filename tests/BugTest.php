<?php

namespace App;

use Doctrine\ORM\EntityRepository;

class BugTest extends TestCase
{
    /** @var EntityRepository */
    private $repository;

    public function testCanCreateEntity()
    {
        $product1 = $this->createProduct("product 1");
        $product2 = $this->createProduct("product 2");
        $reporter = $this->createUser("reporter");
        $engineer = $this->createUser("engineer");
        $bug = $this->createBug();

        $bug->assignToProduct($product1);
        $bug->assignToProduct($product2);
        $bug->setReporter($reporter);
        $bug->setEngineer($engineer);

        $this->em->persist($bug);
        $this->em->flush();

        /** @var Bug $sut */
        $sut = $this->repository->find($bug->getId());

        echo $sut;
        self::assertEquals($reporter, $sut->getReporter());
        self::assertEquals($engineer, $sut->getEngineer());
        self::assertEquals($product1, $sut->getProducts()->first());
        self::assertEquals($product2, $sut->getProducts()->last());
    }

    public function testDql()
    {
        /**
         * NOTE.
         * - The trailing semicolon(;) must not be provided
         * - Single quote must be used
         */
        $dql =<<<DQL
SELECT b, e, r
FROM App\\Bug b
JOIN b.engineer e
JOIN b.reporter r
WHERE b.status = 'OPEN'
    AND (e.id = :userId OR r.id = :userId)
ORDER BY b.created DESC
DQL;
        $query = $this->em
            ->createQuery($dql)
            ->setParameter("userId", 10)
            ->setMaxResults(10);

        $bugs = $query->getResult();

        foreach($bugs as $bug) {
            echo $bug;
        }

        self::assertTrue(true);
    }

    public function testQueryBuilder()
    {
        $qb = $this->em->createQueryBuilder();
        $bugs = $qb->select("b, r, e")
            ->from(Bug::class, "b")
            ->join("b.reporter", "r")
            ->join("b.engineer", "e")
            ->orderBy("b.created", "DESC")
            ->setFirstResult(0)
            ->setMaxResults(10)
            ->getQuery()
            ->getResult();

        foreach($bugs as $bug) {
            echo $bug;
        }

        self::assertTrue(true);
    }

    public function testCanUpdateEntity()
    {
        $bug = $this->createBug();

        /** @var Bug $sut */
        $sut = $this->repository->find($bug->getId());
        $sut->close();
        $this->em->flush();

        echo $sut;
        self::assertEquals("CLOSE", $sut->getStatus());
    }

    // Helper

    private function createUser(string $name = null)
    {
        $user = User::of($name ?: "foo");
        $this->em->persist($user);
        $this->em->flush();

        return $user;
    }

    private function createBug(string $description = null)
    {
        $bug = Bug::of($description ?: "bug description");
        $this->em->persist($bug);
        $this->em->flush();

        return $bug;
    }

    private function createProduct(string $name = null)
    {
        $product = Product::of($name ?: "product 1");
        $this->em->persist($product);
        $this->em->flush();

        return $product;
    }

    // Setup

    protected function setUp(): void
    {
        parent::setUp();
        $this->repository = $this->em->getRepository(Bug::class);
    }
}
