<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Etats
 *
 * @ORM\Table(name="etats")
 * @ORM\Entity
 */
class Etats
{
    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=50, nullable=true)
     */
    private $name;

    /**
     * @var integer
     *
     * @ORM\Column(name="id_etat", type="smallint")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $idEtat;

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * @return int
     */
    public function getIdEtat()
    {
        return $this->idEtat;
    }

    /**
     * @param int $idEtat
     */
    public function setIdEtat($idEtat)
    {
        $this->idEtat = $idEtat;
    }
}

