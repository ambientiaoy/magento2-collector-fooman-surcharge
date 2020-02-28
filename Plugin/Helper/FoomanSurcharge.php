<?php

namespace Ambientia\CollectorFoomanSurcharge\Plugin\Helper;

use Ambientia\CollectorFoomanSurcharge\Model\FoomanSurchargeTotalAmountResolver;
use Magento\Framework\Api\ExtensionAttributesInterface;
use Magento\Quote\Api\CartRepositoryInterface;

class FoomanSurcharge
{
    /**
     * @var CartRepositoryInterface
     */
    private $cartRepository;
    /**
     * @var \FoomanSurchargeTotalAmountResolver
     */
    private $totalAmountResolver;

    /**
     * FoomanSurcharge constructor.
     * @param CartRepositoryInterface $cartRepository
     * @param FoomanSurchargeTotalAmountResolver $totalAmountResolver
     */
    public function __construct(CartRepositoryInterface $cartRepository, FoomanSurchargeTotalAmountResolver $totalAmountResolver)
    {
        $this->cartRepository = $cartRepository;
        $this->totalAmountResolver = $totalAmountResolver;
    }

    /**
     * @param \Customweb\CollectorCw\Helper\FoomanSurcharge $subject
     * @param callable $proceed
     * @param int $quoteId
     * @return int
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function aroundGetQuoteSurchargeAmount(\Customweb\CollectorCw\Helper\FoomanSurcharge $subject, callable $proceed, int $quoteId)
    {
        if (!$subject->isModuleActive()) {
            return 0;
        }
        $quote = $this->cartRepository->get($quoteId);
        $address = $quote->getShippingAddress() ? $quote->getShippingAddress() : $quote->getBillingAddress();
        if ($extensionAttributes = $address->getExtensionAttributes()) {
            return $this->totalAmountResolver->getTotalAmount($extensionAttributes);
        }
        return 0;
    }

    /**
     * @param \Customweb\CollectorCw\Helper\FoomanSurcharge $subject
     * @param callable $proceed
     * @param \Magento\Sales\Model\Order $order
     * @return int
     */
    public function aroundGetOrderSurchargeAmount(\Customweb\CollectorCw\Helper\FoomanSurcharge $subject, callable $proceed, \Magento\Sales\Model\Order $order)
    {
        if (!$subject->isModuleActive()) {
            return 0;
        }
        if ($extensionAttributes = $order->getExtensionAttributes()) {
            return $this->totalAmountResolver->getTotalAmount($extensionAttributes);
        }
        return 0;
    }
}
