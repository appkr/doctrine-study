## Doctrine study

- https://www.doctrine-project.org/projects/doctrine-orm/en/2.6/tutorials/getting-started.html

```
+----------------+       +------------+       +------------+
| User           | <>--> | Bug        | <>--> | Product    |
+----------------+       +------------+ <--<> +------------+
| - reportedBugs |       | - products |
| - assignedBugs |       | - engineer |
+----------------+       | - reporter |
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
