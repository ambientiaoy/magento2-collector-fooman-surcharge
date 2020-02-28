<?php

namespace Ambientia\CollectorFoomanSurcharge\Plugin\Helper;

class InvoiceItem
{
    /**
     * @param \Customweb\CollectorCw\Helper\InvoiceItem $subject
     * @param array $result
     * @param array $items
     * @return array
     */
    public function afterGetInvoiceItems(
        \Customweb\CollectorCw\Helper\InvoiceItem $subject,
        array $result,
        array $items
    ) {
        $order = null;
        $firstItem = array_shift($items);
        if ($firstItem->getOrder()) {
            $order = $firstItem->getOrder();
        }
        else if ($firstItem->getOrderItem()) {
            $order = $firstItem->getOrderItem()->getOrder();
        }

        if ($order && $extensionAttributes = $order->getExtensionAttributes()) {
            if ($orderTotalGroup = $extensionAttributes->getFoomanTotalGroup()) {
                if ($totalItems = $orderTotalGroup->getItems()) {
                    foreach ($totalItems as $totalItem) {
                        $totalTaxAmount = $totalItem->getTaxAmount();
                        $totalAmount = $totalItem->getAmount() + $totalTaxAmount;
                        $totalLabel = $totalItem->getLabel();
                        if ($totalTaxAmount > 0) {
                            $surchargeTaxRate = round(abs($totalTaxAmount) / (abs($totalAmount) - abs($totalTaxAmount)) * 100);
                        } else {
                            $surchargeTaxRate = 0;
                        }
                        $surcharge = new \Customweb_Payment_Authorization_DefaultInvoiceItem(
                            'surcharge',
                            $totalLabel,
                            $surchargeTaxRate,
                            (double)$totalAmount,
                            1,
                            \Customweb_Payment_Authorization_IInvoiceItem::TYPE_FEE
                        );
                        $result[] = $surcharge;
                    }
                }
            }
        }

        // Remove the originally added incorrect Fooman Surcharge invoice items
        foreach ($result as $key => $invoiceItem) {
            if ($invoiceItem->getSku() == 'fooman_surcharge') {
                unset($result[$key]);
            }
        }

        return $result;
    }
}
