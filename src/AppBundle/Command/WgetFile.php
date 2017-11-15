<?php
/**
 * Created by PhpStorm.
 * Date: 21/09/2017
 * Time: 13:32
 */
namespace AppBundle\Command;


use AppBundle\Entity\Etats;
use AppBundle\Entity\FilesInDownload;
use AppBundle\Entity\FilesInTransmission;
use AppBundle\Service\AddWgetAction;
use Doctrine\ORM\Query\ResultSetMappingBuilder;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class WgetFile extends ContainerAwareCommand
{
    /**
     * Configure de la commande
     */
    protected function configure()
    {
        $this
            // the name of the command (the part after "bin/console")
            ->setName('torrent:wget_file')
            ->setDescription('Récupère les fichiers sur le serveur');
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        // Set de la bdd
        $entityManager = $this->getContainer()->get('doctrine.orm.default_entity_manager');

        // Récupération des fichiers torrent
        $rsm = new ResultSetMappingBuilder($entityManager);
        // On mappe le nom de chaque colonne en base de données sur les attributs de nos entités
        $rsm->addEntityResult(FilesInTransmission::class, "fit");
        $query = $entityManager->createNativeQuery(
            'SELECT fit.*
                  FROM files_in_transmission fit
                  LEFT JOIN files_in_download fid ON (fit.hash_name = fid.hash_name)
                  WHERE fit.is_ok = "O"
                  AND fid.id_download IS NULL', $rsm);

        foreach ($entityManager->getClassMetadata(FilesInTransmission::class)->fieldMappings as $obj) {
            $rsm->addFieldResult("fit", $obj["columnName"], $obj["fieldName"]);
        }
        $aListeTorrent = $query->getResult();

        $aListeFileIneTorrent = count($entityManager->getRepository(FilesInDownload::class)
            ->findByEtat($entityManager->getReference(Etats::class,1)));

        $nbMaxDownload = 2 - $aListeFileIneTorrent;
        foreach ($aListeTorrent as $aTorrentFile) {
            if ($nbMaxDownload == 0) {
                break;
            }

            $sUrl = $this->getContainer()->getParameter("param_serveur.download_site");
            $aUrlTorrent = $sUrl.$aTorrentFile->getName();
            $sNameLog = $this->getContainer()->getParameter("download_file_log")."/".$aTorrentFile->getHashName().".log";
            AddWgetAction::addWget($aUrlTorrent, $this->getContainer()->getParameter("param_serveur.download_file"), $sNameLog);

            $objFileInDownload = new FilesInDownload();
            $objFileInDownload->setHashName($aTorrentFile->getHashName());
            $objFileInDownload->setStartDate(new \DateTime());
            $objFileInDownload->setEtat($entityManager->getReference(Etats::class,1));
            $entityManager->persist($objFileInDownload);
            $entityManager->flush();
            $nbMaxDownload--;
        }
    }
}