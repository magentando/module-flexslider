<?php
/**
 * @copyright   2018 Magentando (http://www.magentando.com.br)
 * @license     http://www.magentando.com.br  Copyright
 * @author      Leandro Rosa <dev.leandrorosa@gmail.com>
 *
 * @link        http://www.magentando.com.br
 */

namespace Magentando\FlexSlider\Controller\Adminhtml\Images;


use Magentando\FlexSlider\Api\ImageRepositoryInterface;
use Magento\Backend\App\Action;
use Magento\Framework\Controller\ResultFactory;

/**
 * @codeCoverageIgnore
 */
class Edit extends Action
{
    const ADMIN_RESOURCE = 'Magentando_FlexSlider::images';

    /** @var ImageRepositoryInterface  */
    protected $repository;

    /**
     * @param Action\Context $context
     * @param ImageRepositoryInterface $repository
     */
    public function __construct(
        Action\Context $context,
        ImageRepositoryInterface $repository
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
        $imageId = $this->getRequest()->getParam('image_id');
        try {
            $image = $this->repository->get($imageId);
        } catch (\Exception $e) {
            $this->messageManager->addError(__('The requested image is not found'));
            return $this->resultRedirectFactory->create()->setPath('*/*/index');
        }

        $resultPage->setActiveMenu('Magentando_FlexSlider::images_index');
        $resultPage->getConfig()->getTitle()->prepend(__('Edit: ') . $image->getTitle());
        return $resultPage;
    }
}
