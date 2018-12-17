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


use Magentando\FlexSlider\Api\Data\GroupInterface;
use Magento\Framework\Model\AbstractModel;

/**
 * @codeCoverageIgnore
 */
class Group extends AbstractModel implements GroupInterface
{
    const STATUS_ENABLE     = 1;
    const STATUS_DISABLE    = 0;
    const GROUP_ID          = 'group_id';
    const IDENTIFIER        = 'identifier';
    const TITLE             = 'title';
    const STATUS            = 'status';
    const PROPERTIES        = 'properties';
    const CREATED_AT        = 'created_at';
    const UPDATED_AT        = 'updated_at';

    /**
     * @inheritDoc
     */
    public function getGroupId()
    {
        return (int) $this->getData(static::GROUP_ID);
    }

    /**
     * @inheritDoc
     */
    public function setGroupId($value)
    {
        return $this->setData(static::GROUP_ID, $value);
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
    public function getProperties()
    {
        return $this->getData(static::PROPERTIES);
    }

    /**
     * @inheritDoc
     */
    public function setProperties($value)
    {
        return $this->setData(static::PROPERTIES, $value);
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
        $this->_init(ResourceModel\Group::class);
    }
}
