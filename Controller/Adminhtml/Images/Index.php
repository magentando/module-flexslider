<?php
/**
 * @copyright   2018 Magentando (http://www.magentando.com.br)
 * @license     http://www.magentando.com.br  Copyright
 * @author      Leandro Rosa <dev.leandrorosa@gmail.com>
 *
 * @link        http://www.magentando.com.br
 */

namespace Magentando\FlexSlider\Controller\Adminhtml\Images;


use Magento\Backend\App\Action;
use Magento\Framework\Controller\ResultFactory;

/**
 * @codeCoverageIgnore
 */
class Index extends Action
{
    const ADMIN_RESOURCE = 'Magentando_FlexSlider::images';

    /**
     * @inheritDoc
     */
    public function execute()
    {
        /** @var \Magento\Backend\Model\View\Result\Page $resultPage */
        $resultPage = $this->resultFactory->create(ResultFactory::TYPE_PAGE);
        $resultPage->setActiveMenu('Magentando_FlexSlider::images_index');
        $resultPage->getConfig()->getTitle()->prepend(__('Images'));
        $resultPage->addBreadcrumb(__('FlexSlider'), __('Images'));
        return $resultPage;
    }
}

