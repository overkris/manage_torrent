<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * FilesInDownload
 *
 * @ORM\Table(name="files_in_download", indexes={@ORM\Index(name="FK_files_in_download_etats", columns={"etat"})})
 * @ORM\Entity
 */
class FilesInDownload
{
    /**
     * @var string
     *
     * @ORM\Column(name="hash_name", type="string", length=50, nullable=true)
     */
    private $hashName;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="start_date", type="datetime", nullable=true)
     */
    private $startDate;

    /**
     * @var integer
     *
     * @ORM\Column(name="nb_error", type="smallint", nullable=true)
     */
    private $nbError;

    /**
     * @var integer
     *
     * @ORM\Column(name="nb_k_dl", type="integer", nullable=true)
     */
    private $nbKDl;

    /**
     * @var integer
     *
     * @ORM\Column(name="percent", type="smallint", nullable=true)
     */
    private $percent;

    /**
     * @var string
     *
     * @ORM\Column(name="speed", type="string", length=50, nullable=true)
     */
    private $speed;

    /**
     * @var string
     *
     * @ORM\Column(name="time_left", type="string", length=50, nullable=true)
     */
    private $timeLeft;

    /**
     * @var integer
     *
     * @ORM\Column(name="id_download", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $idDownload;

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
     * @return string
     */
    public function getHashName()
    {
        return $this->hashName;
    }

    /**
     * @param string $hashName
     */
    public function setHashName($hashName)
    {
        $this->hashName = $hashName;
    }

    /**
     * @return \DateTime
     */
    public function getStartDate()
    {
        return $this->startDate;
    }

    /**
     * @param \DateTime $startDate
     */
    public function setStartDate($startDate)
    {
        $this->startDate = $startDate;
    }

    /**
     * @return int
     */
    public function getNbError()
    {
        return $this->nbError;
    }

    /**
     * @param int $nbError
     */
    public function setNbError($nbError)
    {
        $this->nbError = $nbError;
    }

    /**
     * @return int
     */
    public function getNbKDl()
    {
        return $this->nbKDl;
    }

    /**
     * @param int $nbKDl
     */
    public function setNbKDl($nbKDl)
    {
        $this->nbKDl = $nbKDl;
    }

    /**
     * @return int
     */
    public function getPercent()
    {
        return $this->percent;
    }

    /**
     * @param int $percent
     */
    public function setPercent($percent)
    {
        $this->percent = $percent;
    }

    /**
     * @return string
     */
    public function getSpeed()
    {
        return $this->speed;
    }

    /**
     * @param string $speed
     */
    public function setSpeed($speed)
    {
        $this->speed = $speed;
    }

    /**
     * @return string
     */
    public function getTimeLeft()
    {
        return $this->timeLeft;
    }

    /**
     * @param string $timeLeft
     */
    public function setTimeLeft($timeLeft)
    {
        $this->timeLeft = $timeLeft;
    }

    /**
     * @return int
     */
    public function getIdDownload()
    {
        return $this->idDownload;
    }

    /**
     * @param int $idDownload
     */
    public function setIdDownload($idDownload)
    {
        $this->idDownload = $idDownload;
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

