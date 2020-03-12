<?php


namespace SimpleMagento\ProductExtension\Model\Plugin;


class ProductExt
{
    public function afterGet
    (
        \Magento\Catalog\Api\ProductRepositoryInterface $subject,
        \Magento\Catalog\Api\Data\ProductInterface $entity
    ) {
        $ourCustomData = $this->customDataRepository->get($entity->getId());

        $extensionAttributes = $entity->getExtensionAttributes(); /** get current extension attributes from entity **/
        $extensionAttributes->setOurCustomData($ourCustomData);
        $entity->setExtensionAttributes($extensionAttributes);

        return $entity;
    }
}