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


use Magentando\FlexSlider\Model\ResourceModel\Group as Resource;
use Magentando\FlexSlider\Model\Group;
use Magentando\FlexSlider\Model\GroupFactory;
use Magentando\FlexSlider\Model\GroupRepository;
use Magentando\FlexSlider\Model\ResourceModel\Group\CollectionFactory;
use Magentando\FlexSlider\Model\ResourceModel\Group\Collection;
use Magento\Framework\Api\SearchCriteria\CollectionProcessorInterface;
use Magento\Framework\Api\SearchCriteriaBuilder;
use Magento\Framework\Api\SearchResultsInterfaceFactory;
use Magento\Framework\Api\SearchResultsInterface;
use Magento\Framework\Api\SearchCriteriaInterface;

class GroupRepositoryTest extends \PHPUnit\Framework\TestCase
{
    /** @var GroupRepository */
    private $model;
    
    private $groupFactoryMock;
    
    private $groupMock;
    
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
        $this->groupMock                = $this->createMock(Group::class);
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

        $this->groupFactoryMock         = $this->getMockBuilder(GroupFactory::class)
            ->setMethods(['create'])
            ->getMock();

        $this->model = $objectManager->getObject(
            GroupRepository::class,
            [
                'resource'              => $this->resourceMock,
                'groupFactory'          => $this->groupFactoryMock,
                'collectionProcessor'   => $this->collectionProcessorMock,
                'criteriaBuilder'       => $this->criteriaBuilderMock,
                'collectionFactory'     => $this->collectionFactoryMock,
                'searchResultsFactory'  => $this->searchResultsFactoryMock
            ]
        );
    }

    public function testGet()
    {
        $groupId = $this->faker->randomNumber() +1;
        $this->get($groupId);
        $this->assertEquals($this->model->get($groupId), $this->groupMock);
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
            ->with($this->groupMock)
            ->willReturnSelf();

        $this->assertEquals($this->groupMock, $this->model->save($this->groupMock));
    }

    /**
     * @expectedException \Magento\Framework\Exception\CouldNotSaveException
     */
    public function testSaveWithException()
    {
        $this->resourceMock
            ->expects($this->once())
            ->method('save')
            ->with($this->groupMock)
            ->will($this->throwException(new \Exception($this->faker->text(20))));

        $this->model->save($this->groupMock);
    }

    public function testDelete()
    {
        $this->delete();
        $this->assertTrue($this->model->delete($this->groupMock));
    }

    /**
     * @expectedException \Magento\Framework\Exception\CouldNotDeleteException
     */
    public function testDeleteWithException()
    {
        $this->deleteWithException();
        $this->model->delete($this->groupMock);
    }

    public function testDeleteById()
    {
        $groupId = $this->faker->randomNumber() +1;
        $this->get($groupId);
        $this->delete();

        $this->assertTrue($this->model->deleteById($groupId));
    }

    /**
     * @expectedException \Magento\Framework\Exception\CouldNotDeleteException
     */
    public function testDeleteByIdWithExceptionOnDelete()
    {
        $groupId = $this->faker->randomNumber() +1;
        $this->get($groupId);
        $this->deleteWithException();

        $this->model->deleteById($groupId);
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
        $items = [$this->groupMock];

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

    protected function get($groupId)
    {
        $this->groupMock
            ->expects($this->once())
            ->method('getId')
            ->willReturn($groupId);

        $this->groupFactoryMock
            ->expects($this->once())
            ->method('create')
            ->willReturn($this->groupMock);

        $this->resourceMock
            ->expects($this->once())
            ->method('load')
            ->with($this->groupMock, $groupId)
            ->willReturnSelf();
    }

    protected function deleteWithException()
    {
        $this->resourceMock
            ->expects($this->once())
            ->method('delete')
            ->with($this->groupMock)
            ->will($this->throwException(new \Exception($this->faker->text(20))));
    }

    protected function delete()
    {
        $this->resourceMock
            ->expects($this->once())
            ->method('delete')
            ->with($this->groupMock)
            ->willReturnSelf();
    }
}
