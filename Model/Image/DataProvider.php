<?php
/**
 *
 * @copyright   2018 Magentando (http://www.magentando.com.br)
 * @license     http://www.magentando.com.br  Copyright
 * @author      Leandro Rosa <dev.leandrorosa@gmail.com>
 *
 * @link        http://www.magentando.com.br
 */

namespace Magentando\FlexSlider\Model\Image;


use Magentando\FlexSlider\Helper\Data;
use Magentando\FlexSlider\Model\ResourceModel\Image\CollectionFactory;
use Magentando\FlexSlider\Api\Data\ImageInterface;
use Magentando\FlexSlider\Model\Image;
use Magento\Store\Model\StoreManagerInterface;

/**
 * @codeCoverageIgnore
 */
class DataProvider extends \Magento\Ui\DataProvider\AbstractDataProvider
{
    /** @var array  */
    protected $loadedData = [];

    /** @var StoreManagerInterface  */
    protected $storeManager;

    /**
     * @param $name
     * @param $primaryFieldName
     * @param $requestFieldName
     * @param CollectionFactory $collectionFactory
     * @param array $meta
     * @param array $data
     */
    public function __construct(
        $name,
        $primaryFieldName,
        $requestFieldName,
        CollectionFactory $collectionFactory,
        StoreManagerInterface $storeManager,
        array $meta = [],
        array $data = []
    ) {
        $this->storeManager = $storeManager;
        $this->collection = $collectionFactory->create();
        parent::__construct($name, $primaryFieldName, $requestFieldName, $meta, $data);
    }

    /**
     * @return array
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function getData()
    {
        if (! empty($this->loadedData)) {
            return $this->loadedData;
        }

        /** @var ImageInterface $item */
        foreach ($this->getCollection() as $item) {

            $image = [];
            if($item->getFileName()) {
                $baseurl            =  $this->storeManager->getStore()->getBaseUrl(\Magento\Framework\UrlInterface::URL_TYPE_MEDIA);
                $fileName           = $item->getFileName();
                $image[0]['image']  = $fileName;
                $image[0]['url']    = $baseurl . Data::BASE_PATH . '/'.$fileName;
            }

            $this->loadedData[$item->getImageId()] = [
                Image::IMAGE_ID     => $item->getImageId(),
                Image::IDENTIFIER   => $item->getIdentifier(),
                Image::TITLE        => $item->getTitle(),
                Image::STATUS       => $item->getStatus(),
                Image::GROUP_IDS    => $item->getGroupIds(),
                Image::FILE_NAME    => $image
            ];
        }

        return $this->loadedData;
    }
}
