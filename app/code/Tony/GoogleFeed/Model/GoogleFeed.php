<?php
namespace Tony\GoogleFeed\Model;

use Magento\Catalog\Model\ResourceModel\Product\CollectionFactory;
use Psr\Log\LoggerInterface;
use Magento\Store\Model\StoreManagerInterface;
use Magento\Framework\Filesystem\DirectoryList;

class GoogleFeed {

    protected $logger;
    protected $productFactory;
    protected $store;
    protected $directoryList;

    public function __construct(
        CollectionFactory $productFactory,
        LoggerInterface $logger,
        StoreManagerInterface $store,
        DirectoryList $directoryList
    ) {
        $this->store = $store;
        $this->logger = $logger;
        $this->directoryList = $directoryList;
        $this->productFactory = $productFactory;
    }


    public function generateFeed()
    {
        try {
            $this->logger->info("Generate Google Feed");

            $collection = $this->productFactory->create()->addAttributeToSelect('*');
            $siteUrl = $this->store->getStore()->getBaseUrl();
            $imageDir = $siteUrl."pub/media/catalog/product";

            $feed = '<?xml version="1.0"?>';
            $feed .= '<rss version="2.0" xmlns:g="http://base.google.com/ns/1.0">';
            $feed .= '<channel>';
            $feed .= '<title>All products of shop ABCD</title>';
            $feed .= sprintf('<link>%s</link>',$siteUrl);
            $feed .= '<description>Product list</description>';

            foreach ($collection as $key => $product) {

                $price = $product->getSpecialPrice()? $product->getSpecialPrice() : $product->getPrice();
                $image = $imageDir.$product->getImage();
                $item = "<item>";
                $item .= sprintf("<title>%s</title>",$product->getName());

                $item .= sprintf("<link>%s</link>",$product->getProductUrl());

                $item .= sprintf("<description>%s</description>",$product->getDescription());

                $item .= sprintf("<g:image_link>%s</g:image_link>",$image);
                $item .= sprintf("<g:price>%s</g:price>",$price);
                $item .= sprintf("<g:condition>%s</g:condition>","new");
                $item .= sprintf("<g:id>%s</g:id>",$product->getSku());
                $item .="</item>";
                $feed .= $item;

            }
            $feed .="</channel>";
            $feed .= "</rss>";

            $rootPath  =  $this->directoryList->getRoot();
            $file = $rootPath ."/feed.xml";
            file_put_contents($file,$feed);
        }
        catch (\Throwable $e){
            $this->logger->error($e->getMessage(),$e->getCode());
        }


    }

}