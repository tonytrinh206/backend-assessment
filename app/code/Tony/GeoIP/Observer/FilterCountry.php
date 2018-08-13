<?php

namespace Tony\GeoIP\Observer;

use Magento\Framework\Event\ObserverInterface;
use Tony\GeoIP\Model\GeoIP;
use Magento\Framework\Event\Observer;
use Magento\Framework\Controller\Result\RedirectFactory;

class FilterCountry implements ObserverInterface
{
    protected  $geoIp;
    protected  $redirectFactory;

    public function __construct(GeoIP $geoIp, RedirectFactory $redirectFactory)
    {
        $this->geoIp = $geoIp;
        $this->redirectFactory = $redirectFactory;
    }

    public function execute(Observer $observer)
    {
        $country_code = $this->geoIp->getCountryCode();

        // TODO: Replace country array with backend configable system.xml

        if(in_array($country_code,['CN',"RU"])){

            //TODO: Test on production to double check exception handler

            throw new NotFoundException(
                __('Sorry! You are not allowed to access this website from your country'));
        }
    }

}