<?php
/**
 * @copyright   2018 Magentando (http://www.magentando.com.br)
 * @license     http://www.magentando.com.br  Copyright
 * @author      Leandro Rosa <dev.leandrorosa@gmail.com>
 *
 * @link        http://www.magentando.com.br
 */

namespace Magentando\FlexSlider\Model;


use Magentando\FlexSlider\Api\Data\GroupInterface;
use Magentando\FlexSlider\Api\GroupManagementInterface;
use Magentando\FlexSlider\Api\GroupRepositoryInterface;
use Magentando\FlexSlider\Api\ImageRepositoryInterface;
use Magento\Framework\Api\SearchCriteriaBuilder;
use Magento\Framework\DataObject;
use Magento\Framework\Exception\NoSuchEntityException;

class GroupManagement implements GroupManagementInterface
{
    /** @var GroupFactory  */
    protected $groupFactory;

    /** @var GroupRepositoryInterface  */
    protected $repository;

    /** @var SearchCriteriaBuilder  */
    protected $criteriaBuilder;

    /** @var ImageRepositoryInterface */
    protected $imageRepository;

    /**
     * GroupManagement constructor.
     * @param GroupFactory $groupFactory
     * @param GroupRepositoryInterface $repository
     * @param SearchCriteriaBuilder $criteriaBuilder
     * @param ImageRepositoryInterface $imageRepository
     */
    public function __construct(
        GroupFactory $groupFactory,
        GroupRepositoryInterface $repository,
        SearchCriteriaBuilder $criteriaBuilder,
        ImageRepositoryInterface $imageRepository
    )
    {
        $this->groupFactory     = $groupFactory;
        $this->repository       = $repository;
        $this->criteriaBuilder  = $criteriaBuilder;
        $this->imageRepository  = $imageRepository;
    }

    /**
     * @inheritDoc
     */
    public function create(
        $identifier,
        $status,
        $properties = null,
        $title = null,
        $id = null
    )
    {
        try {
            $group = $this->repository->get($id);
        } catch (NoSuchEntityException $exception) {
            $group = $this->groupFactory->create();
        }

        $group
            ->setIdentifier($identifier)
            ->setStatus($status)
            ->setProperties($properties)
            ->setTitle($title);

        return $this->repository->save($group);
    }

    /**
     * @inheritDoc
     */
    public function getGroupByIdentifier($identifier)
    {
        $searchCriteria = $this->criteriaBuilder
            ->addFilter(Group::IDENTIFIER, $identifier)
            ->addFilter(Group::STATUS, Group::STATUS_ENABLE)
            ->create();

        $groups = $this->repository->getList($searchCriteria);

        if (empty($groups)) {
            return new DataObject();
        }

        return reset($groups);
    }

    /**
     * @inheritDoc
     */
    public function getImages(GroupInterface $group)
    {
        $searchCriteria = $this->criteriaBuilder
            ->addFilter(Image::GROUP_IDS, [$group->getGroupId()], 'finset')
            ->addFilter(Image::STATUS, Image::STATUS_ENABLE)
            ->create();

        $groups = $this->imageRepository->getList($searchCriteria);
        return $groups ?? [];
    }
}
