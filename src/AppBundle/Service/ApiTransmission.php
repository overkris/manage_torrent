<?php
/**
 * Created by PhpStorm.
 * Date: 19/10/2017
 * Time: 10:41
 */

namespace AppBundle\Service;

use Symfony\Component\DependencyInjection\ContainerInterface;
use Unirest\Request;

class ApiTransmission
{
    private $_urlTransmission;
    private $_user;
    private $_mdp;

    /**
     * ApiTransmission constructor.
     */
    public function __construct(ContainerInterface $container)
    {
        $this->_urlTransmission = $container->getParameter('transmission.url');
        $this->_user = $container->getParameter('transmission.user');
        $this->_mdp = $container->getParameter('transmission.mdp');
    }


    public function callTransmission($objCallTransmission)
    {
        // Test du CSRF transmission
        $mTestCSRF = $this->_testCSRF();
        $aHeader = array();
        if ($mTestCSRF !== true) {
            $aHeader["X-Transmission-Session-Id"] = $mTestCSRF;
        }

        // Post du fichier sur transmission
        $responseConn = Request::post($this->_urlTransmission, $aHeader,
            json_encode($objCallTransmission), $this->_user, $this->_mdp);

        return $responseConn;
    }

    /**
     * Test si on a déjà une session en cours
     * @return mixed
     */
    private function _testCSRF()
    {
        $responseConn = Request::post($this->_urlTransmission, array(), null,
            $this->_user, $this->_mdp);
        if ($responseConn->code == 409) {
            return $responseConn->headers["X-Transmission-Session-Id"];
        } else {
            return true;
        }
    }
}