<?php


namespace Ktpl\DeliveryDays\Model\Config;

use Magento\Eav\Model\Entity\Attribute\Source\AbstractSource;

class DayOptions extends AbstractSource
{

    /**
     * Retrieve All options
     *
     * @return array
     */
    public function getAllOptions()
    {
        $this->_options = [
            ['label'=>__('3 Days'),'value'=>'3'],
            ['label'=>__('7 Days'),'value'=>'7'],
            ['label'=>__('15 Days'),'value'=>'15']
        ];

        return $this->_options;
    }
}