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


use Magentando\FlexSlider\Api\Data\ImageInterface;
use Magento\Framework\Model\AbstractModel;

/**
 * @codeCoverageIgnore
 */
class Image extends AbstractModel implements ImageInterface
{
    const STATUS_ENABLE     = 1;
    const STATUS_DISABLE    = 0;
    const IMAGE_ID          = 'image_id';
    const IDENTIFIER        = 'identifier';
    const TITLE             = 'title';
    const FILE_NAME         = 'file_name';
    const STATUS            = 'status';
    const CREATED_AT        = 'created_at';
    const UPDATED_AT        = 'updated_at';
    const GROUP_IDS         = 'group_ids';

    /**
     * @inheritDoc
     */
    public function getImageId()
    {
        return (int) $this->getData(static::IMAGE_ID);
    }

    /**
     * @inheritDoc
     */
    public function setImageId($value)
    {
        return $this->setData(static::IMAGE_ID, $value);
    }

    /**
     * @inheritDoc
     */
    public function getIdentifier()
    {
        return $this->getData(static::IDENTIFIER);
    }

    /**
     * @inheritDoc
     */
    public function setIdentifier($value)
    {
        return $this->setData(static::IDENTIFIER, $value);
    }

    /**
     * @inheritDoc
     */
    public function getTitle()
    {
        return $this->getData(static::TITLE);
    }

    /**
     * @inheritDoc
     */
    public function setTitle($value)
    {
        return $this->setData(static::TITLE, $value);
    }

    /**
     * @inheritDoc
     */
    public function getFileName()
    {
        return $this->getData(static::FILE_NAME);
    }

    /**
     * @inheritDoc
     */
    public function setFileName($value)
    {
        return $this->setData(static::FILE_NAME, $value);
    }

    /**
     * @inheritDoc
     */
    public function getStatus()
    {
        return (bool) $this->getData(static::STATUS);
    }

    /**
     * @inheritDoc
     */
    public function setStatus($value)
    {
        return $this->setData(static::STATUS, $value);
    }

    /**
     * @inheritDoc
     */
    public function getGroupIds()
    {
        return explode(',', $this->getData(static::GROUP_IDS));
    }

    /**
     * @inheritDoc
     */
    public function setGroupIds(array $value)
    {
        return $this->setData(static::GROUP_IDS, implode(',', $value));
    }


    /**
     * @inheritDoc
     */
    public function getCreatedAt()
    {
        return $this->getData(static::CREATED_AT);
    }

    /**
     * @inheritDoc
     */
    public function setCreatedAt($value)
    {
        return $this->setData(static::CREATED_AT, $value);
    }

    /**
     * @inheritDoc
     */
    public function getUpdatedAt()
    {
        return $this->getData(static::UPDATED_AT);
    }

    /**
     * @inheritDoc
     */
    public function setUpdatedAt($value)
    {
        return $this->setData(static::UPDATED_AT, $value);
    }

    /**
     * @inheritDoc
     */
    protected function _construct()
    {
        $this->_init(ResourceModel\Image::class);
    }
}
