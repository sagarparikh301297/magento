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



namespace Mirasvit\CatalogLabel\Model\Label;

use Magento\Framework\DataObject\IdentityInterface;

class Attribute extends \Magento\Framework\Model\AbstractModel implements IdentityInterface
{
    const CACHE_TAG = 'cataloglabel_label_attribute';
    /**
     * @var string
     */
    protected $_cacheTag = 'cataloglabel_label_attribute';
    /**
     * @var string
     */
    protected $_eventPrefix = 'cataloglabel_label_attribute';

    /**
     * Get identities.
     *
     * @return array
     */
    public function getIdentities()
    {
        return [self::CACHE_TAG.'_'.$this->getId()];
    }

    /**
     * @var \Mirasvit\CatalogLabel\Model\Label\DisplayFactory
     */
    protected $labelDisplayFactory;

    /**
     * @var \Magento\Framework\Model\Context
     */
    protected $context;

    /**
     * @var \Magento\Framework\Registry
     */
    protected $registry;

    /**
     * @var \Magento\Framework\Model\ResourceModel\AbstractResource
     */
    protected $resource;

    /**
     * @var \Magento\Framework\Data\Collection\AbstractDb
     */
    protected $resourceCollection;

    /**
     * @param \Mirasvit\CatalogLabel\Model\Label\DisplayFactory        $labelDisplayFactory
     * @param \Magento\Framework\Model\Context                        $context
     * @param \Magento\Framework\Registry                             $registry
     * @param \Magento\Framework\Model\ResourceModel\AbstractResource $resource
     * @param \Magento\Framework\Data\Collection\AbstractDb           $resourceCollection
     * @param array                                                   $data
     */
    public function __construct(
        \Mirasvit\CatalogLabel\Model\Label\DisplayFactory $labelDisplayFactory,
        \Magento\Framework\Model\Context $context,
        \Magento\Framework\Registry $registry,
        \Magento\Framework\Model\ResourceModel\AbstractResource $resource = null,
        \Magento\Framework\Data\Collection\AbstractDb $resourceCollection = null,
        array $data = []
    ) {
        $this->labelDisplayFactory = $labelDisplayFactory;
        $this->context = $context;
        $this->registry = $registry;
        $this->resource = $resource;
        $this->resourceCollection = $resourceCollection;
        parent::__construct($context, $registry, $resource, $resourceCollection, $data);
    }

    /**
     * @return void
     */
    protected function _construct()
    {
        $this->_init('Mirasvit\CatalogLabel\Model\ResourceModel\Label\Attribute');
    }

    /**
     * @return Display
     */
    public function getDisplay()
    {
        $display = $this->labelDisplayFactory->create();
        if ($this->getDisplayId()) {
            $display->load($this->getDisplayId());
        }

        return $display;
    }

    /**
     * @param int $labelId
     * @param int $optionId
     * @return \Magento\Framework\DataObject
     */
    public function loadByKey($labelId, $optionId)
    {
        //todo for M2.2 maybe need check in more details
        if (is_array($optionId) && !$optionId) {
            $optionId = 0;
        }

        return $this->getCollection()->addFieldToFilter('label_id', $labelId)
            ->addFieldToFilter('option_id', $optionId)
            ->getLastItem();
    }
}
