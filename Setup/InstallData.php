<?php

namespace Dublerq\SampleInputCheckout\Setup;

class InstallData implements \Magento\Framework\Setup\InstallDataInterface
{
    /**
     * @var \Magento\Framework\App\Config\ConfigResource\ConfigInterface
     */
    private $resourceConfig;

    public function __construct(
        \Magento\Framework\App\Config\ConfigResource\ConfigInterface $resourceConfig
    ) {
        $this->resourceConfig = $resourceConfig;
    }

    /**
     * @inheritdoc
     */
    // @codingStandardsIgnoreStart
    public function install(
        \Magento\Framework\Setup\ModuleDataSetupInterface $setup,
        \Magento\Framework\Setup\ModuleContextInterface $context
    ) {
        // @codingStandardsIgnoreStop
        $setup->startSetup();

        $this->resourceConfig->saveConfig(
            'checkout/options/guest_checkout',
            false,
            \Magento\Framework\App\Config\ScopeConfigInterface::SCOPE_TYPE_DEFAULT,
            \Magento\Store\Model\Store::DEFAULT_STORE_ID
        );

        $setup->endSetup();
    }
}