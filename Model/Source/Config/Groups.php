<?php
/**
 * @copyright   2018 Magentando (http://www.magentando.com.br)
 * @license     http://www.magentando.com.br  Copyright
 * @author      Leandro Rosa <dev.leandrorosa@gmail.com>
 *
 * @link        http://www.magentando.com.br
 */

namespace Magentando\FlexSlider\Model\Source\Config;


use Magentando\FlexSlider\Api\Data\GroupInterface;
use Magentando\FlexSlider\Model\Group;
use Magentando\FlexSlider\Model\ResourceModel\Group\CollectionFactory;

/**
 * @codeCoverageIgnore
 */
class Groups implements \Magento\Framework\Option\ArrayInterface
{
    /** @var array  */
    protected $options = [];

    /** @var CollectionFactory  */
    protected $collectionFactory;

    /**
     * @param CollectionFactory $collectionFactory
     */
    public function __construct(
        CollectionFactory $collectionFactory
    )
    {
        $this->collectionFactory = $collectionFactory;
    }

    /**
     * @inheritdoc
     */
    public function toOptionArray()
    {
        if (empty($this->options)) {
            $options    = [];
            $collection = $this->collectionFactory->create();

            /** @var Group $group */
            foreach ($collection as $group) {
                $options[] = [
                    'value' => $group->getGroupId(),
                    'label' => $this->getLabel($group)
                ];
            }

            $this->options = $options;
        }

        return $this->options;
    }

    /**
     * @return array
     */
    public function toArray()
    {
        if (empty($this->options)) {
            $options    = [];
            $collection = $this->collectionFactory->create();

            /** @var Group $group */
            foreach ($collection as $group) {
                $options[$group->getGroupId()] = $this->getLabel($group);
            }

            $this->options = $options;
        }

        return $this->options;
    }

    /**
     * @param GroupInterface $group
     * @return string
     */
    protected function getLabel(GroupInterface $group)
    {
        return  $group->getTitle() . ' (' . $group->getIdentifier() . ')';
    }
}
