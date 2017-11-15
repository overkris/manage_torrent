<?php
/**
 * Created by PhpStorm.
 * Date: 19/10/2017
 * Time: 23:17
 */

namespace AppBundle\Service;

use AppBundle\Vo\WgetLogFile;

class ReadFileWget
{
    const EXPR_LOG = "/(?P<k_dl>[0-9]*)K[ .]*[ ]*(?P<percent>[0-9 . ,]*)%[ ]*(?P<speed>[0-9, .]*[A-Z])[ =](?P<time>[0-9a-z]*)/";

    /**
     * Get de la dernière ligne de log
     * @param $sFileLog
     * @return WgetLogFile
     */
    public static function readFileWget($sFileLog)
    {
        // Get du fichier dans un tableau
        $fileLog = file($sFileLog);
        // Count du NB de ligne
        $nbLigne = count($fileLog)-1;
        // On cherche la ligne qui va bien
        for ($i=$nbLigne; $i>=0; $i--) {
            if (preg_match(self::EXPR_LOG,
                    $fileLog[$i], $matches) === 1) {
                break;
            }
        }

        $objWgetFileLog = new WgetLogFile();

        if (count($matches) !== 0) {
            $objWgetFileLog->iDlFile = $matches["k_dl"];
            $objWgetFileLog->iPercentDl = $matches["percent"];
            $objWgetFileLog->iSpeed = $matches["speed"];
            $objWgetFileLog->iTimeLeft = $matches["time"];
        }

        return $objWgetFileLog;
    }
}