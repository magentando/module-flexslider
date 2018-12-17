<?php
/**
 * @copyright   2018 Magentando (http://www.magentando.com.br)
 * @license     http://www.magentando.com.br  Copyright
 * @author      Leandro Rosa <dev.leandrorosa@gmail.com>
 *
 * @link        http://www.magentando.com.br
 */

namespace Magentando\FlexSlider\Controller\Adminhtml\Groups;


use Magentando\FlexSlider\Api\GroupRepositoryInterface;
use Magentando\FlexSlider\Model\Group;
use Magento\Backend\App\Action;
use Magento\Framework\Controller\ResultFactory;
use Magento\Framework\Message\Manager;

/**
 * @codeCoverageIgnore
 */
class Delete extends Action
{
    const ADMIN_RESOURCE = 'Magentando_FlexSlider::groups';

    /** @var GroupRepositoryInterface  */
    protected $repository;

    /**
     * @param Action\Context $context
     * @param GroupRepositoryInterface $repository
     * @param Manager $messageManager
     */
    public function __construct(
        Action\Context $context,
        GroupRepositoryInterface $repository
    )
    {
        $this->repository = $repository;
        parent::__construct($context);
    }

    /**
     * @inheritDoc
     */
    public function execute()
    {
        $resultRedirect = $this->resultFactory->create(ResultFactory::TYPE_REDIRECT);

        try {
            $this->repository->deleteById($this->getRequest()->getParam(Group::GROUP_ID));
            $this->messageManager->addSuccessMessage(__('Group has been deleted.'));
        } catch (\Exception $exception) {
            $this->messageManager->addErrorMessage(__($exception->getMessage()));
        }

        return $resultRedirect->setPath('*/*/');
    }
}
