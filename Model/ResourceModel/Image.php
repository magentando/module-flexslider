<?php
/**
 *
 * @copyright   2018 Magentando (http://www.magentando.com.br)
 * @license     http://www.magentando.com.br  Copyright
 * @author      Leandro Rosa <dev.leandrorosa@gmail.com>
 *
 * @link        http://www.magentando.com.br
 */

namespace Magentando\FlexSlider\Model\ResourceModel;


use Magento\Framework\Model\ResourceModel\Db\AbstractDb;

/**
 * @codeCoverageIgnore
 */
class Image extends AbstractDb
{
    const SCHEMA_NAME = 'magentando_flexslider_image';
    /**
     * @inheritDoc
     */
    protected function _construct()
    {
        $this->_init(static::SCHEMA_NAME, \Magentando\FlexSlider\Model\Image::IMAGE_ID);
    }

}
