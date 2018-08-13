<?php
namespace Tony\GoogleFeed\Cron;

class GenerateFeed {

    protected $logger;
    protected  $feed;

    public function __construct(
        \Psr\Log\LoggerInterface $logger,
        \Tony\GoogleFeed\Model\GoogleFeed $feed
    ) {
        $this->feed = $feed;
        $this->logger = $logger;
    }

    public function execute()
    {
        try{
            $this->feed->generateFeed();
        }
        catch (\Throwable $e) {
            $this->logger->error($e->getMessage());
        }

    }

}