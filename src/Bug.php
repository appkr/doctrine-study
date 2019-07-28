<?php

namespace App;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\GeneratedValue;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\ManyToMany;
use Doctrine\ORM\Mapping\ManyToOne;
use Doctrine\ORM\Mapping\Table;

/**
 * @Entity
 * @Table(name="bugs")
 */
class Bug
{
    /**
     * @Id()
     * @Column(type="integer")
     * @GeneratedValue()
     */
    private $id;

    /**
     * @Column(type="string")
     * @var string
     */
    private $description;

    /**
     * @Column(type="string")
     * @var string
     */
    private $status;

    /**
     * @Column(type="datetimetz")
     * @var \DateTimeImmutable
     */
    private $created;

    /**
     * @ManyToOne(targetEntity="User", inversedBy="reportedBugs")
     * @var User
     */
    private $reporter;

    /**
     * @ManyToOne(targetEntity="User", inversedBy="assignedBugs")
     * @var User
     */
    private $engineer;

    /**
     * @ManyToMany(targetEntity="Product")
     * @var Product[]
     */
    private $products;

    public function __construct()
    {
        $this->products = new ArrayCollection();
    }

    public static function of(string $description)
    {
        $instance = new static();
        $instance->description = $description;
        $instance->status = "OPEN";
        $instance->created = new \DateTimeImmutable("now");

        return $instance;
    }

    public function getId()
    {
        return $this->id;
    }

    public function setId($id)
    {
        $this->id = $id;
    }

    public function getDescription()
    {
        return $this->description;
    }

    public function setDescription($description)
    {
        $this->description = $description;
    }

    public function getStatus()
    {
        return $this->status;
    }

    public function setStatus($status)
    {
        $this->status = $status;
    }

    public function close()
    {
        $this->status = "CLOSE";
    }

    public function getCreated()
    {
        return $this->created;
    }

    public function setCreated($created)
    {
        $this->created = $created;
    }

    public function getReporter()
    {
        return $this->reporter;
    }

    public function setReporter(User $reporter)
    {
        $reporter->addReportedBug($this);
        $this->reporter = $reporter;
    }

    public function getEngineer()
    {
        return $this->engineer;
    }

    public function setEngineer(User $engineer)
    {
        $engineer->assignToBug($this);
        $this->engineer = $engineer;
    }

    public function getProducts()
    {
        return $this->products;
    }

    public function assignToProduct(Product $product)
    {
        $this->products[] = $product;
    }

    public function __toString()
    {
        $productString = array_reduce($this->products->toArray(), function (string $carry, Product $p) {
            return "{$carry}{$p}".PHP_EOL;
        }, "");

        return <<<EOT
Bug {
    id: {$this->id},
    description: {$this->description},
    status: {$this->status},
    created: {$this->created->format(DATE_ISO8601)},
    reporter: {$this->reporter},
    engineer: {$this->engineer},
    products: [{$productString}]
}
EOT;
    }
}