<?php

namespace App;

use Doctrine\ORM\EntityRepository;

class UserTest extends TestCase
{
    /** @var EntityRepository */
    private $repository;

    public function testCanCreateEntity()
    {
        $user = $this->createEntity();

        echo $user;
        self::assertNotNull($user);
    }

    public function testCanRetrieveEntity()
    {
        $user = $this->createEntity("Bar");

        /** @var User $sut */
        $sut = $this->repository->find($user->getId());

        echo $sut;
        self::assertEquals("Bar", $sut->getName());
    }

    // Helpers

    private function createEntity(string $name = null)
    {
        $user = User::of($name ?: "Foo");
        $this->em->persist($user);
        $this->em->flush();

        return $user;
    }

    // Setup
    protected function setUp(): void
    {
        parent::setUp();
        $this->repository = $this->em->getRepository(User::class);
    }
}
