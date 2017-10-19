<?php

namespace Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Ventbloki
 *
 * @ORM\Table(name="ventbloki")
 * @ORM\Entity
 */
class Ventbloki
{
    /**
     * @var integer
     *
     * @ORM\Column(name="ID", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="Name", type="string", length=15, nullable=false)
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="Seria", type="string", length=5, nullable=false)
     */
    private $seria;

    /**
     * @var integer
     *
     * @ORM\Column(name="b", type="integer", nullable=false)
     */
    private $b;

    /**
     * @var integer
     *
     * @ORM\Column(name="l", type="integer", nullable=false)
     */
    private $l;


}

