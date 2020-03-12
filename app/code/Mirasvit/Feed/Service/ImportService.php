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
use Mirasvit\Feed\Api\Service\ImportServiceInterface;
use Magento\Framework\Message\ManagerInterface as MessageManagerInterface;

class ImportService implements ImportServiceInterface
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
    public function import($object, $filePath)
    {
        if (is_writeable($filePath)) {
            $content = file_get_contents($filePath);
            $data = $this->yaml->parse($content);
            $object->setData($data);
            $object->setIsActive(1)->save();

            return $object;
        } else {
            $this->messageManager->addWarningMessage(
                __(
                    'There is no permission to import files. Please set Write access to the folder "%1" to import templates',
                    $this->config->getTemplatePath()
                )
            );
        }
    }
}