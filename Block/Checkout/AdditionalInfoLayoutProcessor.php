<?php

namespace Dublerq\SampleInputCheckout\Block\Checkout;

use Dublerq\SampleInputCheckout\Model\ExternalOrderId;
use Magento\Checkout\Block\Checkout\LayoutProcessorInterface;

class AdditionalInfoLayoutProcessor implements LayoutProcessorInterface
{
    /**
     * @inheritdoc
     */
    public function process($jsLayout)
    {
        $jsLayout['components']['checkout']['children']['steps']['children']['shipping-step']['children']
        ['shippingAddress']['children']['shipping-address-fieldset']['children']
        [ExternalOrderId::FIELD_EXTERNAL_ORDER_ID] = $this->getExternalIdFieldLayout();

        return $jsLayout;
    }

    private function getExternalIdFieldLayout() : array
    {
        return [
            'component' => 'Magento_Ui/js/form/element/abstract',
            'config' => [
                'customScope' => 'shippingAddress.custom_attributes',
                'customEntry' => null,
                'template' => 'ui/form/field',
                'elementTmpl' => 'ui/form/element/input',
                'id' => "input_custom_shipping_field"
            ],
            'dataScope' => 'shippingAddress.custom_attributes.' . ExternalOrderId::FIELD_EXTERNAL_ORDER_ID,
            'label' => 'External Order Id',
            'provider' => 'checkoutProvider',
            'sortOrder' => 400,
            'validation' => [
                'max_text_length' => ExternalOrderId::EXTERNAL_ORDER_ID_MAX_LENGTH
            ],
            'visible' => true,
        ];
    }
}
