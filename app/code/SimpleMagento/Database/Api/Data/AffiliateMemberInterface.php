<?php


namespace SimpleMagento\Database\Api\Data;




interface AffiliateMemberInterface
{
    const NAME = "name";
    const ID = "entity_end";
    const STATUS = "status";
    const ADDRESS = "address";
    const PHONE_NUMBER = "phone_number";
    const CREATED_AT = "created_at";
    const UPDATED_AT = "updated_at";


    /**
     * @return int
     */
    public function getId();

    /**
     * @return string
     */
    public function getName();

    /**
     * @return boolean
     */
    public function getStatus();

    /**
     * @return string
     */
    public function getAddress();

    /**
     * @return string
     */
    public function getPhoneNumber();

    /**
     * @return string
     */
    public function getCreatedAt();

    /**
     * @return string
     */
    public function getUpdatedAt();

    /**
     * @param int $id
     * @return \SimpleMagento\Database\Api\Data\AffiliateMemberInterface
     */
    public function setId($id);

    /**
     * @param string $name
     * @return \SimpleMagento\Database\Api\Data\AffiliateMemberInterface
     */
    public function setName($name);

    /**
     * @param boolean $status
     * @return \SimpleMagento\Database\Api\Data\AffiliateMemberInterface
     */
    public function setStatus($status);

    /**
     * @param string $address
     * @return \SimpleMagento\Database\Api\Data\AffiliateMemberInterface
     */
    public function setAddress($address);

    /**
     * @param string $phoneNumber
     * @return \SimpleMagento\Database\Api\Data\AffiliateMemberInterface
     */
    public function setPhoneNumber($phoneNumber);

    /**
     * @return \SimpleMagento\Database\Api\Data\AffiliateMemberExtensionInterface|null
     */
    public function getExtensionAttributes();

    /**
     * @param SimpleMagento\Database\Api\Data\AffiliateMemberExtensionInterface $affiliateMemberExtension
     * @return $this
     */
    public function setExtensionAttributes(\SimpleMagento\Database\Api\Data\AffiliateMemberExtensionInterface $affiliateMemberExtension);

}