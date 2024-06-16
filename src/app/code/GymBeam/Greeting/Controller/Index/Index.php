<?php
namespace GymBeam\Greeting\Controller\Index;

use Magento\Framework\App\Action\HttpGetActionInterface;
use Magento\Framework\App\Action\Context;
use Magento\Framework\View\Result\PageFactory;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Store\Model\ScopeInterface;

class Index implements HttpGetActionInterface
{
    const XML_PATH_GREETING_MESSAGE = 'greeting/settings/message';

    protected $scopeConfig;
    protected $resultPageFactory;

    public function __construct(
        Context $context,
        ScopeConfigInterface $scopeConfig,
        PageFactory $resultPageFactory
    ) {
        $this->scopeConfig = $scopeConfig;
        $this->resultPageFactory = $resultPageFactory;
    }

    public function execute()
    {
        $greetingMessage = $this->scopeConfig->getValue(self::XML_PATH_GREETING_MESSAGE, ScopeInterface::SCOPE_STORE);
        $resultPage = $this->resultPageFactory->create();
        $resultPage->getConfig()->getTitle()->set(__('Greeting Message'));

        /** @var \Magento\Framework\View\Element\Template $block */
        $block = $resultPage->getLayout()->getBlock('greeting.block');
        if ($block) {
            $block->setGreetingMessage($greetingMessage);
        }

        return $resultPage;
    }
}
