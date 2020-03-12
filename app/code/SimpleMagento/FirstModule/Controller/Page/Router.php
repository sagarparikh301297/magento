<?php


namespace SimpleMagento\FirstModule\Controller\Page;




use Magento\Framework\App\ActionInterface;
use Magento\Framework\App\RequestInterface;
use Magento\Framework\App\ActionFactory;

class Router implements \Magento\Framework\App\RouterInterface
{

    protected $actionFactory;
    public function __construct(ActionFactory $actionFactory)
    {
        $this->actionFactory = $actionFactory;
    }

    /**
     * Match application action by request
     *
     * @param RequestInterface $request
     * @return ActionInterface
     */
    public function match(RequestInterface $request)
    {
        $path = trim($request->getPathInfo(),'/');
        $paths = explode('-', $path);
        $request->setModuleName($paths[0]);
        $request->setControllerName($paths[1]);
        $request->setActionName($paths[2]);

        return $this->actionFactory->create('Magento\Framework\App\Action\Forward',['request'=>$request]);
    }
}