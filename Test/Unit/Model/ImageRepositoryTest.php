<?php
/**
 *
 * @copyright   2018 Magentando (http://www.magentando.com.br)
 * @license     http://www.magentando.com.br  Copyright
 * @author      Leandro Rosa <dev.leandrorosa@gmail.com>
 *
 * @link        http://www.magentando.com.br
 */

namespace Magentando\FlexSlider\Test\Unit\Model;


use Magentando\FlexSlider\Model\ResourceModel\Image as Resource;
use Magentando\FlexSlider\Model\Image;
use Magentando\FlexSlider\Model\ImageFactory;
use Magentando\FlexSlider\Model\ImageRepository;
use Magentando\FlexSlider\Model\ResourceModel\Image\CollectionFactory;
use Magentando\FlexSlider\Model\ResourceModel\Image\Collection;
use Magento\Framework\Api\SearchCriteria\CollectionProcessorInterface;
use Magento\Framework\Api\SearchCriteriaBuilder;
use Magento\Framework\Api\SearchResultsInterfaceFactory;
use Magento\Framework\Api\SearchResultsInterface;
use Magento\Framework\Api\SearchCriteriaInterface;

class ImageRepositoryTest extends \PHPUnit\Framework\TestCase
{
    /** @var ImageRepository */
    private $model;

    private $imageFactoryMock;
    
    private $imageMock;
    
    private $resourceMock;
    
    private $collectionProcessorMock;
    
    private $criteriaBuilderMock;
    
    private $searchResultsFactoryMock;
    
    private $collectionFactoryMock;
    
    private $searchResultsMock;
    
    private $collectionMock;
    
    private $criteriaMock;

    private $faker;

    /**
     * @throws \ReflectionException
     */
    protected function setUp()
    {
        $this->faker                    = \Faker\Factory::create();
        $objectManager                  = new \Magento\Framework\TestFramework\Unit\Helper\ObjectManager($this);
        $this->resourceMock             = $this->createMock(Resource::class);
        $this->imageMock                = $this->createMock(Image::class);
        $this->collectionProcessorMock  = $this->createMock(CollectionProcessorInterface::class);
        $this->criteriaBuilderMock      = $this->createMock(SearchCriteriaBuilder::class);
        $this->collectionMock           = $this->createMock(Collection::class);
        $this->searchResultsMock        = $this->createMock(SearchResultsInterface::class);
        $this->criteriaMock             = $this->createMock(SearchCriteriaInterface::class);

        $this->collectionFactoryMock    = $this->getMockBuilder(CollectionFactory::class)
            ->setMethods(['create'])
            ->getMock();

        $this->searchResultsFactoryMock = $this->getMockBuilder(SearchResultsInterfaceFactory::class)
            ->setMethods(['create'])
            ->getMock();

        $this->imageFactoryMock         = $this->getMockBuilder(ImageFactory::class)
            ->setMethods(['create'])
            ->getMock();

        $this->model = $objectManager->getObject(
            ImageRepository::class,
            [
                'resource'              => $this->resourceMock,
                'imageFactory'          => $this->imageFactoryMock,
                'collectionProcessor'   => $this->collectionProcessorMock,
                'criteriaBuilder'       => $this->criteriaBuilderMock,
                'collectionFactory'     => $this->collectionFactoryMock,
                'searchResultsFactory'  => $this->searchResultsFactoryMock
            ]
        );
    }

    public function testGet()
    {
        $imageId = $this->faker->randomNumber() +1;
        $this->get($imageId);
        $this->assertEquals($this->model->get($imageId), $this->imageMock);
    }

    /**
     * @expectedException \Magento\Framework\Exception\NoSuchEntityException
     */
    public function testGetWithException()
    {
        $this->get(0);
        $this->model->get(0);
    }

    public function testSave()
    {
        $this->resourceMock
            ->expects($this->once())
            ->method('save')
            ->with($this->imageMock)
            ->willReturnSelf();

        $this->assertEquals($this->imageMock, $this->model->save($this->imageMock));
    }

    /**
     * @expectedException \Magento\Framework\Exception\CouldNotSaveException
     */
    public function testSaveWithException()
    {
        $this->resourceMock
            ->expects($this->once())
            ->method('save')
            ->with($this->imageMock)
            ->will($this->throwException(new \Exception($this->faker->text(20))));

        $this->model->save($this->imageMock);
    }

    public function testDelete()
    {
        $this->delete();
        $this->assertTrue($this->model->delete($this->imageMock));
    }

    /**
     * @expectedException \Magento\Framework\Exception\CouldNotDeleteException
     */
    public function testDeleteWithException()
    {
        $this->deleteWithException();
        $this->model->delete($this->imageMock);
    }

    public function testDeleteById()
    {
        $imageId = $this->faker->randomNumber() +1;
        $this->get($imageId);
        $this->delete();

        $this->assertTrue($this->model->deleteById($imageId));
    }

    /**
     * @expectedException \Magento\Framework\Exception\CouldNotDeleteException
     */
    public function testDeleteByIdWithExceptionOnDelete()
    {
        $imageId = $this->faker->randomNumber() +1;
        $this->get($imageId);
        $this->deleteWithException();

        $this->model->deleteById($imageId);
    }

    /**
     * @expectedException \Magento\Framework\Exception\NoSuchEntityException
     */
    public function testDeleteByIdWithExceptionOnGet()
    {
        $this->get(0);
        $this->model->deleteById(0);
    }

    public function testGetList()
    {
        $size = $this->faker->randomNumber() +1;
        $items = [$this->imageMock];

        $this->collectionMock
            ->expects($this->once())
            ->method('getItems')
            ->willReturn($items);

        $this->collectionMock
            ->expects($this->once())
            ->method('getSize')
            ->willReturn($size);

        $this->collectionFactoryMock
            ->expects($this->once())
            ->method('create')
            ->willReturn($this->collectionMock);

        $this->searchResultsMock
            ->expects($this->once())
            ->method('setSearchCriteria')
            ->with($this->criteriaMock)
            ->willReturnSelf();

        $this->searchResultsMock
            ->expects($this->once())
            ->method('setTotalCount')
            ->with($size)
            ->willReturnSelf();

        $this->searchResultsMock
            ->expects($this->once())
            ->method('setItems')
            ->with($items)
            ->willReturnSelf();

        $this->searchResultsMock
            ->expects($this->once())
            ->method('getItems')
            ->willReturn($items);

        $this->searchResultsFactoryMock
            ->expects($this->once())
            ->method('create')
            ->willReturn($this->searchResultsMock);


        $this->assertEquals($items, $this->model->getList($this->criteriaMock));
    }

    protected function get($imageId)
    {
        $this->imageMock
            ->expects($this->once())
            ->method('getId')
            ->willReturn($imageId);

        $this->imageFactoryMock
            ->expects($this->once())
            ->method('create')
            ->willReturn($this->imageMock);

        $this->resourceMock
            ->expects($this->once())
            ->method('load')
            ->with($this->imageMock, $imageId)
            ->willReturnSelf();
    }

    protected function deleteWithException()
    {
        $this->resourceMock
            ->expects($this->once())
            ->method('delete')
            ->with($this->imageMock)
            ->will($this->throwException(new \Exception($this->faker->text(20))));
    }

    protected function delete()
    {
        $this->resourceMock
            ->expects($this->once())
            ->method('delete')
            ->with($this->imageMock)
            ->willReturnSelf();
    }
}
