<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * TorrentInTransmission
 *
 * @ORM\Table(name="torrent_in_transmission", indexes={@ORM\Index(name="id_transmission", columns={"id_transmission"}), @ORM\Index(name="FK_torrent_in_transmission_etats", columns={"etat"})})
 * @ORM\Entity
 */
class TorrentInTransmission
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id_transmission", type="integer", nullable=false)
     */
    private $idTransmission;

    /**
     * @var string
     *
     * @ORM\Column(name="hash_string", type="string", length=50, nullable=false)
     */
    private $hashString;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=500, nullable=false)
     */
    private $name;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date_ajout", type="datetime", nullable=false)
     */
    private $dateAjout;

    /**
     * @var string
     *
     * @ORM\Column(name="etat_dl_trans", type="string", length=50, nullable=true)
     */
    private $etatDlTrans;

    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var \AppBundle\Entity\Etats
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Etats")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="etat", referencedColumnName="id_etat")
     * })
     */
    private $etat;

    /**
     * @return int
     */
    public function getIdTransmission()
    {
        return $this->idTransmission;
    }

    /**
     * @param int $idTransmission
     */
    public function setIdTransmission($idTransmission)
    {
        $this->idTransmission = $idTransmission;
    }

    /**
     * @return string
     */
    public function getHashString()
    {
        return $this->hashString;
    }

    /**
     * @param string $hashString
     */
    public function setHashString($hashString)
    {
        $this->hashString = $hashString;
    }

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
     * @return \DateTime
     */
    public function getDateAjout()
    {
        return $this->dateAjout;
    }

    /**
     * @param \DateTime $dateAjout
     */
    public function setDateAjout($dateAjout)
    {
        $this->dateAjout = $dateAjout;
    }

    /**
     * @return string
     */
    public function getEtatDlTrans()
    {
        return $this->etatDlTrans;
    }

    /**
     * @param string $etatDlTrans
     */
    public function setEtatDlTrans($etatDlTrans)
    {
        $this->etatDlTrans = $etatDlTrans;
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param int $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @return Etats
     */
    public function getEtat()
    {
        return $this->etat;
    }

    /**
     * @param Etats $etat
     */
    public function setEtat($etat)
    {
        $this->etat = $etat;
    }
}

