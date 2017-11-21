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
                "fid.start_date")
            ->from("files_in_transmission", "fit")
            ->leftJoin("fit", "files_in_download", "fid", "fit.hash_name = fid.hash_name")
            ->orderBy("fit.id", "DESC");
        $query->getSQL();

        return $this->doctrine->fetchAll($query->getSQL());
    }
}