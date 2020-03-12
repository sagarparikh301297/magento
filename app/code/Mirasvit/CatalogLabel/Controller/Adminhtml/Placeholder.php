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

abstract class Placeholder extends \Magento\Backend\App\Action
{
    /**
     * @var \Mirasvit\CatalogLabel\Model\PlaceholderFactory
     */
    protected $placeholderFactory;

    /**
     * @var \Mirasvit\CatalogLabel\Model\Placeholder
     */
    protected $placeholder;

    /**
     * @var \Magento\Framework\Registry
     */
    protected $registry;

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
     * @param \Mirasvit\CatalogLabel\Model\PlaceholderFactory $placeholderFactory
     * @param \Mirasvit\CatalogLabel\Model\Placeholder        $placeholder
     * @param \Magento\Framework\Registry                     $registry
     * @param \Magento\Backend\App\Action\Context             $context
     */
    public function __construct(
        \Mirasvit\CatalogLabel\Model\PlaceholderFactory $placeholderFactory,
        \Mirasvit\CatalogLabel\Model\Placeholder $placeholder,
        \Magento\Framework\Registry $registry,
        \Magento\Backend\App\Action\Context $context
    ) {
        $this->placeholderFactory = $placeholderFactory;
        $this->placeholder = $placeholder;
        $this->registry = $registry;
        $this->context = $context;
        $this->backendSession = $context->getSession();
        $this->resultFactory = $context->getResultFactory();
        parent::__construct($context);
    }

    /**
     * @param \Magento\Framework\App\RequestInterface $request
     * @return \Magento\Framework\App\ResponseInterface
     */
    public function dispatch(\Magento\Framework\App\RequestInterface $request)
    {
        return parent::dispatch($request);
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
     * @return \Mirasvit\CatalogLabel\Model\Placeholder
     */
    protected function getModel()
    {
        $model = $this->placeholderFactory->create();
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
        return $this->context->getAuthorization()
            ->isAllowed('Mirasvit_CatalogLabel::cataloglabel_cataloglabel_placeholders');
    }
}
