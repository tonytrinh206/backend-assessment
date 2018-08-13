<?php
/***
 * This only for testing purpose
 */
namespace Tony\GeoIP\Controller\Test;

use Magento\Framework\App\Action\Context;
use Magento\Framework\View\Result\PageFactory;
use Tony\GeoIP\Model\GeoIP;

class Index extends \Magento\Framework\App\Action\Action
{
    protected $resultPageFactory;
    protected $geoip;

    public function __construct(
        Context $context,
        PageFactory $resultPageFactory,
        GeoIp $geoIp)
    {
        $this->geoip = $geoIp;
        $this->resultPageFactory = $resultPageFactory;
        parent::__construct($context);
    }

    public function execute()
    {
       $code = $this->geoip->getCountryCode("77.104.150.156");
       echo $code;
    }
}