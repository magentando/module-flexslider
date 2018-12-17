<?php
/**
 * @copyright   2018 Magentando (http://www.magentando.com.br)
 * @license     http://www.magentando.com.br  Copyright
 * @author      Leandro Rosa <dev.leandrorosa@gmail.com>
 *
 * @link        http://www.magentando.com.br
 */

namespace Magentando\FlexSlider\Controller\Adminhtml\Groups;


use Magentando\FlexSlider\Api\GroupManagementInterface;
use Magentando\FlexSlider\Model\Group;
use Magento\Backend\App\Action;
use Magento\Framework\Controller\ResultFactory;
use Magento\Framework\Message\Manager;

/**
 * @codeCoverageIgnore
 */
class Save extends Action
{
    const ADMIN_RESOURCE = 'Magentando_FlexSlider::groups';

    /** @var GroupManagementInterface  */
    protected $management;

    /**
     * Save constructor.
     * @param Action\Context $context
     * @param GroupManagementInterface $management
     */
    public function __construct(
        Action\Context $context,
        GroupManagementInterface $management
    )
    {
        $this->management       = $management;
        parent::__construct($context);
    }

    /**
     * @inheritDoc
     */
    public function execute()
    {
        $request        = $this->getRequest();
        $resultRedirect = $this->resultFactory->create(ResultFactory::TYPE_REDIRECT);
        $groupId        = $request->getParam(Group::GROUP_ID);
        $identifier     = $request->getParam(Group::IDENTIFIER);
        $status         = $request->getParam(Group::STATUS);
        $title          = $request->getParam(Group::TITLE);
        $properties     = $request->getParam(Group::PROPERTIES);

        try {
            $this->management->create($identifier, $status, $properties, $title, $groupId);
        } catch (\Exception $exception) {
            $this->messageManager->addErrorMessage(__($exception->getMessage()));

            return $resultRedirect->setPath('*/*/edit', [
                Group::GROUP_ID, $groupId,
                '_current' => true,
            ]);
        }

        $this->messageManager->addSuccessMessage(__('Group has been saved.'));
        return $resultRedirect->setPath('*/*/');
    }
}
