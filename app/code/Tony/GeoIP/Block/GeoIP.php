<?php
namespace Tony\GeoIp\Block;

use Magento\Framework\View\Element\Template;

class GeoIP extends  \Magento\Framework\View\Element\Template
{
    protected $geoIp;
    protected $ip;

    public function __construct(
        \Tony\GeoIP\Model\GeoIP $geoIp,
        Template\Context $context,
        array $data = [])
    {
        $this->geoIp = $geoIp;
        parent::__construct($context, $data);
    }

    /***
     * Get country code of current IP
     *
     * @return string
     */
    public function getCountryCode() : string
    {
        return $this->geoIp->getCountryCode();
    }

    /***
     * Get static block base on country code
     *
     * @return string
     */

    public function getStaticBlock() : string
    {
        // TODO: replace block name with backend config system.xml

        $code = $this->getCountryCode();

        if($code == "US"){
            return "us_block";
        }
        else{
            return "global_block";
        }
    }


}