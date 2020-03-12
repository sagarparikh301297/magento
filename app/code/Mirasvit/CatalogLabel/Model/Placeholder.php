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



namespace Mirasvit\CatalogLabel\Model;

use Magento\Framework\DataObject\IdentityInterface;

class Placeholder extends \Magento\Framework\Model\AbstractModel implements IdentityInterface
{
    const CACHE_TAG = 'cataloglabel_placeholder';
    /**
     * @var string
     */
    protected $_cacheTag = 'cataloglabel_placeholder';
    /**
     * @var string
     */
    protected $_eventPrefix = 'cataloglabel_placeholder';

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
     * @return void
     */
    protected function _construct()
    {
        $this->_init('Mirasvit\CatalogLabel\Model\ResourceModel\Placeholder');
    }

    /**
     * @return array
     */
    public function getImageType()
    {
        return explode(',', $this->getData('image_type'));
    }

    /**
     * @param string $code
     * @return \Magento\Framework\DataObject
     */
    public function loadByCode($code)
    {
        return $this->getCollection()
            ->addFieldToFilter('code', $code)
            ->getFirstItem();
    }
}
