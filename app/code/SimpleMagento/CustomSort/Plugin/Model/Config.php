<?php


namespace SimpleMagento\CustomSort\Plugin\Model;


class Config extends \Magento\Catalog\Model\Config
{
    public function afterGetAttributeUsedForSortByArray(
        \Magento\Catalog\Model\Config $catalogConfig,
        $options
    ) {
        $options['created_at'] = __('New');
        return $options;
    }
}