<?php


namespace SimpleMagento\CustomAdmin\Model\Banner;


use Magento\Framework\App\Filesystem\DirectoryList;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Filesystem;
use Magento\Framework\UrlInterface;
use Magento\MediaStorage\Helper\File\Storage\Database;
use Magento\MediaStorage\Model\File\UploaderFactory;
use Magento\Store\Model\StoreManagerInterface;
use Psr\Log\LoggerInterface;

class ImageUploader
{
    /**
     * @var Database
     */
    protected $coreFileStorageDatabase;

    protected $mediaDirectory;
    /**
     * @var UploaderFactory
     */
    protected $uploaderFactory;
    /**
     * @var Filesystem
     */
    protected $filesystem;
    /**
     * @var StoreManagerInterface
     */
    protected $storeManager;
    /**
     * @var LoggerInterface
     */
    protected $logger;
    /**
     * @var string
     */
    protected $baseTmpPath;
    /**
     * @var string
     */
    protected $basePath;
    /**
     * @var array
     */
    protected $allowedExtension;
    protected $imageName;

    public function __construct(Database $coreFileStorageDatabase,
                                Filesystem $filesystem,
                                UploaderFactory $uploaderFactory,
                                StoreManagerInterface $storeManager,
                                LoggerInterface $logger,
                                $baseTmpPath = 'simplemagento/tmp/banner_slider',
                                $basePath = 'simplemagento/banner_slider',
                                $allowedExtension = ['jpg', 'jpeg', 'gif', 'png'] )
    {
        $this->coreFileStorageDatabase = $coreFileStorageDatabase;
        $this->mediaDirectory = $filesystem->getDirectoryWrite(DirectoryList::MEDIA);
        $this->uploaderFactory = $uploaderFactory;
        $this->storeManager = $storeManager;
        $this->logger = $logger;
        $this->baseTmpPath = $baseTmpPath;
        $this->basePath = $basePath;
        $this->allowedExtension = $allowedExtension;
    }

    /**
     * @param string $basePath
     */
    public function setBasePath($basePath)
    {
        $this->basePath = $basePath;
    }

    /**
     * @param string $baseTmpPath
     */
    public function setBaseTmpPath($baseTmpPath)
    {
        $this->baseTmpPath = $baseTmpPath;
    }

    /**
     * @param array $allowedExtension
     */
    public function setAllowedExtension($allowedExtension)
    {
        $this->allowedExtension = $allowedExtension;
    }

    /**
     * @return string
     */
    public function getBaseTmpPath()
    {
        return $this->baseTmpPath;
    }

    /**
     * @return string
     */
    public function getBasePath()
    {
        return $this->basePath;
    }

    /**
     * @return array
     */
    public function getAllowedExtension()
    {
        return $this->allowedExtension;
    }

    public function getFilePath($path, $imageName)
    {
        return rtrim($path,'/').'/'.ltrim($imageName,'/');
    }

    public function moveFileFromTmp()
    {
        $baseTmpPath = $this->getBaseTmpPath();
        $basePath = $this->getBasePath();

        $baseImagePath = $this->getFilePath($basePath, $imageName);
        $baseTmpImagePath = $this->getFilePath($baseTmpPath, $imageName);

        try{
            $this->coreFileStorageDatabase->copyFile(
                $baseTmpImagePath,
                $baseImagePath
            );
            $this->mediaDirectory->renameFile(
                $baseTmpImagePath,
                $baseImagePath
            );
        } catch (\Exception $e){
            throw new LocalizedException(__('Went wrong while saving file'));
        }
        return $imageName;
    }


    public function saveFileToTmpDir($fileId){
        $baseTmpPath = $this->getBaseTmpPath();

        $uploader = $this->uploaderFactory->create(['fileId' => $fileId]);
        $uploader->setAllowedExtensions($this->getAllowedExtension());
        $uploader->setAllowedExtensions(true);

        $result = $uploader->save($this->mediaDirectory->getAbsolutePath($baseTmpPath));
        unset($result['path']);

        if(!$result){
            throw new LocalizedException(__('File not saved to destination folder'));
        }

        $result['tmp_name'] = str_replace('\\','/', $result['tmp_name']);
        $result['url'] = $this->storeManager
            ->getStore()
            ->getBaseUrl(
                UrlInterface::URL_TYPE_MEDIA
            );
        $this->getFilePath($baseTmpPath, $result['file']);
        $result['name'] = $result['file'];

        if(isset($result['file'])){
            try{
                $relativePath = rtrim($baseTmpPath, '/').'/'.ltrim($result['file'],'/');
                $this->coreFileStorageDatabase->saveFile($relativePath);
            }catch (\Exception $e){
                $this->logger->critical($e);
                throw new LocalizedException(__('Something went wrong'));
            }
        }
        return $result;
    }

}