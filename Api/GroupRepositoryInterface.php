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


use Magentando\FlexSlider\Api\Data\GroupInterface;
use Magento\Framework\Api\SearchCriteriaInterface;

interface GroupRepositoryInterface
{
    /**
     * @param int $id
     * @return GroupInterface
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function get($id);

    /**
     * @param GroupInterface $model
     * @return GroupInterface
     * @throws \Magento\Framework\Exception\CouldNotSaveException
     */
    public function save(GroupInterface $model);

    /**
     * @param GroupInterface $model
     * @return bool
     * @throws \Magento\Framework\Exception\CouldNotDeleteException
     */
    public function delete(GroupInterface $model);

    /**
     * @param int $id
     * @return bool
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     * @throws \Magento\Framework\Exception\CouldNotDeleteException
     */
    public function deleteById($id);

    /**
     * @param SearchCriteriaInterface $criteria
     * @return GroupInterface[]
     */
    public function getList(SearchCriteriaInterface $criteria);
}
