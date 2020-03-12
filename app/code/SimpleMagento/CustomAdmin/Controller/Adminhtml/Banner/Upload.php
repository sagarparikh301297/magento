<?php


namespace SimpleMagento\CustomAdmin\Controller\Adminhtml\Banner;


use Magento\Backend\App\Action;
use Magento\Framework\App\ResponseInterface;

class Upload extends Action
{
    /**
     * @var \SimpleMagento\CustomAdmin\Model\Banner\ImageUploader
     */
    private $imageUploader;

    public function __construct(Action\Context $context,
                                \SimpleMagento\CustomAdmin\Model\Banner\ImageUploader $imageUploader)
    {
        parent::__construct($context);
        $this->imageUploader = $imageUploader;
    }

    /**
     * Execute action based on request and return result
     *
     * Note: Request will be added as operation argument in future
     *
     * @return \Magento\Framework\Controller\ResultInterface|ResponseInterface
     * @throws \Magento\Framework\Exception\NotFoundException
     */

    protected function _isAllowed()
    {
        return $this->_authorization->isAllowed('SimpleMagento_CustomAdmin::parent');
    }

    public function execute()
    {
        $imageId = $this->_request->getParam('param_name','image');
        try{
            $result = $this->imageUploader->saveFileToTmpDir($imageId);

            $result['cookie'] = [
             'name' => $this->_getSession()->getName(),
             'value' => $this->_getSession()->getSessionId(),
             'lifetime' => $this->_getSession()->getCookieLifetime(),
             'path' => $this->_getSession()->getCookiePath(),
             'domain' => $this->_getSession()->getCookieDomain()

            ];

        }catch (\Exception $e){
            $result = ['error' => $e->getMessage(), 'errorcode' => $e->getCode()];
        }
    }
}