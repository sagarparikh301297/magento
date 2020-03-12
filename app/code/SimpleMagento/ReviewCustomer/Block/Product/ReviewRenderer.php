<?php


namespace SimpleMagento\ReviewCustomer\Block\Product;


use Magento\Catalog\Block\Product\ReviewRendererInterface;
use Magento\Catalog\Model\Product;
use Magento\Framework\App\ObjectManager;
use Magento\Review\Model\ReviewSummaryFactory;
use Magento\Review\Observer\PredispatchReviewObserver;

/**
 * Class ReviewRenderer
 */
class ReviewRenderer extends \Magento\Review\Block\Product\ReviewRenderer
{
    /**
     * Array of available template name
     *
     * @var array
     */
    protected $_availableTemplates = [
        self::FULL_VIEW => 'SimpleMagento_ReviewCustomer::helper/summary.phtml',
        self::SHORT_VIEW => 'Magento_Review::helper/summary_short.phtml',
    ];

}
