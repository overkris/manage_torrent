<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * FilesInTransmission
 *
 * @ORM\Table(name="files_in_transmission", indexes={@ORM\Index(name="FK_files_in_transmission_torrent_in_transmission", columns={"id_torrent_in_transmission"})})
 * @ORM\Entity
 */
class FilesInTransmission
{
    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=500, nullable=false)
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="hash_name", type="string", length=50, nullable=false)
     */
    private $hashName;

    /**
     * @var integer
     *
     * @ORM\Column(name="bytesCompleted", type="integer", nullable=false)
     */
    private $bytescompleted;

    /**
     * @var integer
     *
     * @ORM\Column(name="length", type="integer", nullable=false)
     */
    private $length;

    /**
     * @var string
     *
     * @ORM\Column(name="is_ok", type="string", length=1, nullable=true)
     */
    private $isOk;

    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var \AppBundle\Entity\TorrentInTransmission
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\TorrentInTransmission")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="id_torrent_in_transmission", referencedColumnName="id")
     * })
     */
    private $idTorrentInTransmission;

    /**
     * @var integer
     *
     * @ORM\Column(name="percent", type="smallint")
     */
    private $percent;

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
     * @return int
     */
    public function getBytescompleted()
    {
        return $this->bytescompleted;
    }

    /**
     * @param int $bytescompleted
     */
    public function setBytescompleted($bytescompleted)
    {
        $this->bytescompleted = $bytescompleted;
    }

    /**
     * @return int
     */
    public function getLength()
    {
        return $this->length;
    }

    /**
     * @param int $length
     */
    public function setLength($length)
    {
        $this->length = $length;
    }

    /**
     * @return string
     */
    public function getisOk()
    {
        return $this->isOk;
    }

    /**
     * @param string $isOk
     */
    public function setIsOk($isOk)
    {
        $this->isOk = $isOk;
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
     * @return TorrentInTransmission
     */
    public function getIdTorrentInTransmission()
    {
        return $this->idTorrentInTransmission;
    }

    /**
     * @param TorrentInTransmission $idTorrentInTransmission
     */
    public function setIdTorrentInTransmission($idTorrentInTransmission)
    {
        $this->idTorrentInTransmission = $idTorrentInTransmission;
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
}

