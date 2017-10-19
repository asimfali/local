<?php

namespace Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Izv
 *
 * @ORM\Table(name="izv")
 * @ORM\Entity
 */
class Izv
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="number_izv", type="string", length=11, nullable=false)
     */
    private $numberIzv;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date", type="date", nullable=false)
     */
    private $date;

    /**
     * @var string
     *
     * @ORM\Column(name="reason", type="string", length=255, nullable=false)
     */
    private $reason;

    /**
     * @var integer
     *
     * @ORM\Column(name="code", type="integer", nullable=false)
     */
    private $code;

    /**
     * @var string
     *
     * @ORM\Column(name="appendix", type="string", length=255, nullable=false)
     */
    private $appendix;

    /**
     * @var string
     *
     * @ORM\Column(name="content_title", type="string", length=80, nullable=false)
     */
    private $contentTitle;

    /**
     * @var string
     *
     * @ORM\Column(name="content", type="text", length=65535, nullable=false)
     */
    private $content;


}

