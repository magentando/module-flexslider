<?php
/**
 * @copyright   2018 Magentando (http://www.magentando.com.br)
 * @license     http://www.magentando.com.br  Copyright
 * @author      Leandro Rosa <dev.leandrorosa@gmail.com>
 *
 * @link        http://www.magentando.com.br
 */

namespace Magentando\FlexSlider\Test\Unit\Model;


use Magentando\FlexSlider\Api\Data\GroupInterface;
use Magentando\FlexSlider\Api\Data\ImageInterface;
use Magentando\FlexSlider\Api\GroupRepositoryInterface;
use Magentando\FlexSlider\Api\ImageRepositoryInterface;
use Magentando\FlexSlider\Model\Group;
use Magentando\FlexSlider\Model\GroupFactory;
use Magentando\FlexSlider\Model\GroupManagement;
use Magentando\FlexSlider\Model\Image;
use Magento\Framework\Api\SearchCriteriaBuilder;
use Magento\Framework\Api\SearchCriteria;
use Magento\Framework\DataObject;
use Magento\Framework\Exception\NoSuchEntityException;

class GroupManagementTest extends \PHPUnit\Framework\TestCase
{
    private $model;

    private $faker;

    private $groupFactoryMock;

    private $groupMock;

    private $groupRepositoryMock;

    private $criteriaBuilderMock;

    private $imageRepositoryMock;

    private $imageMock;

    private $criteriaMock;

    protected function setUp()
    {
        $this->groupFactoryMock = $this->getMockBuilder(GroupFactory::class)
            ->setMethods(['create'])
            ->getMock();

        $this->faker                = \Faker\Factory::create();
        $objectManager              = new \Magento\Framework\TestFramework\Unit\Helper\ObjectManager($this);
        $this->groupMock            = $this->createMock(GroupInterface::class);
        $this->groupRepositoryMock  = $this->createMock(GroupRepositoryInterface::class);
        $this->criteriaBuilderMock  = $this->createMock(SearchCriteriaBuilder::class);
        $this->imageRepositoryMock  = $this->createMock(ImageRepositoryInterface::class);
        $this->imageMock            = $this->createMock(ImageInterface::class);
        $this->criteriaMock         = $this->createMock(SearchCriteria::class);

        $this->model                = $objectManager->getObject(
            GroupManagement::class,
            [
                'groupFactory'      => $this->groupFactoryMock,
                'repository'        => $this->groupRepositoryMock,
                'criteriaBuilder'   => $this->criteriaBuilderMock,
                'imageRepository'   => $this->imageRepositoryMock
            ]
        );
    }

    public function testCreateWithRepository()
    {
        $identifier = $this->faker->word;
        $status     = $this->faker->boolean;
        $properties = \json_encode($this->faker->randomElements());
        $title      = $this->faker->title;
        $id         = $this->faker->randomNumber() +1;


        $this->groupMock
            ->expects($this->once())
            ->method('setIdentifier')
            ->with($identifier)
            ->willReturnSelf();

        $this->groupMock
            ->expects($this->once())
            ->method('setStatus')
            ->with($status)
            ->willReturnSelf();

        $this->groupMock
            ->expects($this->once())
            ->method('setProperties')
            ->with($properties)
            ->willReturnSelf();

        $this->groupMock
            ->expects($this->once())
            ->method('setTitle')
            ->with($title)
            ->willReturnSelf();

        $this->groupRepositoryMock
            ->expects($this->once())
            ->method('get')
            ->with($id)
            ->willReturn($this->groupMock);

        $this->groupRepositoryMock
            ->expects($this->once())
            ->method('save')
            ->with($this->groupMock)
            ->willReturn($this->groupMock);


        $this->assertSame($this->groupMock, $this->model->create($identifier, $status, $properties, $title, $id));
    }

