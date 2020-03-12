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

class Store extends \Magento\Framework\Model\AbstractModel implements IdentityInterface
{
    const CACHE_TAG = 'cataloglabel_label_store';
    /**
     * @var string
     */
    protected $_cacheTag = 'cataloglabel_label_store';
    /**
     * @var string
     */
    protected $_eventPrefix = 'cataloglabel_label_store';

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
        $this->_init('Mirasvit\CatalogLabel\Model\ResourceModel\Label\Store');
    }
}
