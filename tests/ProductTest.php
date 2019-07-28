<?php

namespace App;

class ProductTest extends TestCase
{
    public function testCanPersistEntity()
    {
        $this->createEntity();

        self::assertTrue(true);
    }

    public function testRepositoryFindAll()
    {
        $this->createEntity();

        /** @var Product[] $entities */
        $entities = $this->repository->findAll();
        foreach ($entities as $entity) {
            echo $entity;
            self::assertNotNull($entity);
        }
    }

    public function testRepositoryFind()
    {
        /** @var Product $entity */
        $entity = $this->createEntity("Foo");

        echo $this->repository->find($entity->getId());
        self::assertEquals("Foo", $entity->getName());
    }

    // Helper

    private function createEntity(string $title = null)
    {
        $product = Product::of($title ?: "product 1");
        $this->em->persist($product);
        $this->em->flush();

        return $product;
    }
}
