<?php
/**
 * Created by PhpStorm.
 * Date: 21/09/2017
 * Time: 13:32
 */
namespace AppBundle\Command;


use AppBundle\Entity\Etats;
use AppBundle\Entity\TorrentInTransmission;
use AppBundle\Service\ApiTransmission;
use AppBundle\Vo\ResultPushTransmission;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Finder\Finder;

class PushTorrent extends ContainerAwareCommand
{
    /**
     * Configure de la commande
     */
    protected function configure()
    {
        $this
            // the name of the command (the part after "bin/console")
            ->setName('torrent:push_torrent')
            ->setDescription('Push des Torrent sur transmission');
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
        $finder = new Finder();
        $finder->files()
            ->in($this->getContainer()->getParameter('param_serveur.directory_torrent'))
            ->name("*.torrent");

        foreach ($finder as $file) {
            // Dump the absolute path
            $output->writeln("Fichier ".$file->getBasename());
            // Push du fichier sur le serveur transmission
            $objResp = $this->_pushTorrentTransmission(file_get_contents($file->getRealPath()));

            if ($objResp->result == "success") {
                if (NULL === $onjTorrentInTrans = $entityManager->getRepository(TorrentInTransmission::class)->find($objResp->id)) {
                    $onjTorrentInTrans = new TorrentInTransmission();
                    $onjTorrentInTrans->setIdTransmission($objResp->id);
                }

                $onjTorrentInTrans->setName($objResp->name);
                $onjTorrentInTrans->setDateAjout(new \DateTime());
                $onjTorrentInTrans->setEtat($entityManager->getReference(Etats::class,1));
                $onjTorrentInTrans->setHashString($objResp->hashString);
                $entityManager->persist($onjTorrentInTrans);
                $entityManager->flush();
                $output->writeln("Push du fichier sur Transmission --> OK");
                // Suppression fichier torrent
                $fs = new Filesystem();
                $fs->remove($file->getRealPath());
            } else {
                $output->writeln("Push du fichier sur Transmission --> KO");
            }
        }
    }

    /**
     * Push du fichier sur transmission
     * @param $fileTorrentRaw
     * @return ResultPushTransmission
     */
    private function  _pushTorrentTransmission($fileTorrentRaw)
    {
        $objApiTrans = $this->getContainer()->get(ApiTransmission::class);

        $objCallTransmission = array(
            "method" => "torrent-add",
            "arguments" => array(
                "download-dir" => $this->getContainer()->getParameter('transmission.download_dir'),
                "paused" => false,
                "metainfo" => base64_encode($fileTorrentRaw)
            )
        );

        $responseConn = $objApiTrans->callTransmission($objCallTransmission);

        // Traitement de la réponse
        $objReturn = new ResultPushTransmission();
        $response = $responseConn->body;
        if ($responseConn->code == "200") {
            $objReturn->result = $response->result;
            $aArgument = json_decode(json_encode($response->arguments), true);
            if (isset($aArgument["torrent-duplicate"])) {
                $objReturn->id = $aArgument["torrent-duplicate"]["id"];
                $objReturn->name = $aArgument["torrent-duplicate"]["name"];
                $objReturn->hashString = $aArgument["torrent-duplicate"]["hashString"];
            } else if (isset($aArgument["torrent-added"])) {
                $objReturn->id = $aArgument["torrent-added"]["id"];
                $objReturn->name = $aArgument["torrent-added"]["name"];
                $objReturn->hashString = $aArgument["torrent-added"]["hashString"];
            } else {
                $objReturn->result = "error";
                $objReturn->errorResult = var_dump($response, true);
            }
        } else {
            $objReturn->result = "error";
            $objReturn->errorResult = var_dump($response, true);
        }

        return $objReturn;
    }
}
