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



namespace Mirasvit\CatalogLabel\Service;

use Mirasvit\CatalogLabel\Api\Service\CompatibilityServiceInterface;
use Mirasvit\CatalogLabel\Helper\Version;

/**
 * M2.2. compatibility
 */
class CompatibilityService implements CompatibilityServiceInterface
{
    /**
     * @param Version $version
     */
    public function __construct(
        Version $version
    ) {
        $this->version = $version;
    }

    /**
     * Prepare Rule Data For Save ('conditions_serialized', 'actions_serialized')
     *
     * {@inheritdoc}
     */
    public function prepareRuleDataForSave($value)
    {
        if ($this->version->getVersion() >= '2.2.0'
            && !json_decode($value)) {
            $value = unserialize($value);
            $value = $this->getSerializer()->serialize($value);
        }

        return $value;
    }

    /**
     * {@inheritdoc}
     */
    public function getSerializer()
    {
        $serializer = false;
        if (class_exists(\Magento\Framework\Serialize\Serializer\Json::class)) {
            $serializer = \Magento\Framework\App\ObjectManager::getInstance()->get(
                \Magento\Framework\Serialize\Serializer\Json::class
            );
        }

        return $serializer;
    }
}

