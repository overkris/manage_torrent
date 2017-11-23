<?php
/**
 * Created by PhpStorm.
 * Date: 21/09/2017
 * Time: 13:32
 */
namespace AppBundle\Command;

use AppBundle\Entity\Etats;
use AppBundle\Entity\FilesInTransmission;
use AppBundle\Entity\TorrentInTransmission;
use AppBundle\Service\ApiTransmission;
use AppBundle\Vo\FileTransmission;
use Doctrine\Bundle\DoctrineBundle\Registry;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class ShowTransmission extends ContainerAwareCommand
{
    /**
     * Configure de la commande
     */
    protected function configure()
    {
        $this
            // the name of the command (the part after "bin/console")
            ->setName('torrent:show_trans')
            ->setDescription('Liste des fichiers sur transmission');
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
        /** @var  TorrentInTransmission[] $aListeTorrent*/
        $aListeTorrent = $entityManager->getRepository(TorrentInTransmission::class)
            ->findByEtat($entityManager->getReference(Etats::class,1));

        // Traitement par torrent
        foreach ($aListeTorrent as $file) {
            // Get de la liste des fichiers dans transmission
            $aListeObjFile = $this->_getTorrentTransmission($file->getIdTransmission());
            $nbListeObjFile = count($aListeObjFile);
            $nbListeObjFileOk = 0;
            foreach ($aListeObjFile as $objFile) {
                // Est complet?
                $charIsOk = "N";
                if ($objFile->bytesCompleted === $objFile->length) {
                    $charIsOk = "O";
                    $nbListeObjFileOk++;
                }

                // Pourcentage
                $iPourcentage = round(($objFile->bytesCompleted*100)/$objFile->length, 1);

                $sHachName = hash("md5", $objFile->name);
                if (NULL === $objFileInTrans = $entityManager
                        ->getRepository(FilesInTransmission::class)
                        ->findOneBy(array(
                            "idTorrentInTransmission" => $file,
                            "hashName" => $sHachName
                        )))
                {
                    $objFileInTrans = new FilesInTransmission();
                    $objFileInTrans->setHashName($sHachName);
                    $objFileInTrans->setIdTorrentInTransmission($file);
                    $objFileInTrans->setName($objFile->name);
                }

                // Ajout en base de données
                $objFileInTrans->setLength($objFile->length);
                $objFileInTrans->setBytescompleted($objFile->bytesCompleted);
                $objFileInTrans->setIsOk($charIsOk);
                $objFileInTrans->setPercent($iPourcentage);
                $entityManager->persist($objFileInTrans);
                $entityManager->flush();
            }

            // Mise à jour du torrent en base
            $file->setEtatDlTrans($nbListeObjFileOk."/".$nbListeObjFile);
            if ($nbListeObjFileOk == $nbListeObjFile) {
                $file->setEtat($entityManager->getReference(Etats::class,2));
            }

            $entityManager->persist($file);
            $entityManager->flush();
        }
    }

    /**
     * Get des fichiers sur transmission
     * @param $id
     * @return FileTransmission[]
     */
    private function  _getTorrentTransmission($id)
    {
        // Get des fichiers dans transmission
        $objApiTrans = $this->getContainer()->get(ApiTransmission::class);
        $objCallTransmission = array(
            "method" => "torrent-get",
            "arguments" => array(
                "ids" => array($id),
                "fields" => array("files")
            )
        );
        $responseConn = $objApiTrans->callTransmission($objCallTransmission);

        // Traitement du résultat
        $aListeObjFile = array();
        foreach ($responseConn->body->arguments->torrents[0]->files as $fileTrans) {
            $objFileTrans = new FileTransmission();
            $objFileTrans->bytesCompleted = $fileTrans->bytesCompleted;
            $objFileTrans->length = $fileTrans->length;
            $objFileTrans->name = $fileTrans->name;
            array_push($aListeObjFile, $objFileTrans);
        }

        return $aListeObjFile;
    }
}