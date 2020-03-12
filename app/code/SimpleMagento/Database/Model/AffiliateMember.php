<?php


namespace SimpleMagento\Database\Model;

use Magento\Framework\Model\AbstractExtensibleModel;
use Magento\Framework\Model\AbstractModel;
use SimpleMagento\Database\Api\Data\SimpleMagento;
use SimpleMagento\Database\Model\ResourceModel\AffiliateMember as AffiliateMemberResource;
use SimpleMagento\Database\Api\Data\AffiliateMemberInterface;

class AffiliateMember extends AbstractExtensibleModel implements AffiliateMemberInterface
{
    protected function _construct()
    {
        $this->_init(AffiliateMemberResource::class);
    }
    /**
     * @return string
     */
    public function getName()
    {
        // TODO: Implement getName() method.
        return $this->getData(AffiliateMemberInterface::NAME);
    }

    /**
     * @return boolean
     */
    public function getStatus()
    {
        // TODO: Implement getStatus() method.
        return $this->getData(AffiliateMemberInterface::STATUS);
    }

    /**
     * @return string
     */
    public function getAddress()
    {
        // TODO: Implement getAddress() method.
        return $this->getData(AffiliateMemberInterface::ADDRESS);
    }

    /**
     * @return string
     */
    public function getPhoneNumber()
    {
        // TODO: Implement getPhoneNumber() method.
        return $this->getData(AffiliateMemberInterface::PHONE_NUMBER);
    }

    /**
     * @return string
     */
    public function getCreatedAt()
    {
        // TODO: Implement getCreatedAt() method.
        return $this->getData(AffiliateMemberInterface::CREATED_AT);
    }

    /**
     * @return string
     */
    public function getUpdatedAt()
    {
        // TODO: Implement getUpdatedAt() method.
        return $this->getData(AffiliateMemberInterface::UPDATED_AT);
    }

    public function getId()
    {
        // TODO: Implement getUpdatedAt() method.
        return $this->getData(AffiliateMemberInterface::ID);
    }

    /**
     * @param string $name
     * @return \SimpleMagento\Database\Api\Data\AffiliateMemberInterface
     */
    public function setName($name)
    {
        // TODO: Implement setName() method.
        $this->setData(AffiliateMemberInterface::NAME, $name);
    }

    /**
     * @param boolean $status
     * @return \SimpleMagento\Database\Api\Data\AffiliateMemberInterface
     */
    public function setStatus($status)
    {
        // TODO: Implement setStatus() method.
        $this->setData(AffiliateMemberInterface::STATUS, $status);
    }

    /**
     * @param string $address
     * @return \SimpleMagento\Database\Api\Data\AffiliateMemberInterface
     */
    public function setAddress($address)
    {
        // TODO: Implement setAddress() method.
        $this->setData(AffiliateMemberInterface::ADDRESS, $address);
    }

    /**
     * @param string $phoneNumber
     * @return \SimpleMagento\Database\Api\Data\AffiliateMemberInterface
     */
    public function setPhoneNumber($phoneNumber)
    {
        // TODO: Implement setPhoneNumber() method.
        $this->setData(AffiliateMemberInterface::PHONE_NUMBER, $phoneNumber);
    }

    /**
     * @return \SimpleMagento\Database\Api\Data\AffiliateMemberExtensionInterface
     */
    public function getExtensionAttributes()
    {
        return $this->getExtensionAttributes();
    }

    /**
     * @param \SimpleMagento\Database\Api\Data\AffiliateMemberExtensionInterface $affiliateMemberExtension
     * @return $this
     */
    public function setExtensionAttributes(\SimpleMagento\Database\Api\Data\AffiliateMemberExtensionInterface $affiliateMemberExtension)
    {
        return $this->setExtensionAttributes($affiliateMemberExtension);
    }
}