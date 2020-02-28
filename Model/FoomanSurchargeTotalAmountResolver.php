<?php

namespace Ambientia\CollectorFoomanSurcharge\Model;

use Magento\Framework\Api\ExtensionAttributesInterface;

class FoomanSurchargeTotalAmountResolver
{
    /**
     * @param ExtensionAttributesInterface $extensionAttributes
     * @return int $totalAmount
     */
    public function getTotalAmount(ExtensionAttributesInterface $extensionAttributes)
    {
        $totalAmount = 0;
        if (!method_exists($extensionAttributes, 'getFoomanTotalGroup')) {
            return 0;
        }
        if ($totalGroup = $extensionAttributes->getFoomanTotalGroup()) {
            if ($totalItems = $totalGroup->getItems()) {
                foreach ($totalItems as $totalItem) {
                    $totalAmount += $totalItem->getTaxAmount();
                    $totalAmount += $totalItem->getAmount();
                }
            }
        }
        return $totalAmount;
    }
}
