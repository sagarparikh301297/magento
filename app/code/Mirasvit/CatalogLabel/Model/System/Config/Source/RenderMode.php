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


namespace Mirasvit\CatalogLabel\Model\System\Config\Source;

class RenderMode implements \Magento\Framework\Option\ArrayInterface
{
    /**
     * @return array
     */
    public function toOptionArray()
    {
        $array = [
            [
                'label' => 'Automatically',
                'value' => 1,
            ],
            [
                'label' => 'Manually',
                'value' => 0,
            ],
        ];

        return $array;
    }

    /**
     * @param strint|int|float $value
     * @return bool|string
     */
    public function getLabel($value)
    {
        foreach ($this->toOptionArray() as $item) {
            if ($item['value'] == $value) {
                return $item['label'];
            }
        }

        return false;
    }
}