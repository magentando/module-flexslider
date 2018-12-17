<?php
/**
 * @copyright   2018 Magentando (http://www.magentando.com.br)
 * @license     http://www.magentando.com.br  Copyright
 * @author      Leandro Rosa <dev.leandrorosa@gmail.com>
 *
 * @link        http://www.magentando.com.br
 */

namespace Magentando\FlexSlider\Controller\Adminhtml\Images;


use Magentando\FlexSlider\Api\ImageManagementInterface;
use Magentando\FlexSlider\Model\Image;
use Magentando\FlexSlider\Api\ImageUploaderManagementInterface;
use Magento\Backend\App\Action;
use Magento\Framework\Controller\ResultFactory;

/**
 * @codeCoverageIgnore
 */
class Save extends Action
{
    const ADMIN_RESOURCE = 'Magentando_FlexSlider::images';

    /** @var ImageManagementInterface  */
    protected $management;

    /** @var ImageUploaderManagementInterface  */
    protected $imageUploader;

    /**
     * Save constructor.
     * @param Action\Context $context
     * @param ImageManagementInterface $management
     * @param ImageUploaderManagementInterface $imageUploader
     */
    public function __construct(
        Action\Context $context,
        ImageManagementInterface $management,
        ImageUploaderManagementInterface $imageUploader
    )
    {
        $this->management = $management;
        $this->imageUploader = $imageUploader;
        parent::__construct($context);
    }

    /**
     * @inheritDoc
     */
    public function execute()
    {
        $request        = $this->getRequest();
        $resultRedirect = $this->resultFactory->create(ResultFactory::TYPE_REDIRECT);
        $imageRequest   = $request->getParam(Image::FILE_NAME);
        $imageId        = $request->getParam(Image::IMAGE_ID);
        $fileName       = null;

        try {
            if (isset($imageRequest[0]['name']) && isset($imageRequest[0]['tmp_name'])) {
                $fileName = $imageRequest[0]['name'];
                $this->imageUploader->moveFileFromTmp($fileName);
            }
        } catch (\Exception $exception) {
            $this->messageManager->addErrorMessage(__($exception->getMessage()));
            return $resultRedirect->setPath('*/*/edit', [
                Image::IMAGE_ID, $imageId,
                '_current' => true,
            ]);
        }

        if (isset($imageRequest[0]['image']) && ! isset($imageRequest[0]['tmp_name'])) {
            $fileName = $imageRequest[0]['image'];
        }

        $identifier = $request->getParam(Image::IDENTIFIER);
        $status     = $request->getParam(Image::STATUS);
        $title      = $request->getParam(Image::TITLE);
        $groupIds   = $request->getParam(Image::GROUP_IDS);

        if (! is_array($groupIds) && ! empty($groupIds)) {
            $groupIds = implode(',', $groupIds);
        }

        try {
            $this->management->create($identifier, $fileName, $status, $title, $groupIds, $imageId);
        } catch (\Exception $exception) {
            $this->messageManager->addErrorMessage(__($exception->getMessage()));

            return $resultRedirect->setPath('*/*/edit', [
                Image::IMAGE_ID, $imageId,
                '_current' => true,
            ]);
        }

        $this->messageManager->addSuccessMessage(__('Image has been saved.'));
        return $resultRedirect->setPath('*/*/');
    }
}
