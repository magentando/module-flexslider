<?php
/**
 * @copyright   2018 Magentando (http://www.magentando.com.br)
 * @license     http://www.magentando.com.br  Copyright
 * @author      Leandro Rosa <dev.leandrorosa@gmail.com>
 *
 * @link        http://www.magentando.com.br
 */

namespace Magentando\FlexSlider\Model;


use Magentando\FlexSlider\Api\ImageUploaderManagementInterface;
use Magento\MediaStorage\Helper\File\Storage\Database;
use Magento\Framework\Filesystem\Directory\WriteInterface;
use Magento\MediaStorage\Model\File\UploaderFactory;
use Magento\Store\Model\StoreManagerInterface;
use Magento\Framework\Filesystem;

class ImageUploaderManagement implements ImageUploaderManagementInterface
{
    /** @var Database  */
    protected $database;

    /** @var WriteInterface  */
    protected $mediaDirectory;

    /** @var UploaderFactory  */
    private $uploaderFactory;

    /** @var StoreManagerInterface  */
    protected $storeManager;

    /** @var string */
    protected $baseTmpPath;

    /** @var string */
    protected $basePath;

    /** @var string[] */
    protected $allowedExtensions;

    /**
     * @param Database $database
     * @param Filesystem $filesystem
     * @param UploaderFactory $uploaderFactory
     * @param StoreManagerInterface $storeManager
     * @param string $baseTmpPath
     * @param string $basePath
     * @param array $allowedExtensions
     * @throws \Magento\Framework\Exception\FileSystemException
     */
    public function __construct(
        Database $database,
        Filesystem $filesystem,
        UploaderFactory $uploaderFactory,
        StoreManagerInterface $storeManager,
        $baseTmpPath,
        $basePath,
        array $allowedExtensions
    ) {
        $this->database             = $database;
        $this->mediaDirectory       = $filesystem->getDirectoryWrite(\Magento\Framework\App\Filesystem\DirectoryList::MEDIA);
        $this->uploaderFactory      = $uploaderFactory;
        $this->storeManager         = $storeManager;
        $this->baseTmpPath          = $baseTmpPath;
        $this->basePath             = $basePath;
        $this->allowedExtensions    = $allowedExtensions;
    }

    /**
     * @inheritdoc
     */
    public function moveFileFromTmp($imageName)
    {
        $baseImagePath      = $this->getFilePath($this->basePath, $imageName);
        $baseTmpImagePath   = $this->getFilePath($this->baseTmpPath, $imageName);

        try {
            $this->database->copyFile(
                $baseTmpImagePath,
                $baseImagePath
            );
            $this->mediaDirectory->renameFile(
                $baseTmpImagePath,
                $baseImagePath
            );
        } catch (\Exception $e) {
            throw new \Magento\Framework\Exception\LocalizedException(
                __('Something went wrong while saving the file(s).')
            );
        }

        return $imageName;
    }

    /**
     * @inheritdoc
     */
    public function saveFileToTmpDir($fileId)
    {
        $uploader = $this->uploaderFactory->create(['fileId' => $fileId]);
        $uploader->setAllowedExtensions($this->allowedExtensions);
        $uploader->setAllowRenameFiles(true);

        $result = $uploader->save($this->mediaDirectory->getAbsolutePath($this->baseTmpPath));

        if (!$result) {
            throw new \Magento\Framework\Exception\LocalizedException(
                __('File can not be saved to the destination folder.')
            );
        }

        $result['id']  = random_int(0, 100);
        $result['url'] = $this->storeManager
                ->getStore()
                ->getBaseUrl(
                    \Magento\Framework\UrlInterface::URL_TYPE_MEDIA
                ) . $this->getFilePath($this->baseTmpPath, $result['file']);

        if (isset($result['file'])) {
            $result['name'] = $result['file'];
            $relativePath   = rtrim($this->baseTmpPath, '/') . '/' . ltrim($result['file'], '/');

            try {
                $this->database->saveFile($relativePath);
            } catch (\Exception $e) {
                throw new \Magento\Framework\Exception\LocalizedException(
                    __('Something went wrong while saving the file(s).')
                );
            }
        }

        return $result;
    }

    /**
     * @param $path
     * @param $imageName
     * @return string
     */
    protected function getFilePath($path, $imageName)
    {
        return rtrim($path, '/') . '/' . ltrim($imageName, '/');
    }
}