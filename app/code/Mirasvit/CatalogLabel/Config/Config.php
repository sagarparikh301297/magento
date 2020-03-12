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



namespace Mirasvit\CatalogLabel\Config;

use Magento\Store\Model\ScopeInterface as ScopeInterface;
use Magento\Framework\App\Config\ScopeConfigInterface;

class Config implements \Mirasvit\CatalogLabel\Api\Config\ConfigInterface
{
    /**
     * @var ScopeConfigInterface
     */
    protected $scopeConfig;

    /**
     * Config constructor.
     * @param ScopeConfigInterface $scopeConfig
     */
    public function __construct(
        ScopeConfigInterface $scopeConfig
    ) {
        $this->scopeConfig = $scopeConfig;
    }

    /**
     * {@inheritdoc}
     */
    public function isFlushCacheEnabled($store = null)
    {
        return $this->scopeConfig->getValue(
            'cataloglabel/general/is_flush_cache_enabled',
            ScopeInterface::SCOPE_STORE,
            $store
        );
    }
}
