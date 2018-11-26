<?php

namespace Dublerq\SampleInputCheckout\Plugin\Checkout\Api;

use Dublerq\SampleInputCheckout\Model\ExternalOrderId;
use Magento\Framework\Exception\InputException;
use Magento\Quote\Api\CartRepositoryInterface;

class ShippingInformationManagementInterfacePlugin
{
    /**
     * @var CartRepositoryInterface
     */
    private $cartRepository;

    /**
     * @param CartRepositoryInterface $cartRepository
     */

    public function __construct(
        CartRepositoryInterface $cartRepository
    ) {
        $this->cartRepository = $cartRepository;
    }

    /**
     * @param \Magento\Checkout\Model\ShippingInformationManagement $subject
     * @param $cartId
     * @param \Magento\Checkout\Api\Data\ShippingInformationInterface $addressInformation
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     * @throws InputException
     */
    // @codingStandardsIgnoreStart
    public function beforeSaveAddressInformation(
        \Magento\Checkout\Model\ShippingInformationManagement $subject,
        $cartId,
        \Magento\Checkout\Api\Data\ShippingInformationInterface $addressInformation
    ) {
        // @codingStandardsIgnoreStop
        if (!$shippingExtensionAttributes = $addressInformation->getExtensionAttributes()) {
            return;
        }

        $this->validateExternalOrderId($shippingExtensionAttributes);
        $this->saveExternalOrderIdToQuote($cartId, $shippingExtensionAttributes);
    }

    /**
     * @param \Magento\Checkout\Api\Data\ShippingInformationExtensionInterface|null $shippingExtensionAttributes
     * @throws InputException
     */
    private function validateExternalOrderId(
        \Magento\Checkout\Api\Data\ShippingInformationExtensionInterface $shippingExtensionAttributes
    ) {
        $trimmedExternalOrderId = trim($shippingExtensionAttributes->getExternalOrderId());

        if (strlen($trimmedExternalOrderId) > ExternalOrderId::EXTERNAL_ORDER_ID_MAX_LENGTH) {
            throw new InputException(__('External Order Id should not be longer than 40 characters.'));
        }
    }

    /**
     * @param $cartId
     * @param \Magento\Checkout\Api\Data\ShippingInformationExtensionInterface $shippingExtensionAttributes
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    private function saveExternalOrderIdToQuote(
        $cartId,
        \Magento\Checkout\Api\Data\ShippingInformationExtensionInterface $shippingExtensionAttributes
    ) {
        $quote = $this->cartRepository->getActive($cartId);
        $quote->setExternalOrderId($shippingExtensionAttributes->getExternalOrderId());
    }
}
