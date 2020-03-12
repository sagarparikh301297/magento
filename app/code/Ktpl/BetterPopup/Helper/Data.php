<?php
/**
 * @category    Ktpl
 * @package     Ktpl_BetterPopup
 */

namespace Ktpl\BetterPopup\Helper;

use Magento\Framework\App\Filesystem\DirectoryList;
use Magento\Framework\App\Helper\Context;
use Magento\Framework\Exception\FileSystemException;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\Filesystem;
use Magento\Framework\ObjectManagerInterface;
use Magento\Store\Model\StoreManagerInterface;
use Ktpl\BetterPopup\Helper\AbstractData as AbstractHelper;

/**
 * Class Data
 * @package Ktpl\BetterPopup\Helper
 */
class Data extends AbstractHelper
{
    const CONFIG_MODULE_PATH = 'betterpopup';

    /**
     * @var DirectoryList
     */
    protected $_directoryList;

    /**
     * @var Filesystem
     */
    protected $_fileSystem;

    /**
     * Data constructor.
     *
     * @param Context $context
     * @param ObjectManagerInterface $objectManager
     * @param StoreManagerInterface $storeManager
     * @param Filesystem $filesystem
     * @param DirectoryList $directoryList
     */
    public function __construct(
        Context $context,
        ObjectManagerInterface $objectManager,
        StoreManagerInterface $storeManager,
        Filesystem $filesystem,
        DirectoryList $directoryList
    ) {
        $this->_fileSystem = $filesystem;
        $this->_directoryList = $directoryList;

        parent::__construct($context, $objectManager, $storeManager);
    }

    /**
     * @param $code
     * @param null $storeId
     *
     * @return array|mixed
     */
    public function getWhatToShowConfig($code, $storeId = null)
    {
        return  $this->getModuleConfig('what_to_show/' . $code, $storeId);
    }

    /**
     * @param $code
     * @param null $storeId
     *
     * @return array|mixed
     */
    public function getWhereToShowConfig($code, $storeId = null)
    {
        return $this->getModuleConfig('where_to_show/' . $code, $storeId);
    }

    /**
     * @param $code
     * @param null $storeId
     *
     * @return array|mixed
     */
    public function getWhenToShowConfig($code, $storeId = null)
    {
        return $this->getModuleConfig('when_to_show/' . $code, $storeId);
    }


    /**
     * Get default template path
     *
     * @param $templateId
     * @param string $type
     *
     * @return string
     */
    public function getTemplatePath($templateId, $type = '.html')
    {
        /** Get directory of Data.php */
        $currentDir = __DIR__;

        /** Get root directory(path of magento's project folder) */
        $rootPath = $this->_directoryList->getRoot();

        $currentDirArr = explode('\\', $currentDir);
        $countDir = count($currentDirArr);
        if ($countDir === 1) {
            $currentDirArr = explode('/', $currentDir);
            $countDir = count($currentDirArr);
        }

        $rootPathArr = explode('/', $rootPath);
        $countPath = count($rootPathArr);
        if ($countPath === 1) {
            $rootPathArr = explode('\\', $rootPath);
            $countPath = count($rootPathArr);
        }

        $basePath = '';
        for ($i = $countPath; $i < $countDir - 1; $i++) {
            $basePath .= $currentDirArr[$i] . '/';
        }

        $templatePath = $basePath . 'view/frontend/templates/popup/template/';

        return $templatePath . $templateId . $type;
    }

    /**
     * @param $relativePath
     *
     * @return string
     * @throws FileSystemException
     */
    public function readFile($relativePath)
    {
        $rootDirectory = $this->_fileSystem->getDirectoryRead(DirectoryList::ROOT);

        return $rootDirectory->readFile($relativePath);
    }

    /**
     * @param $templateId
     *
     * @return string
     * @throws FileSystemException
     */
    public function getDefaultTemplateHtml($templateId)
    {
        return $this->readFile($this->getTemplatePath($templateId));
    }

    /**
     * @return int
     * @throws NoSuchEntityException
     */
    public function getStoreId()
    {
        return $this->storeManager->getStore()->getId();
    }
}
