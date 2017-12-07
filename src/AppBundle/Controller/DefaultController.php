<?php

namespace AppBundle\Controller;

use AppBundle\Entity\FilesInTransmission;
use AppBundle\Entity\TorrentInTransmission;
use AppBundle\Model\GetDataDownload;
use AppBundle\Service\ApiTransmission;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\HttpFoundation\JsonResponse;
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
        $input = new ArrayInput(['command' => 'torrent:read_log_wget_file']);
        $application->run($input);
        $input = new ArrayInput(['command' => 'torrent:show_trans']);
        $application->run($input);

        $aDataTorrent = [];
        foreach ($objTorrent->getDataFileIntransmission() as $aTorrent) {
            $aDataTorrent[] = array(
                "name_torrent" => $aTorrent["name"],
                "percent_trans" => round(((int)$aTorrent["bytesCompleted"]*100)/(int)$aTorrent["length"], 1),
                "percent_wget" => (int) $aTorrent["percent"],
                "speed_wget" => $aTorrent["speed"],
                "time_left" => $aTorrent["time_left"],
                "start_date" => $aTorrent["date_ajout"],
                "id_transmission" => $aTorrent["id_transmission"],
                "is_delete" => $aTorrent["is_delete"]
            );
        }

        return JsonResponse::create($aDataTorrent);
    }

    /**
     * @Route("/delete_torrent_transmission", name="delete_torrent_transmission")
     */
    public function deleteTorrentTransmissionAction(Request $request, ApiTransmission $objApiTrans)
    {

        $idTorrent = $request->request->get("id_torrent");

        $objCallTransmission = array(
            "method" => "torrent-remove",
            "arguments" => array(
                "delete-local-data" => true,
                "ids" => array($idTorrent)
            )
        );

        $objReponse = $objApiTrans->callTransmission($objCallTransmission);

        if ($objReponse->body->result == "success") {
            $em = $this->getDoctrine()->getManager();
            $torrentInTransmission = $em->getRepository(TorrentInTransmission::class)
                ->findOneByIdTransmission($idTorrent);
            $torrentInTransmission->setIsDelete("O");

            $em->persist($torrentInTransmission);
            $em->flush();

            return JsonResponse::create(array("result" => "success"));
        } else {
            return JsonResponse::create(array("result" => "error"));
        }
    }

    /**
     * @Route("/delete_torrent", name="delete_torrent")
     */
    public function deleteTorrentAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $idTorrent = $request->request->get("id_torrent");

        $torrentInTransmission = $em->getRepository(TorrentInTransmission::class)
            ->findOneByIdTransmission($idTorrent);

        $em->remove($torrentInTransmission);
        $em->flush();

        return JsonResponse::create(array("result" => "success"));
    }

    /**
     * @Route("/check_torrent", name="check_torrent")
     */
    public function checkTorrentAction(ApiTransmission $objApiTrans)
    {
        $em = $this->getDoctrine()->getManager();

        $torrentsInTransmission = $em->getRepository(TorrentInTransmission::class)
            ->findAll();

        foreach ($torrentsInTransmission as $torrent) {
            // Get des fichiers dans transmission
            $objCallTransmission = array(
                "method" => "torrent-get",
                "arguments" => array(
                    "ids" => array($torrent->getIdTransmission()),
                    "fields" => array("files")
                )
            );
            $responseConn = $objApiTrans->callTransmission($objCallTransmission);

            if (count($responseConn->body->arguments->torrents) !== 0) {
                $torrent->setIsDelete("N");
            } else {
                $torrent->setIsDelete("O");
            }
            $em->persist($torrent);
            $em->flush();
        }


        return JsonResponse::create(array("result" => "success"));
    }
}