    public function testCreateWithFactory()
    {
        $identifier = $this->faker->word;
        $status     = $this->faker->boolean;
        $properties = \json_encode($this->faker->randomElements());
        $title      = $this->faker->title;
        $id         = $this->faker->randomNumber() +1;


        $this->groupMock
            ->expects($this->once())
            ->method('setIdentifier')
            ->with($identifier)
            ->willReturnSelf();

        $this->groupMock
            ->expects($this->once())
            ->method('setStatus')
            ->with($status)
            ->willReturnSelf();

        $this->groupMock
            ->expects($this->once())
            ->method('setProperties')
            ->with($properties)
            ->willReturnSelf();

        $this->groupMock
            ->expects($this->once())
            ->method('setTitle')
            ->with($title)
            ->willReturnSelf();

        $this->groupRepositoryMock
            ->expects($this->once())
            ->method('get')
            ->with($id)
            ->will($this->throwException(new NoSuchEntityException()));

        $this->groupFactoryMock
            ->expects($this->once())
            ->method('create')
            ->willReturn($this->groupMock);

        $this->groupRepositoryMock
            ->expects($this->once())
            ->method('save')
            ->with($this->groupMock)
            ->willReturn($this->groupMock);


        $this->assertSame($this->groupMock, $this->model->create($identifier, $status, $properties, $title, $id));
    }

    public function testGetGroupByIdentifier()
    {
        $identifier = $this->faker->word;

        $this->criteriaBuilderMock
            ->expects($this->at(0))
            ->method('addFilter')
            ->with(Group::IDENTIFIER, $identifier)
            ->willReturnSelf();

        $this->criteriaBuilderMock
            ->expects($this->at(1))
            ->method('addFilter')
            ->with(Group::STATUS, Group::STATUS_ENABLE)
            ->willReturnSelf();

        $this->criteriaBuilderMock
            ->expects($this->once())
            ->method('create')
            ->willReturn($this->criteriaMock);

        $this->groupRepositoryMock
            ->expects($this->once())
            ->method('getList')
            ->with($this->criteriaMock)
            ->willReturn([$this->groupMock]);

        $this->assertSame($this->groupMock, $this->model->getGroupByIdentifier($identifier));
    }

    public function testGetGroupByIdentifierEmpty()
    {
        $identifier = $this->faker->word;

        $this->criteriaBuilderMock
            ->expects($this->at(0))
            ->method('addFilter')
            ->with(Group::IDENTIFIER, $identifier)
            ->willReturnSelf();

        $this->criteriaBuilderMock
            ->expects($this->at(1))
            ->method('addFilter')
            ->with(Group::STATUS, Group::STATUS_ENABLE)
            ->willReturnSelf();

        $this->criteriaBuilderMock
            ->expects($this->once())
            ->method('create')
            ->willReturn($this->criteriaMock);

        $this->groupRepositoryMock
            ->expects($this->once())
            ->method('getList')
            ->with($this->criteriaMock)
            ->willReturn([]);

        $this->assertInstanceOf(DataObject::class, $this->model->getGroupByIdentifier($identifier));
    }

    public function testGetImages()
    {
        $groupId = $this->faker->randomNumber() +1;

        $this->groupMock
            ->expects($this->once())
            ->method('getGroupId')
            ->willReturn($groupId);


        $this->criteriaBuilderMock
            ->expects($this->at(0))
            ->method('addFilter')
            ->with(Image::GROUP_IDS, [$groupId])
            ->willReturnSelf();

        $this->criteriaBuilderMock
            ->expects($this->at(1))
            ->method('addFilter')
            ->with(Image::STATUS, Image::STATUS_ENABLE)
            ->willReturnSelf();

        $this->criteriaBuilderMock
            ->expects($this->once())
            ->method('create')
            ->willReturn($this->criteriaMock);

        $this->imageRepositoryMock
            ->expects($this->once())
            ->method('getList')
            ->willReturn([$this->imageMock]);

        $this->assertSame([$this->imageMock], $this->model->getImages($this->groupMock));
    }

    public function testGetImagesEmpty()
    {
        $this->criteriaBuilderMock
            ->expects($this->at(0))
            ->method('addFilter')
            ->with(Image::GROUP_IDS, [null])
            ->willReturnSelf();

        $this->criteriaBuilderMock
            ->expects($this->at(1))
            ->method('addFilter')
            ->with(Image::STATUS, Image::STATUS_ENABLE)
            ->willReturnSelf();

        $this->criteriaBuilderMock
            ->expects($this->once())
            ->method('create')
            ->willReturn($this->criteriaMock);

        $this->imageRepositoryMock
            ->expects($this->once())
            ->method('getList')
            ->willReturn([]);

        $this->assertSame([], $this->model->getImages($this->groupMock));
    }
}
