<?php
/**
 * @copyright   2018 Magentando (http://www.magentando.com.br)
 * @license     http://www.magentando.com.br  Copyright
 * @author      Leandro Rosa <dev.leandrorosa@gmail.com>
 *
 * @link        http://www.magentando.com.br
 */

namespace Magentando\FlexSlider\Block\Widget;


use Magentando\FlexSlider\Api\GroupManagementInterface;
use Magento\Framework\View\Element\Template;
use Magento\Widget\Block\BlockInterface;

/**
 * @codeCoverageIgnore
 */
class FlexSlider extends Template implements BlockInterface
{
    /** @var string  */
    protected $_template = "Magentando_FlexSlider::widget/flexslider.phtml";

    /** @var \Magentando\FlexSlider\Api\Data\GroupInterface */
    protected $group;


    /** @var GroupManagementInterface  */
    protected $management;

    /**
     * FlexSlider constructor.
     * @param GroupManagementInterface $management
     * @param Template\Context $context
     * @param array $data
     */
    public function __construct(
        GroupManagementInterface $management,
        Template\Context $context,
        array $data = []
    )
    {
        $this->management = $management;
        parent::__construct($context, $data);
    }

    /**
     * @return \Magentando\FlexSlider\Api\Data\GroupInterface
     */
    public function getGroup()
    {
        if (! $this->group) {
            $this->group = $this->management->getGroupByIdentifier($this->getData('group_identifier'));
        }

        return $this->group;
    }

    /**
     * @return null|string
     */
    public function getProperties()
    {
        return $this->getGroup()->getProperties() ?? '{}';
    }

    /**
     * @return \Magentando\FlexSlider\Api\Data\ImageInterface[]
     */
    public function getImages()
    {
        return $this->management->getImages($this->getGroup());
    }
}
