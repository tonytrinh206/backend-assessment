<?php
namespace Tony\GeoIp\Block;

use Magento\Framework\View\Element\Template;
use Magento\Framework\HTTP\PhpEnvironment\RemoteAddress;

class GeoIP extends  \Magento\Framework\View\Element\Template
{
    protected $geoIp;
    protected $ip;

    public function __construct(
        \Tony\GeoIP\Model\GeoIP $geoIp,
        RemoteAddress $ip,
        Template\Context $context,
        array $data = [])
    {
        $this->geoIp = $geoIp;
        $this->ip = $ip;
        parent::__construct($context, $data);
    }

    /***
     * Get country code of current IP
     *
     * @return string
     */
    public function getCountryCode() : string
    {
        // Default Magento get IP function
        //$ip = $this->ip->getRemoteAddress())

        // Custom function get real IP address;
        $ip = $this->getUserIP();

        $code = $this->geoIp->getCountryCode($ip);
        return $code;
    }

    /***
     * Get static block base on country code
     *
     * @return string
     */

    public function getStaticBlock() : string
    {
        $code = $this->getCountryCode();
        if($code == "US"){
            return "us_block";
        }
        else{
            return "global_block";
        }
    }

    /***
     * Get real visitor's ip address based on different network layer
     *
     * @return string
     */
    protected function getUserIP()
    {
        // For testing only
        return "49.176.213.45";

        // Get real visitor IP behind CloudFlare network
        if (isset($_SERVER["HTTP_CF_CONNECTING_IP"])) {
            $_SERVER['REMOTE_ADDR'] = $_SERVER["HTTP_CF_CONNECTING_IP"];
            $_SERVER['HTTP_CLIENT_IP'] = $_SERVER["HTTP_CF_CONNECTING_IP"];
        }
        $client  = @$_SERVER['HTTP_CLIENT_IP'];
        $forward = @$_SERVER['HTTP_X_FORWARDED_FOR'];
        $remote  = $_SERVER['REMOTE_ADDR'];

        if(filter_var($client, FILTER_VALIDATE_IP))
        {
            $ip = $client;
        }
        elseif(filter_var($forward, FILTER_VALIDATE_IP))
        {
            $ip = $forward;
        }
        else
        {
            $ip = $remote;
        }

        return $ip;
    }
}