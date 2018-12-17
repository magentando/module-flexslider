<?php
/**
 * @copyright   2018 Magentando (http://www.magentando.com.br)
 * @license     http://www.magentando.com.br  Copyright
 * @author      Leandro Rosa <dev.leandrorosa@gmail.com>
 *
 * @link        http://www.magentando.com.br
 */

namespace Magentando\FlexSlider\Test\Unit\Model;


use Magentando\FlexSlider\Helper\Data;
use Magentando\FlexSlider\Model\ImageUploaderManagement;
use Magento\MediaStorage\Helper\File\Storage\Database;
use Magento\Framework\Filesystem\Directory\WriteInterface;
use Magento\MediaStorage\Model\File\UploaderFactory;
use Magento\MediaStorage\Model\File\Uploader;
use Magento\Store\Model\StoreManagerInterface;
use Magento\Store\Model\Store;
use Magento\Framework\Filesystem;

class ImageUplodaderManagementTest extends \PHPUnit\Framework\TestCase
{
    private $model;

    private $faker;

    private $databaseMock;

    private $filesystemMock;

    private $uploaderFactoryMock;

    private $uploaderMock;

    private $storeManagerMock;

    private $storeMock;

    private $mediaDirectoryMock;

    protected function setUp()
    {
        $this->uploaderFactoryMock  = $this->getMockBuilder(UploaderFactory::class)
            ->setMethods(['create'])
            ->getMock();

        $this->faker                = \Faker\Factory::create();
        $objectManager              = new \Magento\Framework\TestFramework\Unit\Helper\ObjectManager($this);
        $this->databaseMock         = $this->createMock(Database::class);
        $this->filesystemMock       = $this->createMock(Filesystem::class);
        $this->uploaderMock         = $this->createMock(Uploader::class);
        $this->storeManagerMock     = $this->createMock(StoreManagerInterface::class);
        $this->storeMock            = $this->createMock(Store::class);
        $this->mediaDirectoryMock   = $this->createMock(WriteInterface::class);

        $this->filesystemMock
            ->expects($this->once())
            ->method('getDirectoryWrite')
            ->with(\Magento\Framework\App\Filesystem\DirectoryList::MEDIA)
            ->willReturn($this->mediaDirectoryMock);

        $this->model = $objectManager->getObject(
            ImageUploaderManagement::class,
            [
                'database'          => $this->databaseMock,
                'filesystem'        => $this->filesystemMock,
                'uploaderFactory'   => $this->uploaderFactoryMock,
                'storeManager'      => $this->storeManagerMock,
                'baseTmpPath'       => Data::BASE_PATH . '/tmp',
                'basePath'          => Data::BASE_PATH,
                'allowedExtensions' => ['jpg', 'png'],
            ]
        );
    }

    public function testMoveFileFromTmp()
    {
        $filename           = $this->faker->word . '.png';
        $baseImagePath      = $this->getFilePath(Data::BASE_PATH, $filename);
        $baseTmpImagePath   = $this->getFilePath(Data::BASE_PATH . '/tmp', $filename);

        $this->databaseMock
            ->expects($this->once())
            ->method('copyFile')
            ->with($baseTmpImagePath, $baseImagePath);

        $this->mediaDirectoryMock
            ->expects($this->once())
            ->method('renameFile')
            ->with($baseTmpImagePath, $baseImagePath)
            ->willReturn(true);

        $this->assertSame($filename, $this->model->moveFileFromTmp($filename));
    }

    /**
     * @expectedException \Magento\Framework\Exception\LocalizedException
     * @expectedExceptionMessage Something went wrong while saving the file(s).
     */
    public function testMoveFileFromTmpWithException()
    {
        $filename           = $this->faker->word . '.png';
        $baseImagePath      = $this->getFilePath(Data::BASE_PATH, $filename);
        $baseTmpImagePath   = $this->getFilePath(Data::BASE_PATH . '/tmp', $filename);

        $this->databaseMock
            ->expects($this->once())
            ->method('copyFile')
            ->with($baseTmpImagePath, $baseImagePath)
            ->will($this->throwException(new \Exception()));

        $this->assertSame($filename, $this->model->moveFileFromTmp($filename));
    }

