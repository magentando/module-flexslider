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


use Magentando\FlexSlider\Api\ImageRepositoryInterface;
use Magentando\FlexSlider\Api\Data\ImageInterface;
use Magento\Framework\Api\SearchCriteriaInterface;
use Magentando\FlexSlider\Model\ResourceModel\Image as Resource;
use Magentando\FlexSlider\Model\ResourceModel\Image;
use Magento\Framework\Api\SearchCriteria\CollectionProcessorInterface;
use Magento\Framework\Api\SearchCriteriaBuilder;
use Magento\Framework\Exception\CouldNotDeleteException;
use Magento\Framework\Exception\CouldNotSaveException;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\Api\SearchResultsInterfaceFactory;
use Magento\Framework\Api\SearchResultsInterface;

class ImageRepository implements ImageRepositoryInterface
{
    /** @var Resource */
    protected $resource;

    /** @var ImageFactory */
    protected $imageFactory;

    /** @var CollectionProcessorInterface */
    protected $collectionProcessor;

    /** @var SearchCriteriaBuilder */
    protected $criteriaBuilder;

    /** @var Image\CollectionFactory */
    protected $collectionFactory;

    /** @var SearchResultsInterfaceFactory */
    protected $searchResultsFactory;

    /**
     * @param Image $resource
     * @param ImageFactory $imageFactory
     * @param CollectionProcessorInterface $collectionProcessor
     * @param SearchCriteriaBuilder $criteriaBuilder
     * @param Image\CollectionFactory $collectionFactory
     * @param SearchResultsInterfaceFactory $searchResultsFactory
     */
    public function __construct(
        Resource $resource,
        ImageFactory $imageFactory,
        CollectionProcessorInterface $collectionProcessor,
        SearchCriteriaBuilder $criteriaBuilder,
        Image\CollectionFactory $collectionFactory,
        SearchResultsInterfaceFactory $searchResultsFactory
    )
    {
        $this->resource             = $resource;
        $this->imageFactory         = $imageFactory;
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
        $image = $this->imageFactory->create();
        $this->resource->load($image, $id);

        if (! $image->getId()) {
            throw new NoSuchEntityException(__('Image %1 does not exist', $id));
        }

        return $image;
    }

    /**
     * @inheritDoc
     */
    public function save(ImageInterface $image)
    {
        try {
            $this->resource->save($image);
        } catch (\Exception $exception) {
            throw new CouldNotSaveException(__($exception->getMessage()));
        }

        return $image;
    }

    /**
     * @inheritDoc
     */
    public function delete(ImageInterface $image)
    {
        try {
            $this->resource->delete($image);
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

        /** @var Image\Collection $collection */
        $collection = $this->collectionFactory->create();
        $this->collectionProcessor->process($criteria, $collection);

        $searchResult->setSearchCriteria($criteria);
        $searchResult->setTotalCount($collection->getSize());
        $searchResult->setItems($collection->getItems());

        return $searchResult->getItems();
    }
}
