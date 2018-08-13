<?php
namespace Tony\GoogleFeed\Controller\Feed;

use Magento\Framework\View\Result\PageFactory;
use Magento\Framework\App\Action\Context;
use Tony\GoogleFeed\Model\GoogleFeed;

class Generate extends \Magento\Framework\App\Action\Action
{

    protected $feed;
    protected $pageFactory;

    public function __construct(
        Context $context,
        GoogleFeed $feed,
        PageFactory $pageFactory
    )
    {
        $this->feed = $feed;
        $this->pageFactory = $pageFactory;
        parent::__construct($context);
    }

    /**
     * Generate Google Feed Manually
     *
     */
    public function execute()
    {
        header('Content-Type: application/xml; charset=utf-8');
        $data = $this->feed->generateFeed(true);
        echo $data;
        exit;
    }

}