<?php
/**
 *
 * @copyright   2018 Magentando (http://www.magentando.com.br)
 * @license     http://www.magentando.com.br  Copyright
 * @author      Leandro Rosa <dev.leandrorosa@gmail.com>
 *
 * @link        http://www.magentando.com.br
 */

namespace Magentando\FlexSlider\Model\Group;


use Magentando\FlexSlider\Model\ResourceModel\Group\CollectionFactory;
use Magentando\FlexSlider\Api\Data\GroupInterface;
use Magentando\FlexSlider\Model\Group;

/**
 * @codeCoverageIgnore
 */
class DataProvider extends \Magento\Ui\DataProvider\AbstractDataProvider
{
    protected $loadedData = [];

    /**
     * @param $name
     * @param $primaryFieldName
     * @param $requestFieldName
     * @param CollectionFactory $collectionFactory
     * @param array $meta
     * @param array $data
     */
    public function __construct(
        $name,
        $primaryFieldName,
        $requestFieldName,
        CollectionFactory $collectionFactory,
        array $meta = [],
        array $data = []
    ) {
        $this->collection = $collectionFactory->create();
        parent::__construct($name, $primaryFieldName, $requestFieldName, $meta, $data);
    }

    /**
     * @return array
     */
    public function getData()
    {
        if (! empty($this->loadedData)) {
            return $this->loadedData;
        }

        /** @var GroupInterface $item */
        foreach ($this->getCollection() as $item) {
            $this->loadedData[$item->getGroupId()] = [
                Group::GROUP_ID     => $item->getGroupId(),
                Group::IDENTIFIER   => $item->getIdentifier(),
                Group::TITLE        => $item->getTitle(),
                Group::STATUS       => $item->getStatus(),
                Group::PROPERTIES   => $item->getProperties()
            ];
        }

        return $this->loadedData;
    }
}