    public function testsaveFileToTmpDir()
    {
        $file = $this->faker->word . '.png';
        $path = implode('/', $this->faker->words);
        $result = [
            'file' => $file
        ];

        $this->mediaDirectoryMock
            ->expects($this->once())
            ->method('getAbsolutePath')
            ->willReturn($path);

        $this->uploaderMock
            ->expects($this->once())
            ->method('setAllowedExtensions')
            ->with(['jpg', 'png'])
            ->willReturnSelf();

        $this->uploaderMock
            ->expects($this->once())
            ->method('setAllowRenameFiles')
            ->with(true)
            ->willReturnSelf();

        $this->uploaderMock
            ->expects($this->once())
            ->method('save')
            ->with($path)
            ->willReturn($result);

        $this->storeMock
            ->expects($this->once())
            ->method('getBaseUrl')
            ->with(\Magento\Framework\UrlInterface::URL_TYPE_MEDIA)
            ->willReturn($path);

        $this->storeManagerMock
            ->expects($this->once())
            ->method('getStore')
            ->willReturn($this->storeMock);

        $this->uploaderFactoryMock
            ->expects($this->once())
            ->method('create')
            ->willReturn($this->uploaderMock);

        $this->databaseMock
            ->expects($this->once())
            ->method('saveFile');


        $this->arrayHasKey('file', $this->model->saveFileToTmpDir($file));
    }

    /**
     * @expectedException \Magento\Framework\Exception\LocalizedException
     * @expectedExceptionMessage File can not be saved to the destination folder.
     */
    public function testsaveFileToTmpDirWithExceptionEmptyResult()
    {
        $file = $this->faker->word . '.png';
        $path = implode('/', $this->faker->words);

        $this->mediaDirectoryMock
            ->expects($this->once())
            ->method('getAbsolutePath')
            ->willReturn($path);

        $this->uploaderMock
            ->expects($this->once())
            ->method('setAllowedExtensions')
            ->with(['jpg', 'png'])
            ->willReturnSelf();

        $this->uploaderMock
            ->expects($this->once())
            ->method('setAllowRenameFiles')
            ->with(true)
            ->willReturnSelf();

        $this->uploaderMock
            ->expects($this->once())
            ->method('save')
            ->with($path)
            ->willReturn(null);

        $this->uploaderFactoryMock
            ->expects($this->once())
            ->method('create')
            ->willReturn($this->uploaderMock);

        $this->model->saveFileToTmpDir($file);

    }

    /**
     * @expectedException \Magento\Framework\Exception\LocalizedException
     * @expectedExceptionMessage Something went wrong while saving the file(s).
     */
    public function testsaveFileToTmpDirWithExceptionCantSave()
    {
        $file = $this->faker->word . '.png';
        $path = implode('/', $this->faker->words);
        $result = [
            'file' => $file
        ];

        $this->mediaDirectoryMock
            ->expects($this->once())
            ->method('getAbsolutePath')
            ->willReturn($path);

        $this->uploaderMock
            ->expects($this->once())
            ->method('setAllowedExtensions')
            ->with(['jpg', 'png'])
            ->willReturnSelf();

        $this->uploaderMock
            ->expects($this->once())
            ->method('setAllowRenameFiles')
            ->with(true)
            ->willReturnSelf();

        $this->uploaderMock
            ->expects($this->once())
            ->method('save')
            ->with($path)
            ->willReturn($result);

        $this->storeMock
            ->expects($this->once())
            ->method('getBaseUrl')
            ->with(\Magento\Framework\UrlInterface::URL_TYPE_MEDIA)
            ->willReturn($path);

        $this->storeManagerMock
            ->expects($this->once())
            ->method('getStore')
            ->willReturn($this->storeMock);

        $this->uploaderFactoryMock
            ->expects($this->once())
            ->method('create')
            ->willReturn($this->uploaderMock);

        $this->databaseMock
            ->expects($this->once())
            ->method('saveFile')
            ->will($this->throwException(new \Exception()));


        $this->model->saveFileToTmpDir($file);
    }

    protected function getFilePath($path, $imageName)
    {
        return rtrim($path, '/') . '/' . ltrim($imageName, '/');
    }

}
