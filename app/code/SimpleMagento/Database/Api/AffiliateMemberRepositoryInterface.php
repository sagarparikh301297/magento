<?php


namespace SimpleMagento\Database\Api;


use Magento\Framework\Api\SearchCriteriaInterface;

interface AffiliateMemberRepositoryInterface
{
    /**
     * @return \SimpleMagento\Database\Api\Data\AffiliateMemberInterface[]
     *
     */
    public function getList();

    /**
     * @param int $id
     * @return \SimpleMagento\Database\Api\Data\AffiliateMemberInterface
     */
    public function getAffiliateMemberById($id);

    /**
     * @param int $id
     * @return void
     */
    public function deleteById($id);

    /**
     * @param \SimpleMagento\Database\Api\Data\AffiliateMemberInterface $member
     * @return \SimpleMagento\Database\Api\Data\AffiliateMemberInterface
     */
    public function saveAffiliateMember(\SimpleMagento\Database\Api\Data\AffiliateMemberInterface $member);

    /**
     * @param SearchCriteriaInterface $searchCriteria
     * @return \SimpleMagento\Database\Api\Data\AffiliateMemberSearchResultInterface
     */
    public function getSearchResultsList(SearchCriteriaInterface $searchCriteria);
}