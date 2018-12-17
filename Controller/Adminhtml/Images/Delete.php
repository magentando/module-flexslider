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
use Magentando\FlexSlider\Model\Image;
use Magento\Backend\App\Action;
use Magento\Framework\Controller\ResultFactory;
use Magento\Framework\Message\Manager;

/**
 * @codeCoverageIgnore
 */
class Delete extends Action
{
    const ADMIN_RESOURCE = 'Magentando_FlexSlider::images';

    /** @var ImageRepositoryInterface  */
    protected $repository;

    /**
     * @param Action\Context $context
     * @param ImageRepositoryInterface $repository
     * @param Manager $messageManager
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
        $resultRedirect = $this->resultFactory->create(ResultFactory::TYPE_REDIRECT);

        try {
            $this->repository->deleteById($this->getRequest()->getParam(Image::IMAGE_ID));
            $this->messageManager->addSuccessMessage(__('Image has been deleted.'));
        } catch (\Exception $exception) {
            $this->messageManager->addErrorMessage(__($exception->getMessage()));
        }

        return $resultRedirect->setPath('*/*/');
    }
}
