<?php
/**
 *
 * @copyright   2018 Magentando (http://www.magentando.com.br)
 * @license     http://www.magentando.com.br  Copyright
 * @author      Leandro Rosa <dev.leandrorosa@gmail.com>
 *
 * @link        http://www.magentando.com.br
 */

namespace Magentando\FlexSlider\Setup;


use Magento\Framework\Setup\InstallSchemaInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\SchemaSetupInterface;
use Magentando\FlexSlider\Model\ResourceModel\Image as ResourceImage;
use Magentando\FlexSlider\Model\Image as ModelImage;
use Magentando\FlexSlider\Model\ResourceModel\Group as ResourceGroup;
use Magentando\FlexSlider\Model\Group as ModelGroup;

/**
 * @codeCoverageIgnore
 */
class InstallSchema implements InstallSchemaInterface
{
    /**
     * @param SchemaSetupInterface $setup
     * @param ModuleContextInterface $context
     * @throws \Zend_Db_Exception
     */
    public function install(SchemaSetupInterface $setup, ModuleContextInterface $context)
    {
        $setup->startSetup();

        if (version_compare($context->getVersion(), '0.1.0') < 0) {
            $this->install010($setup);
        }

        $setup->endSetup();
    }

    /**
     * @param SchemaSetupInterface $setup
     * @throws \Zend_Db_Exception
     */
    protected function install010(SchemaSetupInterface $setup)
    {
        /*
         * Group Table
         */
        $table = $setup->getConnection()->newTable(
            $setup->getTable(ResourceGroup::SCHEMA_NAME)
        )->addColumn(
            ModelGroup::GROUP_ID,
            \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
            null,
            ['identity' => true, 'nullable' => false, 'primary' => true],
            'Group ID'
        )->addColumn(
            ModelGroup::IDENTIFIER,
            \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
            255,
            ['nullable' => false, 'unique' => true],
            'Identifier'
        )->addColumn(
            ModelGroup::TITLE,
            \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
            255,
            [],
            'Title'
        )->addColumn(
            ModelGroup::STATUS,
            \Magento\Framework\DB\Ddl\Table::TYPE_BOOLEAN,
            null,
            ['default' => 1],
            'Status'
        )->addColumn(
            ModelGroup::PROPERTIES,
            \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
            null,
            [],
            'Properties'
        )->addColumn(
            ModelGroup::CREATED_AT,
            \Magento\Framework\DB\Ddl\Table::TYPE_TIMESTAMP,
            null,
            ['nullable' => false, 'default' => \Magento\Framework\DB\Ddl\Table::TIMESTAMP_INIT],
            'Created Time'
        )->addColumn(
            ModelGroup::UPDATED_AT,
            \Magento\Framework\DB\Ddl\Table::TYPE_TIMESTAMP,
            null,
            ['nullable' => false, 'default' => \Magento\Framework\DB\Ddl\Table::TIMESTAMP_INIT_UPDATE],
            'Update Time'
        )->addIndex(
            $setup->getIdxName(
                ResourceGroup::SCHEMA_NAME,
                [ModelGroup::IDENTIFIER]
            ),
            [ModelGroup::IDENTIFIER]
        );

        $setup->getConnection()->createTable($table);


        /*
        * Image Table
        */
        $table = $setup->getConnection()->newTable(
            $setup->getTable(ResourceImage::SCHEMA_NAME)
        )->addColumn(
            ModelImage::IMAGE_ID,
            \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
            null,
            ['identity' => true, 'nullable' => false, 'primary' => true],
            'Image ID'
        )->addColumn(
            ModelImage::IDENTIFIER,
            \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
            255,
            ['nullable' => false, 'unique' => true],
            'Identifier'
        )->addColumn(
            ModelImage::TITLE,
            \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
            255,
            [],
            'Title'
        )->addColumn(
            ModelImage::FILE_NAME,
            \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
            255,
            ['nullable' => false],
            'File name'
        )->addColumn(
            ModelImage::GROUP_IDS,
            \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
            null,
            [],
            'Group IDS'
        )->addColumn(
            ModelImage::STATUS,
            \Magento\Framework\DB\Ddl\Table::TYPE_BOOLEAN,
            null,
            ['default' => 1],
            'Status'
        )->addColumn(
            ModelImage::CREATED_AT,
            \Magento\Framework\DB\Ddl\Table::TYPE_TIMESTAMP,
            null,
            ['nullable' => false, 'default' => \Magento\Framework\DB\Ddl\Table::TIMESTAMP_INIT],
            'Created Time'
        )->addColumn(
            ModelImage::UPDATED_AT,
            \Magento\Framework\DB\Ddl\Table::TYPE_TIMESTAMP,
            null,
            ['nullable' => false, 'default' => \Magento\Framework\DB\Ddl\Table::TIMESTAMP_INIT_UPDATE],
            'Update Time'
        )->addIndex(
            $setup->getIdxName(
                ResourceImage::SCHEMA_NAME,
                [ModelImage::IDENTIFIER]
            ),
            [ModelImage::IDENTIFIER]
        );

        $setup->getConnection()->createTable($table);
    }
}
