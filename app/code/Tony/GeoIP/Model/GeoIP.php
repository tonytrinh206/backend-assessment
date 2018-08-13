<?php
/***
 * Handle API Call to https://ipstack.com/
 */
namespace Tony\GeoIP\Model;

use Magento\Framework\HTTP\Client\Curl;
use Psr\Log\LoggerInterface;

class GeoIP
{
    protected $curl;
    protected $apikey = "e5af4888f8346dc22f403f33db1c40e8";
    protected $url = "http://api.ipstack.com/";

    public function __construct(
        Curl $curl,
        LoggerInterface $logger
    )
    {
        $this->curl = $curl;
        $this->logger = $logger;
    }

    /***
     * Get API Key
     *
     * @return string
     */
    public function getApiKey() : string
    {
        return $this->apikey;
    }

    /***
     * Get URL
     *
     * @return string
     */
    public function getUrl() : string
    {
        return $this->url;
    }

    /***
     * Set API Key
     *
     * @param string $key
     */
    public function setApiKey(string $key) : void
    {
        $this->apikey = $key;
    }

    /***
     * Set URL
     *
     * @param string $url
     */
    public function setUrl(string $url) : void
    {
        $this->url = $url;
    }

    /***
     * Get country by IP
     *
     * @param string $ip
     * @return null|string
     */
    public function getCountryCode(string $ip) : ?string
    {
        try{
            $request = sprintf("%s%s?access_key=%s",$this->url,$ip,$this->apikey);
            $this->curl->get($request);
            $result = $this->curl->getBody();

            if(empty($request)){
                throw new \Exception("Not found",0);
            }

            $result = json_decode($result, true);
            return isset($result['country_code'])? $result['country_code'] : null;

        }
        catch(\Throwable $e){
            $this->logger->error($e->getMessage());
            return null;
        }
    }

}