<?php
/**
 * Created by PhpStorm.
 * Date: 09/11/2017
 * Time: 12:10
 */

namespace AppBundle\Model;


use Doctrine\Bundle\DoctrineBundle\Registry;
use Doctrine\DBAL\Connection;
use Doctrine\ORM\EntityManager;

class GetDataDownload
{
    /**
     * @var Registry
     */
    private $doctrine;

    /**
     * @param Registry $doctrine Doctrine
     */
    public function __construct(Connection $doctrine)
    {
        $this->doctrine = $doctrine;
    }

    public function getDataFileIntransmission()
    {
        $query = $this->doctrine->createQueryBuilder()
            ->select("fit.name", "fit.bytesCompleted", "fit.length",
                "fid.percent", "fid.speed", "fid.time_left",
                "tit.date_ajout", "tit.id_transmission", "tit.is_delete")
            ->from("files_in_transmission", "fit")
            ->leftJoin("fit", "files_in_download", "fid", "fit.hash_name = fid.hash_name")
            ->leftJoin("fit", "torrent_in_transmission", "tit", "tit.id = fit.id_torrent_in_transmission")
            ->orderBy("fit.id", "DESC");
        $query->getSQL();

        return $this->doctrine->fetchAll($query->getSQL());
    }
}