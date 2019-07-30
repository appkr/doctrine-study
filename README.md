## Doctrine study

- https://www.doctrine-project.org/projects/doctrine-orm/en/2.6/tutorials/getting-started.html
- https://www.youtube.com/watch?v=SsBKbLOMbFE&list=PL9CoITZ7umdsKdTm--jgmR0peMvaVLcBM

> Doctrine will ONLY check the owning side for changes
> - Owning side: Parent `@OneToMany(targetEntity="", mappedBy="", cascade={""})`
> - Inverse side: Child

```
+----------------+       +------------+       +------------+
| User           | <>--> | Bug        | <>--> | Product    |
+----------------+       +------------+ <--<> +------------+
| - reportedBugs |       | - products |
| - assignedBugs |       | - engineer |
+----------------+       | - reporter |
                         +------------+
```

### Entity Lifecycle

- detached Entity will be inserted again when we call EntityManager.persist()
- To make the entity state to managed again, EntityManager.persist() should be used
```
$entity = $repository->find(1); // managed
$em->detach($entity);           // detached
$em->persist();
```

```
+------------+
| Entity Mgr |
| +--------+ | detach() -> +--------+
| | Entity | |             | Entity |
| +--------+ | <- merge()  +--------+
+------------+
```

### Worflow

Install doctrine orm
```bash
composer require doctrine/orm
```

Scaffold folder structure
```
.
├── src
└── tests
```

Setup doctrine
- https://www.doctrine-project.org/projects/doctrine-orm/en/2.6/tutorials/getting-started.html#obtaining-the-entitymanager
- https://www.doctrine-project.org/projects/doctrine-orm/en/2.6/tutorials/getting-started.html#generating-the-database-schema

Create entity

Apply database schema
```bash
vendor/bin/doctrine orm:schema-tool:update --force --dump-sql
```
