<?php
/**
 * @copyright   2018 Magentando (http://www.magentando.com.br)
 * @license     http://www.magentando.com.br  Copyright
 * @author      Leandro Rosa <dev.leandrorosa@gmail.com>
 *
 * @link        http://www.magentando.com.br
 */

namespace Magentando\FlexSlider\Model;


use Magentando\FlexSlider\Api\ImageManagementInterface;
use Magentando\FlexSlider\Api\ImageRepositoryInterface;
use Magento\Framework\Exception\NoSuchEntityException;

class ImageManagement implements ImageManagementInterface
{
    /** @var ImageFactory  */
    protected $imageFactory;

    /** @var ImageRepositoryInterface  */
    protected $repository;

    /**
     * ImageManagement constructor.
     * @param ImageFactory $imageFactory
     * @param ImageRepositoryInterface $repository
     */
    public function __construct(
        ImageFactory $imageFactory,
        ImageRepositoryInterface $repository
    )
    {
        $this->imageFactory = $imageFactory;
        $this->repository   = $repository;
    }

    /**
     * @inheritDoc
     */
    public function create(
        $identifier,
        $fileName,
        $status,
        $title = null,
        array $groupIds = [],
        $id = null
    )
    {
        try {
            $image = $this->repository->get($id);
        } catch (NoSuchEntityException $exception) {
            $image = $this->imageFactory->create();
        }

        $image
            ->setIdentifier($identifier)
            ->setFileName($fileName)
            ->setStatus($status)
            ->setTitle($title)
            ->setGroupIds($groupIds);

        return $this->repository->save($image);
    }
}
