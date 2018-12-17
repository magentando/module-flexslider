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
use Magento\Backend\App\Action;
use Magento\Framework\Controller\ResultFactory;

/**
 * @codeCoverageIgnore
 */
class Edit extends Action
{
    const ADMIN_RESOURCE = 'Magentando_FlexSlider::groups';

    protected $repository;

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
        /** @var \Magento\Backend\Model\View\Result\Page $resultPage */
        $resultPage = $this->resultFactory->create(ResultFactory::TYPE_PAGE);
        $groupId = $this->getRequest()->getParam('group_id');
        try {
            $group = $this->repository->get($groupId);
        } catch (\Exception $e) {
            $this->messageManager->addError(__('The requested group is not found'));
            return $this->resultRedirectFactory->create()->setPath('*/*/index');
        }

        $resultPage->setActiveMenu('Magentando_FlexSlider::groups_index');
        $resultPage->getConfig()->getTitle()->prepend(__('Edit: ') . $group->getTitle());
        return $resultPage;
    }
}
