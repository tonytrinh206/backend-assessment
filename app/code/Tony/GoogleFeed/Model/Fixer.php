<?php
/***
 * Handle API Call to http://fixer.io to covert currency
 */

namespace Tony\GoogleFeed\Model;

use Magento\Framework\HTTP\Client\Curl;
use Psr\Log\LoggerInterface;
use Magento\Store\Model\StoreManagerInterface;

class Fixer
{
    protected $curl;
    protected $apikey = "a29017944d6b7646fe67ef8a1285a7c1";
    protected $url = "http://data.fixer.io/api/";
    protected $endpoint = "latest";
    protected $store;

    public function __construct(
        Curl $curl,
        LoggerInterface $logger,
        StoreManagerInterface $store
    )
    {
        $this->curl = $curl;
        $this->logger = $logger;
        $this->store = $store;
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
    public function convert(float $total, string $to) : ?float
    {
        try{
            $store_currency = $this->store->getStore()->getCurrentCurrency()->getCode();

            //NOTE: Free plan doesn't support convert function.

            $request = sprintf(
                "%s%s?access_key=%s",
                $this->url,
                $this->endpoint,
                $this->apikey);

            $this->curl->get($request);
            $result = $this->curl->getBody();

            if(empty($request)){
                throw new \Exception("Connection error with Fixer when trying to convert currency",0);
            }

            $result = json_decode($result, true);

            $end_rate = $result['rates'][$to];
            $store_rate = $result['rates'][$store_currency];

            $rate = $end_rate/$store_rate;

            return $total * $rate;

        }
        catch(\Throwable $e){
            $this->logger->error($e->getMessage());
            return null;
        }
    }

}