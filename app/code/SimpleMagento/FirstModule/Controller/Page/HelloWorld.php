<?php
namespace SimpleMagento\FirstModule\Controller\Page;

use Magento\Framework\App\ResponseInterface;
use Magento\Framework\App\Action\Context;
use phpDocumentor\Reflection\Type;
use function PHPSTORM_META\type;
use SimpleMagento\FirstModule\Api\PencilInterface;
use Magento\Catalog\Api\ProductRepositoryInterface;
use SimpleMagento\FirstModule\Model\PencilFactory;
use Magento\Catalog\Model\ProductFactory;
use Magento\Framework\App\Request\Http;
use SimpleMagento\FirstModule\Model\HeavyService;

class HelloWorld extends \Magento\Framework\App\Action\Action
{
	protected $pencilInterface;
	protected $productRepository;
	protected $pencilFactory;
	protected $productFactory;
	protected $http;
	protected $heavyService;
	public function __construct(Context $context,
								HeavyService $heavyService,
								ProductFactory $productFactory,
								PencilFactory $pencilFactory,
								PencilInterface $pencilInterface,
								ProductRepositoryInterface $productRepository,
								Http $http)
	{
		$this->pencilFactory = $pencilFactory;
		$this->pencilInterface = $pencilInterface;
		$this->productRepository = $productRepository;
		$this->productFactory = $productFactory;
		$this->http = $http;
		$this->heavyService = $heavyService;
		parent::__construct($context);	
	}

	/**
	 * @return ResponseInterface|\Magento\Framework\Controller\ResultInterface|void
	 */
	public function execute()
	{
//		$objectManager = \Magento\Framework\App\ObjectManager::getInstance();
//		$pencil = $objectManager->create('SimpleMagento\FirstModule\Model\Pencil');
//		var_dump($pencil);
		//echo $this->pencilInterface->getPencilType();
		//echo "hello world";
		//echo get_class($this->productRepository);
		//$pencil = $this->pencilFactory->create(array("name"=>"Alex", "school"=>"International College"));
		//var_dump($pencil);
//		$product = $this->productFactory->create()->load(1);
//		$product->setName("sdfs");
//		$productName = $product->getIdBySku("A0001");
		//echo $productName;
//		echo "Main function"."</br>";
		$id = $this->http->getParam('id',0);
		if($id == 1){
			$this->heavyService->printHeavyServiceMes();
		}else{
			echo "HS not used";
		}
	}
}
?>
