<?php
/**
 * @copyright   2018 Magentando (http://www.magentando.com.br)
 * @license     http://www.magentando.com.br  Copyright
 * @author      Leandro Rosa <dev.leandrorosa@gmail.com>
 *
 * @link        http://www.magentando.com.br
 */

namespace Magentando\FlexSlider\Api;


use Magentando\FlexSlider\Api\Data\GroupInterface;
use Magentando\FlexSlider\Api\Data\ImageInterface;

interface GroupManagementInterface
{
    /**
     * @param $identifier
     * @param $status
     * @param null $properties
     * @param null $title
     * @param null $id
     * @return GroupInterface
     */
    public function create($identifier, $status, $properties = null, $title = null, $id = null);

    /**
     * @param string $identifier
     * @return GroupInterface
     */
    public function getGroupByIdentifier($identifier);

    /**
     * @param GroupInterface $group
     * @return ImageInterface[]
     */
    public function getImages(GroupInterface $group);
}
