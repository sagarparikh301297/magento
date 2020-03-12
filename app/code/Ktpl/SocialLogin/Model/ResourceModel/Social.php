<?php
/**
 * @category  Ktpl
 * @package   Ktpl_SocialLogin
 */

namespace Ktpl\SocialLogin\Model\ResourceModel;

use Magento\Framework\Model\ResourceModel\Db\AbstractDb;

/**
 * Class Social
 *
 * @package Ktpl\SocialLogin\Model\ResourceModel
 */
class Social extends AbstractDb
{
    /**
     * Define main table
     */
    protected function _construct()
    {
        $this->_init('ktpl_social_customer', 'social_customer_id');
    }
}
