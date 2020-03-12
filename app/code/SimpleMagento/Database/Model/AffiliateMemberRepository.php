<?php


namespace SimpleMagento\Database\Model;

use Magento\Framework\Api\SearchCriteriaInterface;
use SimpleMagento\Database\Api\AffiliateMemberRepositoryInterface;
use SimpleMagento\Database\Api\Data;
use SimpleMagento\Database\Model\ResourceModel\AffiliateMember\CollectionFactory;
use SimpleMagento\Database\Model\AffiliateMemberFactory;
use SimpleMagento\Database\Model\ResourceModel\AffiliateMember;
use SimpleMagento\Database\Api\Data\AffiliateMemberSearchResultInterfaceFactory;
use Magento\Framework\Api\SearchCriteria\CollectionProcessor;

class AffiliateMemberRepository implements AffiliateMemberRepositoryInterface
{
    private $collectionFactory;
    private $affiliateMemberFactory;
    private $affiliateMember;
    private $resultInterfaceFactory;
    private $collectionProcessor;

    public function __construct(CollectionFactory $collectionFactory, AffiliateMemberFactory $affiliateMemberFactory,
    AffiliateMember $affiliateMember, AffiliateMemberSearchResultInterfaceFactory $resultInterfaceFactory, CollectionProcessor $collectionProcessor)
    {
        $this->collectionFactory = $collectionFactory;
        $this->affiliateMemberFactory = $affiliateMemberFactory;
        $this->affiliateMember = $affiliateMember;
        $this->resultInterfaceFactory = $resultInterfaceFactory;
        $this->collectionProcessor = $collectionProcessor;
    }

    /**
     * @return \SimpleMagento\Database\Api\Data\AffiliateMemberInterface[]
     */
    public function getList()
    {

        return $this->collectionFactory->create()->getItems();
    }

    /**
     * @param int $id
     * @return \SimpleMagento\Database\Api\Data\AffiliateMemberInterface
     */
    public function getAffiliateMemberById($id)
    {

        $member = $this->affiliateMemberFactory->create();
        return $member->load($id);
    }


    /**
     * @param \SimpleMagento\Database\Api\Data\AffiliateMemberInterface $member
     * @return \SimpleMagento\Database\Api\Data\AffiliateMemberInterface
     */
    public function saveAffiliateMember(\SimpleMagento\Database\Api\Data\AffiliateMemberInterface $member)
    {
       if($member->getId() == null){
        $this->affiliateMember->save($member);
        return $member;
       }else{
           $newmember = $this->affiliateMemberFactory->create()->load($member->getId());
           foreach ($member->getData() as $key => $value){
               $newmember->setData($key, $value);
           }
           $this->affiliateMember->save($newmember);
           return $newmember;
       }
    }

    /**
     * @param int $id
     * @return void
     */
    public function deleteById($id)
    {
        $member = $this->affiliateMemberFactory->create()->load($id);
        $member->delete();
    }

    /**
     * @param SearchCriteriaInterface $searchCriteria
     * @return \SimpleMagento\Database\Api\Data\AffiliateMemberSearchResultInterface
     */
    public function getSearchResultsList(SearchCriteriaInterface $searchCriteria)
    {
        $collection = $this->affiliateMemberFactory->create()->getCollection();
        $this->collectionProcessor->process($searchCriteria,$collection);
        $searchResults = $this->resultInterfaceFactory->create();
        $searchResults->setSearchCriteria($searchCriteria);
        $searchResults->setItems($collection->getData());
        $searchResults->setTotalCount($collection->getSize());
        return $searchResults;
    }
}