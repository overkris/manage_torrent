<?php
/**
 * Created by PhpStorm.
 * Date: 19/10/2017
 * Time: 23:32
 */

namespace AppBundle\Service;


use Symfony\Component\Process\Process;

class AddWgetAction
{
    const WGET_COMMANDE = 'wget -P %s -o %s "%s"';

    public static function addWget($sUrlFile, $sDestFile, $sDestLog)
    {
        // Construction de la commande
        $sWgetCmd = sprintf(self::WGET_COMMANDE, $sDestFile, $sDestLog, $sUrlFile);
        // Lancement de la commande
        $process = new Process($sWgetCmd);
        $process->start();

        return $sUrlFile;
    }
}