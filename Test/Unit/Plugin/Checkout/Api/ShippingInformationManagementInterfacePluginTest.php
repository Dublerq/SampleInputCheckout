<?php

namespace Dublerq\SampleInputCheckout\Test\Unit\Plugin\Checkout\Api;

use Dublerq\SampleInputCheckout\Plugin\Checkout\Api\ShippingInformationManagementInterfacePlugin;
use Magento\Checkout\Api\Data\ShippingInformationExtensionInterface;
use Magento\Checkout\Api\Data\ShippingInformationInterface;
use Magento\Checkout\Model\ShippingInformationManagement;
use Magento\Quote\Api\CartRepositoryInterface;
use Magento\Quote\Api\Data\CartInterface;
use PHPUnit\Framework\TestCase;

class ShippingInformationManagementInterfacePluginTest extends TestCase
{
    /**
     * @var ShippingInformationManagementInterfacePlugin
     */
    private $plugin;

    /**
     * @var CartRepositoryInterface|\PHPUnit_Framework_MockObject_MockObject
     */
    private $cartRepositoryMock;

    public function setUp()
    {
        $om = new \Magento\Framework\TestFramework\Unit\Helper\ObjectManager($this);

        $this->cartRepositoryMock = $this->getMockBuilder(CartRepositoryInterface::class)
            ->getMockForAbstractClass();

        $this->plugin = $om->getObject(
            ShippingInformationManagementInterfacePlugin::class,
            [
                'cartRepository' => $this->cartRepositoryMock
            ]
        );
    }

    /**
     * @expectedException \Magento\Framework\Exception\InputException
     */
    public function testBeforeSaveAddressInformationThrowsExceptionForTooLongExternalOrderId()
    {
        /** @var ShippingInformationInterface|\PHPUnit_Framework_MockObject_MockObject $shippingInformationMock */
        $shippingInformationMock = $this->getMockBuilder(ShippingInformationInterface::class)
            ->getMockForAbstractClass();

        /**
         * @var ShippingInformationExtensionInterface|\PHPUnit_Framework_MockObject_MockObject $shippingInfoExtesionMock
         */
        $shippingInfoExtesionMock = $this->getMockBuilder(ShippingInformationExtensionInterface::class)
            ->setMethods(['getExternalOrderId'])
            ->getMockForAbstractClass();

        $shippingInformationMock->method('getExtensionAttributes')->willReturn($shippingInfoExtesionMock);
        $shippingInfoExtesionMock->method('getExternalOrderId')->willReturn(str_repeat('a', 41));

        /** @var ShippingInformationManagement|\PHPUnit_Framework_MockObject_MockObject $shippingInfoManagementMock */
        $shippingInfoManagementMock = $this->getMockBuilder(ShippingInformationManagement::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->plugin->beforeSaveAddressInformation($shippingInfoManagementMock, 123, $shippingInformationMock);
    }

    public function testBeforeSaveAddressInformationSetsExternalIdToQuote()
    {
        $cartId = 123;
        $externalOrderId = 'example_external_order_id';

        /** @var ShippingInformationInterface|\PHPUnit_Framework_MockObject_MockObject $shippingInformationMock */
        $shippingInformationMock = $this->getMockBuilder(ShippingInformationInterface::class)
            ->getMockForAbstractClass();

        /**
         * @var ShippingInformationExtensionInterface|\PHPUnit_Framework_MockObject_MockObject $shippingInfoExtesionMock
         */
        $shippingInfoExtesionMock = $this->getMockBuilder(ShippingInformationExtensionInterface::class)
            ->setMethods(['getExternalOrderId'])
            ->getMockForAbstractClass();

        $shippingInformationMock->method('getExtensionAttributes')->willReturn($shippingInfoExtesionMock);
        $shippingInfoExtesionMock->method('getExternalOrderId')->willReturn($externalOrderId);

        /** @var ShippingInformationManagement|\PHPUnit_Framework_MockObject_MockObject $shippingInfoManagementMock */
        $shippingInfoManagementMock = $this->getMockBuilder(ShippingInformationManagement::class)
            ->disableOriginalConstructor()
            ->getMock();

        /** @var CartInterface|\PHPUnit_Framework_MockObject_MockObject $quoteMock */
        $quoteMock = $this->getMockBuilder(CartInterface::class)
            ->setMethods(['setExternalOrderId'])
            ->getMockForAbstractClass();

        $this->cartRepositoryMock->method('getActive')->with($cartId)->willReturn($quoteMock);

        $quoteMock->expects($this->once())->method('setExternalOrderId')->with($externalOrderId);

        $this->plugin->beforeSaveAddressInformation($shippingInfoManagementMock, $cartId, $shippingInformationMock);
    }
}
