<?php
namespace Tony\GoogleFeed\Controller\Feed;
use Magento\Framework\Controller\ResultFactory;

class Generate extends \Magento\Framework\App\Action\Action
{
    protected $logger;
    protected  $feed;

    public function __construct(
        \Magento\Framework\App\Action\Context $context,
        \Psr\Log\LoggerInterface $logger,
        \Tony\GoogleFeed\Model\GoogleFeed $feed
    )
    {
        $this->feed = $feed;
        $this->logger = $logger;
        parent::__construct($context);
    }

    /**
     * Create new customer after placing order
     *
     */
    public function execute()
    {
        $this->feed->generateFeed();
    }

}