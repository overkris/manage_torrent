<?php

namespace AppBundle\Controller;

use AppBundle\Entity\FilesInTransmission;
use AppBundle\Entity\TorrentInTransmission;
use AppBundle\Model\GetDataDownload;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\KernelInterface;

class DefaultController extends Controller
{
    /**
     * @Route("/", name="homepage")
     */
    public function indexAction()
    {
        // replace this example code with whatever you need
        return $this->render('default/index.html.twig');
    }

    /**
     * @Route("/content_table", name="homepage_content")
     */
    public function getContentTableAction(GetDataDownload $objTorrent, KernelInterface $kernel)
    {
        // Call de la commande
        $application = new Application($kernel);
        $application->setAutoExit(false);
        $input = new ArrayInput(['command' => 'torrent:call_all_command']);
        $application->run($input);

        $aDataTorrent = [];
        foreach ($objTorrent->getDataFileIntransmission() as $aTorrent) {
            $aDataTorrent[] = array(
                "name" => $aTorrent["name"],
                "percent_trans" => round(((int)$aTorrent["bytesCompleted"]*100)/(int)$aTorrent["length"], 1),
                "percent_wget" => $aTorrent["percent"],
                "speed_wget" => $aTorrent["speed"],
                "time_left" => $aTorrent["time_left"]
            );
        }

        return $this->render('default/content_table.html.twig', [
            'data_torrent' => $aDataTorrent
        ]);
    }
}
