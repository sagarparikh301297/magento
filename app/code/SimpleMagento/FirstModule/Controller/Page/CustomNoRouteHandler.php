<?php


namespace SimpleMagento\FirstModule\Controller\Page;


class CustomNoRouteHandler implements \Magento\Framework\App\Router\NoRouteHandlerInterface
{

    /**
     * Check and process no route request
     *
     * @param \Magento\Framework\App\RequestInterface $request
     * @return bool
     */
    public function process(\Magento\Framework\App\RequestInterface $request)
    {
        $request->setModuleName('noroutefound')->setControllerName('page')->setActionName('customnoroute');
        return true;
    }
}