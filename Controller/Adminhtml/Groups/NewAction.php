<?php
/**
 * @copyright   2018 Magentando (http://www.magentando.com.br)
 * @license     http://www.magentando.com.br  Copyright
 * @author      Leandro Rosa <dev.leandrorosa@gmail.com>
 *
 * @link        http://www.magentando.com.br
 */

namespace Magentando\FlexSlider\Controller\Adminhtml\Groups;


use Magento\Backend\App\Action;
use Magento\Framework\Controller\ResultFactory;

/**
 * @codeCoverageIgnore
 */
class NewAction extends Action
{
    const ADMIN_RESOURCE = 'Magentando_FlexSlider::groups';
    /**
     * New company action
     *
     * @return \Magento\Backend\Model\View\Result\Page
     */
    public function execute()
    {
        /** @var \Magento\Backend\Model\View\Result\Page $resultPage */
        $resultPage = $this->resultFactory->create(ResultFactory::TYPE_PAGE);
        $resultPage->setActiveMenu('Magentando_FlexSlider::groups_index');
        $resultPage->getConfig()->getTitle()->prepend(__('New Group'));
        return $resultPage;
    }
}
