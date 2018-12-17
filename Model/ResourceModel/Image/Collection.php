<?php
/**
 *
 * @copyright   2018 Magentando (http://www.magentando.com.br)
 * @license     http://www.magentando.com.br  Copyright
 * @author      Leandro Rosa <dev.leandrorosa@gmail.com>
 *
 * @link        http://www.magentando.com.br
 */

namespace Magentando\FlexSlider\Model\ResourceModel\Image;


use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;
use Magentando\FlexSlider\Model\Image as Model;
use Magentando\FlexSlider\Model\ResourceModel\Image as Resource;

/**
 * @codeCoverageIgnore
 */
class Collection extends AbstractCollection
{
    /**
     * @inheritdoc
     */
    protected function _construct()
    {
        $this->_init(Model::class, Resource::class);
    }
}
