<?php
/**
 * @copyright   2018 Magentando (http://www.magentando.com.br)
 * @license     http://www.magentando.com.br  Copyright
 * @author      Leandro Rosa <dev.leandrorosa@gmail.com>
 *
 * @link        http://www.magentando.com.br
 */

namespace Magentando\FlexSlider\Api;


use Magentando\FlexSlider\Api\Data\ImageInterface;

interface ImageManagementInterface
{
    /**
     * @param string $identifier
     * @param string $fileName
     * @param $status
     * @param string $title
     * @param array $groupIds
     * @param int $id
     * @return ImageInterface
     */
    public function create($identifier, $fileName, $status, $title = null, array $groupIds = [], $id = null);
}