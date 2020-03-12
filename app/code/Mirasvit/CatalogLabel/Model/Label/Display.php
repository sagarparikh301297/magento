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

class Display extends \Magento\Framework\Model\AbstractModel implements IdentityInterface
{
    const CACHE_TAG = 'cataloglabel_label_display';
    /**
     * @var string
     */
    protected $_cacheTag = 'cataloglabel_label_display';
    /**
     * @var string
     */
    protected $_eventPrefix = 'cataloglabel_label_display';

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
     * @var \Mirasvit\CatalogLabel\Model\Config
     */
    protected $config;

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
     * @param \Mirasvit\CatalogLabel\Model\Config                     $config
     * @param \Magento\Framework\Model\Context                        $context
     * @param \Magento\Framework\Registry                             $registry
     * @param \Magento\Framework\Model\ResourceModel\AbstractResource $resource
     * @param \Magento\Framework\Data\Collection\AbstractDb           $resourceCollection
     * @param array                                                   $data
     */
    public function __construct(
        \Mirasvit\CatalogLabel\Model\Config $config,
        \Magento\Framework\Model\Context $context,
        \Magento\Framework\Registry $registry,
        \Magento\Framework\Model\ResourceModel\AbstractResource $resource = null,
        \Magento\Framework\Data\Collection\AbstractDb $resourceCollection = null,
        array $data = []
    ) {
        $this->config = $config;
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
        $this->_init('Mirasvit\CatalogLabel\Model\ResourceModel\Label\Display');
    }

    /**
     * @param null|string $type
     * @return null|string
     */
    public function getType($type = null)
    {
        if ($type == null && !$this->getData('type')) {
            return 'list';
        } elseif ($type != null) {
            return $type;
        }

        return $this->getData('type');
    }

    /**
     * @param null|string $type
     * @return string
     */
    public function getImage($type = null)
    {
        $type = $this->getType($type);

        return $this->getData($type.'_image');
    }

    /**
     * @param null|string $type
     * @return string
     */
    public function getImageUrl($type = null)
    {
        $type = $this->getType($type);

        if ($this->getImage($type)) {
            return $this->config->getBaseMediaUrl().$this->getImage($type);
        }
    }

    /**
     * @param null|string $type
     * @return string
     */
    public function getPosition($type = null)
    {
        $type     = $this->getType($type);
        $position = $this->getData($type.'_position');
        if (!$position) {
            $position = 'EMPTY';
        }

        return  $position;
    }

    /**
     * @param null|string $type
     * @return string
     */
    public function getStyle($type = null)
    {
        $type     = $this->getType($type);
        $style = $this->getData($type.'_style');

        return  $style;
    }

    /**
     * @param null|string $type
     * @return string
     */
    public function getTitle($type = null)
    {
        $type = $this->getType($type);

        return $this->getData($type.'_title');
    }

    /**
     * @param null|string $type
     * @return string
     */
    public function getDescription($type = null)
    {
        $type = $this->getType($type);

        return $this->getData($type.'_description');
    }

    /**
     * @param null|string $type
     * @return string
     */
    public function getUrl($type = null)
    {
        $type = $this->getType($type);

        return $this->getData($type.'_url');
    }
}
