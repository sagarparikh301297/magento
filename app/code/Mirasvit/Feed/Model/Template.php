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
 * @package   mirasvit/module-feed
 * @version   1.0.110
 * @copyright Copyright (C) 2019 Mirasvit (https://mirasvit.com/)
 */



namespace Mirasvit\Feed\Model;

use Magento\Framework\Model\Context;
use Magento\Framework\Registry;
use Mirasvit\Core\Service\YamlService;
use Mirasvit\Feed\Model\ResourceModel\Template\CollectionFactory as TemplateCollectionFactory;
use Mirasvit\Feed\Service\Serialize;
use Magento\Framework\Message\ManagerInterface as MessageManagerInterface;

/**
 * Template Model
 *
 * @method string getName()
 * @method $this setName($name)
 * @method bool hasCreatedAt()
 * @method $this setCreatedAt($createdAt)
 */
class Template extends AbstractTemplate
{
    /**
     * @var Config
     */
    protected $config;

    /**
     * @var TemplateCollectionFactory
     */
    protected $collectionFactory;

    /**
     * @var Serializer
     */
    protected $serializer;

    /**
     * @var MessageManagerInterface
     */
    protected $messageManager;

    /**
     * {@inheritdoc}
     * @param Config $config
     * @param TemplateCollectionFactory $templateCollectionFactory
     * @param Context $context
     * @param Registry $registry
     */
    public function __construct(
        Config                    $config,
        TemplateCollectionFactory $templateCollectionFactory,
        Context                   $context,
        Registry                  $registry,
        Serialize                 $serializer,
        MessageManagerInterface   $messageManager
    ) {
        $this->config            = $config;
        $this->collectionFactory = $templateCollectionFactory;
        $this->serializer        = $serializer;
        $this->messageManager    = $messageManager;

        parent::__construct($context, $registry);
    }

    /**
     * {@inheritdoc}
     */
    protected function _construct()
    {
        $this->_init('Mirasvit\Feed\Model\ResourceModel\Template');
    }

    /**
     * Export template to file
     *
     * @return string
     */
    public function export()
    {
        $path = $this->config->getTemplatePath() . '/' . $this->getName() . '.yaml';

        $yaml = YamlService::dump($this->toArray([
            'name',
            'type',
            'format_serialized',
            'csv_delimiter',
            'csv_enclosure',
            'csv_include_header',
            'csv_extra_header',
            'csv_schema',
        ]), 10);

        try {
            file_put_contents($path, $yaml);
        } catch (\Exception $e) {
            $this->messageManager->addWarningMessage(
                __(
                    'There is no permission to export files. Please set Write access to the folder "%1" to export templates',
                    $this->config->getTemplatePath()
                )
            );
        }

        return $path;
    }

    /**
     * Import template from file
     *
     * @param string $filePath
     * @return $this
     */
    public function import($filePath)
    {
        if (is_writeable($filePath)) {
            $content = file_get_contents($filePath);
            $data = YamlService::parse($content);
            $model = $this->collectionFactory->create()
                ->addFieldToFilter('name', $data['name'])
                ->getFirstItem();

            $model->addData($data)
                ->save();

            return $model;
        } else {
            $this->messageManager->addWarningMessage(
                __(
                    'There is no permission to import files. Please set Write access to the folder "%1" to import templates',
                    $this->config->getTemplatePath()
                )
            );
        }
    }

    /**
     * @return array
     */
    public function getRowsToExport()
    {

        $array = [
            'name',
            'type',
            'format_serialized',
            'csv_delimiter',
            'csv_enclosure',
            'csv_include_header',
            'csv_extra_header',
            'csv_schema',
        ];

        return $array;
    }
}