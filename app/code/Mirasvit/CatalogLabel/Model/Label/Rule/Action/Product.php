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



namespace Mirasvit\CatalogLabel\Model\Label\Rule\Action;

class Product extends \Magento\Rule\Model\Action\AbstractAction
{
    /**
     * @return $this
     */
    public function loadAttributeOptions()
    {
        $this->setAttributeOption([
            'rule_price' => __('Rule price'),
        ]);

        return $this;
    }

    /**
     * @return $this
     */
    public function loadOperatorOptions()
    {
        $this->setOperatorOption([
            'to_fixed' => __('To Fixed Value'),
            'to_percent' => __('To Percentage'),
            'by_fixed' => __('By Fixed value'),
            'by_percent' => __('By Percentage'),
        ]);

        return $this;
    }


    /**
     * @return string
     */
    public function asHtml()
    {
        $html = $this->getTypeElement()->getHtml().__(
                "Update product's %1 %2: %3",
                $this->getAttributeElement()->getHtml(),
                $this->getOperatorElement()->getHtml(),
                $this->getValueElement()->getHtml()
            );
        $html .= $this->getRemoveLinkHtml();

        return $html;
    }
}
