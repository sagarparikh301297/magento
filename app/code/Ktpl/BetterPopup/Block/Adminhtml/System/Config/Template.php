<?php
/**
 * @category    Ktpl
 * @package     Ktpl_BetterPopup
 */

namespace Ktpl\BetterPopup\Block\Adminhtml\System\Config;

use Magento\Backend\Block\Template\Context;
use Magento\Config\Block\System\Config\Form\Field;
use Magento\Framework\Data\Form\Element\AbstractElement;
use Magento\Framework\Exception\FileSystemException;
use Ktpl\BetterPopup\Helper\Data;

/**
 * Class Provider
 * @package Ktpl\Smtp\Block\Adminhtml\System\Config
 */
class Template extends Field
{
    /**
     * Template
     */
    protected $_template = 'Ktpl_BetterPopup::system/config/template.phtml';

    /**
     * @var Data
     */
    protected $_helperData;

    /**
     * Template constructor.
     *
     * @param Context $context
     * @param Data $helperData
     * @param array $data
     */
    public function __construct(
        Context $context,
        Data $helperData,
        array $data = []
    ) {
        $this->_helperData = $helperData;

        parent::__construct($context, $data);
    }

    /**
     * @param AbstractElement $element
     *
     * @return string
     * @throws FileSystemException
     */
    protected function _getElementHtml(AbstractElement $element)
    {
        $originalData = $element->getOriginalData();
        $buttonLabel = !empty($originalData['button_label']) ? $originalData['button_label'] : '';
        $this->addData(
            [
                'button_label' => __($buttonLabel),
                'html_id'      => $element->getHtmlId(),
                'mp_template'  => $this->getOptionTemplate(),
                'data_info'    => json_encode($this->getOptionTemplate())
            ]
        );

        return $this->_toHtml();
    }

//    /**
//     * @return array
//     * @throws FileSystemException
//     */
//    private function getOptionTemplate()
//    {
//        $options = [
//            [
//                'label'       => __('Default Template'),
//                'popupHtml'   => $this->_helperData->getDefaultTemplateHtml('template3/popup'),
//                'successHtml' => $this->_helperData->getDefaultTemplateHtml('success'),
//                'background'  => '#3d9bc7',
//                'textColor'   => '#000000',
//                'width'       => '800',
//                'height'      => '321'
//            ],
//        ];
//        return $options;
//    }
}
