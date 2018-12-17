<?php
/**
 * @copyright   2018 Magentando (http://www.magentando.com.br)
 * @license     http://www.magentando.com.br  Copyright
 * @author      Leandro Rosa <dev.leandrorosa@gmail.com>
 *
 * @link        http://www.magentando.com.br
 */

namespace Magentando\FlexSlider\Controller\Adminhtml\Images;


use Magentando\FlexSlider\Api\ImageUploaderManagementInterface;
use Magento\Backend\App\Action;
use Magento\Framework\Controller\ResultFactory;
use Magento\Backend\App\Action\Context;

class Upload extends Action
{
    /** @var ImageUploaderManagementInterface  */
    protected $imageUploader;

    /**
     * Upload constructor.
     * @param Context $context
     * @param ImageUploaderManagementInterface $imageUploader
     */
    public function __construct(
        Context $context,
        ImageUploaderManagementInterface $imageUploader
    ) {
        $this->imageUploader = $imageUploader;
        parent::__construct($context);
    }

    /**
     * @return \Magento\Framework\App\ResponseInterface|\Magento\Framework\Controller\ResultInterface
     */
    public function execute()
    {
        try {
            $result = $this->imageUploader->saveFileToTmpDir('file_name');
        } catch (\Exception $e) {
            $result = ['error' => $e->getMessage(), 'errorcode' => $e->getCode()];
        }

        $result['cookie'] = [
            'name' => $this->_getSession()->getName(),
            'value' => $this->_getSession()->getSessionId(),
            'lifetime' => $this->_getSession()->getCookieLifetime(),
            'path' => $this->_getSession()->getCookiePath(),
            'domain' => $this->_getSession()->getCookieDomain(),
        ];

        return $this->resultFactory->create(ResultFactory::TYPE_JSON)->setData($result);
    }
}
