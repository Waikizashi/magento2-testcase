<?php
namespace GymBeam\Greeting\Controller\Index;

use Magento\Framework\App\Action\Context;
use Magento\Framework\App\Action\HttpGetActionInterface;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\View\Result\PageFactory;
use Magento\Store\Model\ScopeInterface;
use Psr\Log\LoggerInterface;

/**
 * Class Index
 * @package GymBeam\Greeting\Controller\Index
 */
class Index implements HttpGetActionInterface
{
    const XML_PATH_GREETING_MESSAGE = 'greeting/settings/message';

    /**
     * @var ScopeConfigInterface
     */
    protected $scopeConfig;

    /**
     * @var PageFactory
     */
    protected $resultPageFactory;

     /**
     * @var LoggerInterface
     */
    protected $logger;

    /**
     * Constructor
     *
     * @param ScopeConfigInterface $scopeConfig
     * @param PageFactory $resultPageFactory
     * @param LoggerInterface $logger
     */
    public function __construct(
        Context $context,
        ScopeConfigInterface $scopeConfig,
        PageFactory $resultPageFactory,
        LoggerInterface $logger
    ) {
        $this->scopeConfig = $scopeConfig;
        $this->resultPageFactory = $resultPageFactory;
        $this->logger = $logger;
    }

    /**
     * Execute action
     *
     * @return \Magento\Framework\View\Result\Page
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function execute()
    {
        try {
            $greetingMessage = $this->scopeConfig->getValue(self::XML_PATH_GREETING_MESSAGE, ScopeInterface::SCOPE_STORE);
            $this->logger->info('Greeting message loaded: ' . $greetingMessage);

            $resultPage = $this->resultPageFactory->create();
            $resultPage->getConfig()->getTitle()->set(__('Greeting Message'));

            /** @var \Magento\Framework\View\Element\Template $block */
            $block = $resultPage->getLayout()->getBlock('greeting.block');
            if ($block) {
                $block->setGreetingMessage($greetingMessage);
            }

            return $resultPage;
        } catch (\Exception $e) {
            $this->logger->error('Error loading greeting message: ' . $e->getMessage());
            throw new \Magento\Framework\Exception\LocalizedException(__('Unable to load greeting message.'));
        }
    }
}