<?php
/**
 *
 * @copyright   2018 Magentando (http://www.magentando.com.br)
 * @license     http://www.magentando.com.br  Copyright
 * @author      Leandro Rosa <dev.leandrorosa@gmail.com>
 *
 * @link        http://www.magentando.com.br
 */

namespace Magentando\FlexSlider\Api\Data;


interface ImageInterface extends \Magento\Framework\Api\ExtensibleDataInterface
{
    /**
     * @return int|null
     */
    public function getImageId();

    /**
     * @param int $value
     * @return self
     */
    public function setImageId($value);

    /**
     * @return string|null
     */
    public function getIdentifier();

    /**
     * @param string $value
     * @return self
     */
    public function setIdentifier($value);

    /**
     * @return string|null
     */
    public function getTitle();

    /**
     * @param string $value
     * @return self
     */
    public function setTitle($value);

    /**
     * @return string|null
     */
    public function getFileName();

    /**
     * @param string $value
     * @return self
     */
    public function setFileName($value);

    /**
     * @return bool
     */
    public function getStatus();

    /**
     * @param bool $value
     * @return self
     */
    public function setStatus($value);

    /**
     * @return array
     */
    public function getGroupIds();

    /**
     * @param array $value
     * @return self
     */
    public function setGroupIds(array $value);
    /**
     * @return string|null
     */
    public function getCreatedAt();

    /**
     * @param string $value
     * @return self
     */
    public function setCreatedAt($value);

    /**
     * @return string|null
     */
    public function getUpdatedAt();

    /**
     * @param string $value
     * @return self
     */
    public function setUpdatedAt($value);
}
