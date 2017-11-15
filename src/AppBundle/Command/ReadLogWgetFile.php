<?php
/**
 * Created by PhpStorm.
 * Date: 21/09/2017
 * Time: 13:32
 */
namespace AppBundle\Command;


use AppBundle\Entity\Etats;
use AppBundle\Entity\FilesInDownload;
use AppBundle\Service\ReadFileWget;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class ReadLogWgetFile extends ContainerAwareCommand
{
    /**
     * Configure de la commande
     */
    protected function configure()
    {
        $this
            // the name of the command (the part after "bin/console")
            ->setName('torrent:read_log_wget_file')
            ->setDescription('Lecture du fichier de log');
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
        /** @var  FilesInDownload[] $aListeFileInDownload*/
        $aListeFileInDownload = $entityManager->getRepository(FilesInDownload::class)
            ->findByEtat($entityManager->getReference(Etats::class,1));

        foreach ($aListeFileInDownload as $objFileInDownload) {
            $sLogFile = $this->getContainer()->getParameter("param_serveur.download_file_log")."/".$objFileInDownload->getHashName().".log";
            $objReadFile = ReadFileWget::readFileWget($sLogFile);

            // Test de l'erreur
            if ($objFileInDownload->getNbKDl() === $objReadFile->iDlFile) {
                $nbError = (int)$objFileInDownload->getNbError();
                $nbError++;
                $objFileInDownload->setNbError($nbError);
            } else {
                $objFileInDownload->setNbError(0);
            }

            $objFileInDownload->setNbKDl($objReadFile->iDlFile);
            $objFileInDownload->setPercent($objReadFile->iPercentDl);
            $objFileInDownload->setSpeed($objReadFile->iSpeed);
            $objFileInDownload->setTimeLeft($objReadFile->iTimeLeft);

            // Si on a plus de 5 erreurs on passe le téléchargement en KO
            if ((int)$objFileInDownload->getNbError() > $this->getContainer()->getParameter("param_serveur.nb_max_error")) {
                $objFileInDownload->setEtat($entityManager->getReference(Etats::class,3));
            }
            // Si on est à 100% on passe la téléchargement à OK
            if ((int)$objReadFile->iPercentDl == 100) {
                $objFileInDownload->setEtat($entityManager->getReference(Etats::class, 2));
            }

            $entityManager->persist($objFileInDownload);
            $entityManager->flush();
        }
    }
}