<?php
/**
 * Mirasvit
 *
 * This source file is subject to the Mirasvit Software License, which is available at https://mirasvit.com/license/.
 * Do not edit or add to this file if you wish to upgrade the to newer versions in the future.
 * If you wish to customize this module for your needs.
 * Please refer to http://www.magentocommerce.com for more information.
 *
 * @category  Mirasvit
 * @package   mirasvit/module-cataloglabel
 * @version   1.1.14
 * @copyright Copyright (C) 2019 Mirasvit (https://mirasvit.com/)
 */



namespace Mirasvit\CatalogLabel\Controller\Adminhtml;

abstract class Label extends \Magento\Backend\App\Action
{
    /**
     * @var \Mirasvit\CatalogLabel\Model\LabelFactory
     */
    protected $labelFactory;

    /**
     * @var \Mirasvit\CatalogLabel\Model\Label\RuleFactory
     */
    protected $labelRuleFactory;

    /**
     * @var \Mirasvit\CatalogLabel\Model\Label
     */
    protected $label;

    /**
     * @var \Mirasvit\CatalogLabel\Model\Config
     */
    protected $config;

    /**
     * @var \Magento\Framework\Stdlib\DateTime\DateTime
     */
    protected $date;

    /**
     * @var \Magento\Framework\Registry
     */
    protected $registry;

    /**
     * @var \Magento\Framework\Json\Helper\Data
     */
    protected $jsonEncoder;

    /**
     * @var \Magento\Backend\App\Action\Context
     */
    protected $context;

    /**
     * @var \Magento\Backend\Model\Session
     */
    protected $backendSession;

    /**
     * @var \Magento\Framework\Controller\ResultFactory
     */
    protected $resultFactory;

    /**
     * @var \Mirasvit\Core\Helper\Cron
     */
    protected $cronHelper;

    /**
     * @param \Mirasvit\CatalogLabel\Model\LabelFactory      $labelFactory
     * @param \Mirasvit\CatalogLabel\Model\Label\RuleFactory $labelRuleFactory
     * @param \Mirasvit\CatalogLabel\Model\Label             $label
     * @param \Mirasvit\CatalogLabel\Model\Config            $config
     * @param \Mirasvit\Core\Helper\Cron                     $cronHelper
     * @param \Magento\Framework\Stdlib\DateTime\DateTime    $date
     * @param \Magento\Framework\Registry                    $registry
     * @param \Magento\Framework\Json\Helper\Data            $jsonEncoder
     * @param \Magento\Backend\App\Action\Context            $context
     */
    public function __construct(
        \Mirasvit\CatalogLabel\Model\LabelFactory $labelFactory,
        \Mirasvit\CatalogLabel\Model\Label\RuleFactory $labelRuleFactory,
        \Mirasvit\CatalogLabel\Model\Label $label,
        \Mirasvit\CatalogLabel\Model\Config $config,
        \Mirasvit\Core\Helper\Cron $cronHelper,
        \Magento\Framework\Stdlib\DateTime\DateTime $date,
        \Magento\Framework\Registry $registry,
        \Magento\Framework\Json\Helper\Data $jsonEncoder,
        \Magento\Backend\App\Action\Context $context
    ) {
        $this->labelFactory = $labelFactory;
        $this->labelRuleFactory = $labelRuleFactory;
        $this->label = $label;
        $this->config = $config;
        $this->cronHelper = $cronHelper;
        $this->date = $date;
        $this->registry = $registry;
        $this->jsonEncoder = $jsonEncoder;
        $this->context = $context;
        $this->backendSession = $context->getSession();
        $this->resultFactory = $context->getResultFactory();

        parent::__construct($context);
    }

    /**
     * @return $this
     */
    protected function _initAction()
    {
        $this->_setActiveMenu('Mirasvit_CatalogLabel::cataloglabel');
        $this->_view->getLayout()->getBlock('head');

        return $this;
    }

    /**
     * @return \Mirasvit\CatalogLabel\Model\Label
     */
    protected function getModel()
    {
        $model = $this->labelFactory->create();
        if ($id = $this->getRequest()->getParam('id')) {
            $model->load($id);
        }

        $this->registry->register('current_model', $model);

        return $model;
    }

    /**
     * @return bool
     */
    protected function _isAllowed()
    {
        return $this->context->getAuthorization()->isAllowed('Mirasvit_CatalogLabel::cataloglabel_cataloglabel_labels');
    }
}
