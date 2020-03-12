<?php


namespace SimpleMagento\PreviewPopup\Block\Adminhtml\ActionsLog;

use Magento\Backend\Block\Template;

class Preview extends Template
{
    /**
     * Path to template file in theme.
     * @var string
     */
    public $_template = 'Test_PreviewPopup::tab/modification.phtml';

    /**
     * Preview constructor.
     * @param Template\Context $context
     */
    public function __construct(
        \Magento\Backend\Block\Template\Context $context,
        \Magento\Backend\Model\Auth\Session $authSession,
        \SimpleMagento\ProductTabs\Model\ProductTabsFactory $actionlogFactory,
        \SimpleMagento\ProductTabs\Model\ResourceModel\ProductTabs\CollectionFactory $actionsLogChangesFactory,
        array $data = []
    ) {
        $this->authSession = $authSession;
        $this->actionlogFactory = $actionlogFactory;
        $this->actionsLogChangesFactory = $actionsLogChangesFactory;
        parent::__construct($context, $data);
    }

    public function getModificationDetails()
    {
        $id = $this->getActionsId();
        $actionsLogChangescollections = $this->actionsLogChangesFactory
            ->create()
            ->addFieldToFilter(
                'custom_entity_id',
                array('eq' => $id)
            )
            ->load();
        return $actionsLogChangescollections;
    }

    public function getActionsId()
    {
        return $this->getRequest()->getParam('id');
    }

    public function getUserEmail()
    {
        return $this->authSession->getUser()->getEmail();
    }

    /**
     * Check permission for passed action
     *
     * @param string $resourceId
     * @return bool
     */
    protected function _isAllowed()
    {
        return $this->_authorization->isAllowed("SimpleMagento_PreviewPopup::actions_log");
    }
}