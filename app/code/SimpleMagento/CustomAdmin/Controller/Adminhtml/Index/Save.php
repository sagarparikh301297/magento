<?php


namespace SimpleMagento\CustomAdmin\Controller\Adminhtml\Index;


use Magento\Backend\App\Action;
use Magento\Framework\App\Request\DataPersistorInterface;
use Magento\Framework\Exception\LocalizedException;
use Magento\Cms\Controller\Adminhtml\Page\PostDataProcessor;

class Save extends \Magento\Backend\App\Action
{

    const ADMIN_RESOURCE = 'SimpleMagento_CustomAdmin::parent';
    protected $dataProcessor;
    protected $dataPersistor;
    protected $imageUploader;


    public function __construct(
        Action\Context $context,
        PostDataProcessor $dataProcessor,
        DataPersistorInterface $dataPersistor
    )
    {
        $this->dataProcessor = $dataProcessor;
        $this->dataPersistor = $dataPersistor;
        parent::__construct($context);
    }

    public function execute()
    {

        $data = $this->getRequest()->getPostValue();


        $resultRedirect = $this->resultRedirectFactory->create();
        if ($data) {


            if (isset($data['logo'][0]['name']) && isset($data['logo'][0]['tmp_name'])) {
                $data['image'] = $data['logo'][0]['name'];
                $this->imageUploader = \Magento\Framework\App\ObjectManager::getInstance()->get(
                    'SimpleMagento\CustomAdmin\HelloWorldImageUpload'
                );
                $this->imageUploader->moveFileFromTmp($data['image']);
            } elseif (isset($data['logo'][0]['image']) && !isset($data['logo'][0]['tmp_name'])) {
                $data['image'] = $data['logo'][0]['image'];
            } else {
                $data['image'] = null;
            }


            return $resultRedirect->setPath('*/*/');
        }
    }
}