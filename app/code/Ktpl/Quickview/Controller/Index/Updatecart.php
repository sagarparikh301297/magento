<?php
/**
 *
 * @category   Krish
 * @package    Ktpl_Quickview
 * @author     Extension Team
 */
namespace Ktpl\Quickview\Controller\Index;

class Updatecart extends \Magento\Framework\App\Action\Action
{
    public function execute()
    {
        if (!$this->getRequest()->isAjax()) {
            $this->_redirect('/');
            return;
        }

        $jsonData = json_encode(['result' => true]);
        $this->getResponse()->setHeader('Content-type', 'application/json');
        $this->getResponse()->setBody($jsonData);
    }
}
