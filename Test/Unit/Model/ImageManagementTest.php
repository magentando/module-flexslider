<?php
/**
 * @copyright   2018 Magentando (http://www.magentando.com.br)
 * @license     http://www.magentando.com.br  Copyright
 * @author      Leandro Rosa <dev.leandrorosa@gmail.com>
 *
 * @link        http://www.magentando.com.br
 */

namespace Magentando\FlexSlider\Test\Unit\Model;



use Magentando\FlexSlider\Api\Data\ImageInterface;
use Magentando\FlexSlider\Api\ImageRepositoryInterface;
use Magentando\FlexSlider\Model\ImageFactory;
use Magentando\FlexSlider\Model\ImageManagement;
use Magento\Framework\Exception\NoSuchEntityException;

class ImageManagementTest extends \PHPUnit\Framework\TestCase
{
    private $model;

    private $faker;

    private $imageFactoryMock;

    private $imageMock;

    private $imageRepositoryMock;

    protected function setUp()
    {
        $this->imageFactoryMock     = $this->getMockBuilder(ImageFactory::class)
            ->setMethods(['create'])
            ->getMock();

        $this->faker                = \Faker\Factory::create();
        $objectManager              = new \Magento\Framework\TestFramework\Unit\Helper\ObjectManager($this);
        $this->imageRepositoryMock  = $this->createMock(ImageRepositoryInterface::class);
        $this->imageMock            = $this->createMock(ImageInterface::class);

        $this->model                = $objectManager->getObject(
            ImageManagement::class,
            [
                'imageFactory'      => $this->imageFactoryMock,
                'repository'        => $this->imageRepositoryMock
            ]
        );
    }

    public function testCreateWithRepository()
    {
        $identifier = $this->faker->word;
        $fileName   = $this->faker->word . '.jpg';
        $status     = $this->faker->boolean;
        $title      = $this->faker->title;
        $groupIds   = $this->faker->randomElements();
        $id         = $this->faker->randomNumber() +1;


        $this->imageMock
            ->expects($this->once())
            ->method('setIdentifier')
            ->with($identifier)
            ->willReturnSelf();

        $this->imageMock
            ->expects($this->once())
            ->method('setFileName')
            ->with($fileName)
            ->willReturnSelf();

        $this->imageMock
            ->expects($this->once())
            ->method('setStatus')
            ->with($status)
            ->willReturnSelf();

        $this->imageMock
            ->expects($this->once())
            ->method('setTitle')
            ->with($title)
            ->willReturnSelf();

        $this->imageMock
            ->expects($this->once())
            ->method('setGroupIds')
            ->with($groupIds)
            ->willReturnSelf();

        $this->imageRepositoryMock
            ->expects($this->once())
            ->method('get')
            ->with($id)
            ->willReturn($this->imageMock);

        $this->imageRepositoryMock
            ->expects($this->once())
            ->method('save')
            ->with($this->imageMock)
            ->willReturn($this->imageMock);


        $this->assertSame($this->imageMock, $this->model->create($identifier, $fileName, $status, $title, $groupIds, $id));
    }
    public function testCreateWithFactory()
    {
        $identifier = $this->faker->word;
        $fileName   = $this->faker->word . '.jpg';
        $status     = $this->faker->boolean;
        $title      = $this->faker->title;
        $groupIds   = $this->faker->randomElements();
        $id         = $this->faker->randomNumber() +1;


        $this->imageMock
            ->expects($this->once())
            ->method('setIdentifier')
            ->with($identifier)
            ->willReturnSelf();

        $this->imageMock
            ->expects($this->once())
            ->method('setFileName')
            ->with($fileName)
            ->willReturnSelf();

        $this->imageMock
            ->expects($this->once())
            ->method('setStatus')
            ->with($status)
            ->willReturnSelf();

        $this->imageMock
            ->expects($this->once())
            ->method('setTitle')
            ->with($title)
            ->willReturnSelf();

        $this->imageMock
            ->expects($this->once())
            ->method('setGroupIds')
            ->with($groupIds)
            ->willReturnSelf();

        $this->imageRepositoryMock
            ->expects($this->once())
            ->method('get')
            ->with($id)
            ->will($this->throwException(new NoSuchEntityException()));

        $this->imageFactoryMock
            ->expects($this->once())
            ->method('create')
            ->willReturn($this->imageMock);

        $this->imageRepositoryMock
            ->expects($this->once())
            ->method('save')
            ->with($this->imageMock)
            ->willReturn($this->imageMock);


        $this->assertSame($this->imageMock, $this->model->create($identifier, $fileName, $status, $title, $groupIds, $id));
    }
}