<?php
/**
 *
 * @copyright   2018 Magentando (http://www.magentando.com.br)
 * @license     http://www.magentando.com.br  Copyright
 * @author      Leandro Rosa <dev.leandrorosa@gmail.com>
 *
 * @link        http://www.magentando.com.br
 */

namespace Magentando\FlexSlider\Model;


use Magentando\FlexSlider\Api\GroupRepositoryInterface;
use Magentando\FlexSlider\Api\Data\GroupInterface;
use Magento\Framework\Api\SearchCriteriaInterface;
use Magentando\FlexSlider\Model\ResourceModel\Group as Resource;
use Magentando\FlexSlider\Model\ResourceModel\Group;
use Magento\Framework\Api\SearchCriteria\CollectionProcessorInterface;
use Magento\Framework\Api\SearchCriteriaBuilder;
use Magento\Framework\Exception\CouldNotDeleteException;
use Magento\Framework\Exception\CouldNotSaveException;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\Api\SearchResultsInterfaceFactory;
use Magento\Framework\Api\SearchResultsInterface;

class GroupRepository implements GroupRepositoryInterface
{
    /** @var Resource */
    protected $resource;

    /** @var GroupFactory */
    protected $groupFactory;

    /** @var CollectionProcessorInterface */
    protected $collectionProcessor;

    /** @var SearchCriteriaBuilder */
    protected $criteriaBuilder;

    /** @var Group\CollectionFactory */
    protected $collectionFactory;

    /** @var SearchResultsInterfaceFactory */
    protected $searchResultsFactory;

    /**
     * @param Group $resource
     * @param GroupFactory $groupFactory
     * @param CollectionProcessorInterface $collectionProcessor
     * @param SearchCriteriaBuilder $criteriaBuilder
     * @param Group\CollectionFactory $collectionFactory
     * @param SearchResultsInterfaceFactory $searchResultsFactory
     */
    public function __construct(
        Resource $resource,
        GroupFactory $groupFactory,
        CollectionProcessorInterface $collectionProcessor,
        SearchCriteriaBuilder $criteriaBuilder,
        Group\CollectionFactory $collectionFactory,
        SearchResultsInterfaceFactory $searchResultsFactory
    )
    {
        $this->resource             = $resource;
        $this->groupFactory         = $groupFactory;
        $this->collectionProcessor  = $collectionProcessor;
        $this->criteriaBuilder      = $criteriaBuilder;
        $this->collectionFactory    = $collectionFactory;
        $this->searchResultsFactory = $searchResultsFactory;
    }

    /**
     * @inheritDoc
     */
    public function get($id)
    {
        $group = $this->groupFactory->create();
        $this->resource->load($group, $id);

        if (! $group->getId()) {
            throw new NoSuchEntityException(__('Group %1 does not exist', $id));
        }

        return $group;
    }

    /**
     * @inheritDoc
     */
    public function save(GroupInterface $group)
    {
        try {
            $this->resource->save($group);
        } catch (\Exception $exception) {
            throw new CouldNotSaveException(__($exception->getMessage()));
        }

        return $group;
    }

    /**
     * @inheritDoc
     */
    public function delete(GroupInterface $group)
    {
        try {
            $this->resource->delete($group);
        } catch (\Exception $exception) {
            throw new CouldNotDeleteException(__($exception->getMessage()));
        }

        return true;
    }

    /**
     * @inheritDoc
     */
    public function deleteById($id)
    {
        return $this->delete($this->get($id));
    }

    /**
     * @inheritDoc
     */
    public function getList(SearchCriteriaInterface $criteria)
    {
        /** @var SearchResultsInterface $searchResult */
        $searchResult = $this->searchResultsFactory->create();

        /** @var Group\Collection $collection */
        $collection = $this->collectionFactory->create();
        $this->collectionProcessor->process($criteria, $collection);

        $searchResult->setSearchCriteria($criteria);
        $searchResult->setTotalCount($collection->getSize());
        $searchResult->setItems($collection->getItems());

        return $searchResult->getItems();
    }
}
