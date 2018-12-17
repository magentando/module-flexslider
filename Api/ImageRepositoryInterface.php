<?php
/**
 *
 * @copyright   2018 Magentando (http://www.magentando.com.br)
 * @license     http://www.magentando.com.br  Copyright
 * @author      Leandro Rosa <dev.leandrorosa@gmail.com>
 *
 * @link        http://www.magentando.com.br
 */

namespace Magentando\FlexSlider\Api;


use Magentando\FlexSlider\Api\Data\ImageInterface;
use Magento\Framework\Api\SearchCriteriaInterface;

interface ImageRepositoryInterface
{
    /**
     * @param int $id
     * @return ImageInterface
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function get($id);

    /**
     * @param ImageInterface $model
     * @return ImageInterface
     * @throws \Magento\Framework\Exception\CouldNotSaveException
     */
    public function save(ImageInterface $model);

    /**
     * @param ImageInterface $model
     * @return bool
     * @throws \Magento\Framework\Exception\CouldNotDeleteException
     */
    public function delete(ImageInterface $model);

    /**
     * @param int $id
     * @return bool
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     * @throws \Magento\Framework\Exception\CouldNotDeleteException
     */
    public function deleteById($id);

    /**
     * @param SearchCriteriaInterface $criteria
     * @return ImageInterface[]
     */
    public function getList(SearchCriteriaInterface $criteria);
}
