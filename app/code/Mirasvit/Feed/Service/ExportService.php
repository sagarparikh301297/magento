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



namespace Mirasvit\Feed\Service;

use Mirasvit\Core\Service\YamlService;
use Mirasvit\Feed\Model\Config;
use Mirasvit\Feed\Api\Service\ExportServiceInterface;
use Magento\Framework\Message\ManagerInterface as MessageManagerInterface;

class ExportService implements ExportServiceInterface
{
    /**
     * @var YamlService
     */
    protected $yaml;

    /**
     * @var Config
     */
    protected $config;

    /**
     * @var MessageManagerInterface
     */
    protected $messageManager;

    /**
     * {@inheritdoc}
     * @param YamlService             $yaml
     * @param Config                  $config
     * @param MessageManagerInterface $messageManager
     */
    public function __construct(
        YamlService             $yaml,
        Config                  $config,
        MessageManagerInterface $messageManager
    ) {
        $this->yaml           = $yaml;
        $this->config         = $config;
        $this->messageManager = $messageManager;
    }

    /**
     * {@inheritdoc}
     */
    public function export($entityModel, $path)
    {
        $yaml = $this->yaml->dump(
            $entityModel->toArray($entityModel->getRowsToExport()),
            10
        );

        if (is_writeable($path)) {
            file_put_contents($path, $yaml);
        } else {
            $this->messageManager->addWarningMessage(
                __(
                    'There is no permission to export files. Please set Write access to the folder "%1" to export templates',
                    $this->config->getTemplatePath()
                )
            );
        }
    }
}