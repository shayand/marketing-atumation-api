<?php

namespace shayand\marketingAutomationApi;

use Shayand\Auth\ApiAuth;
use Shayand\Auth\AuthInterface;
use Shayand\Auth\OAuth;
use shayand\marketingAutomationApi\Exception\ApiAuthException;
use Shayand\NextleadApi;

class MarketingAutomationApi
{

    /**
     * @var $apiUrl
     */
    private $apiUrl;

    /**
     * @var ApiAuth $oauthObject
     */
    private $oauthObject;

    /**
     * @var NextleadApi $nextLeadApi;
     */
    private $nextLeadApi;

    /**
     * MarketingAutomationApi constructor.
     */
    public function __construct()
    {
        $this->nextLeadApi = new NextleadApi();
    }

    /**
     * @param mixed $apiUrl
     * @return MarketingAutomationApi
     */
    public function setApiUrl($apiUrl)
    {
        $this->apiUrl = $apiUrl;
        return $this;
    }

    /**
     * @param mixed $oauthObject
     * @return MarketingAutomationApi
     */
    public function setOauthObject($oauthObject)
    {
        $this->oauthObject = $oauthObject;
        return $this;
    }


    /**
     * @param Contact $contact
     * @return array|mixed
     * @throws ApiAuthException
     * @throws \Shayand\Exception\ContextNotFoundException
     */
    public function createOrUpdateContact(Contact $contact)
    {
        if($this->oauthObject instanceof OAuth){
            $contactApi = $this->nextLeadApi->newApi('contacts',$this->oauthObject,$this->apiUrl);
            $arr = $contactApi->create($contact->toArray());
            return $arr;
        }else{
            throw new ApiAuthException('invalid api auth object',500);
        }
    }

    /**
     * @param Contact $contact
     * @param array $tags
     * @throws ApiAuthException
     * @throws \Shayand\Exception\ContextNotFoundException
     */
    public function setContactTags(Contact $contact,array $tags)
    {
        if($this->oauthObject instanceof OAuth) {
            $contactApi = $this->nextLeadApi->newApi('contacts', $this->oauthObject, $this->apiUrl);
            $list = $contactApi->getList('mobile:' . $contact->getMobile());
            if(isset($list['total'])){
                if ($list['total'] > 0) {
                    $singleContact = $list['contacts'];
                    foreach ($singleContact as $val){
                        $contactApi->edit($val['id'],['tags' => $tags]);
                    }
                }else{
                    $cloneContact = $contact->setTags($tags);
                    $this->createOrUpdateContact($cloneContact);
                }
            }
        }else{
            throw new ApiAuthException('invalid api auth object',500);
        }
    }

    /**
     * @param $categoryId
     * @param Contact $contact
     * @param string $duration
     * @throws ApiAuthException
     * @throws \Shayand\Exception\ContextNotFoundException
     */
    public function setContactExpireDate($category = 'thirdparty',Contact $contact,$duration = "1 year")
    {

        if($this->oauthObject instanceof OAuth) {
            $contactApi = $this->nextLeadApi->newApi('contacts', $this->oauthObject, $this->apiUrl);
            $list = $contactApi->getList('mobile:' . $contact->getMobile());
            if ($list['total'] > 0) {
                $singleContact = $list['contacts'];
                foreach ($singleContact as $key => $val) {

                    if($category == 'fire'){

                        $date1 = $val['fields']['core']['fire_expiredate']['value'];
                        $date2 = $val['fields']['core']['fire_expiredate_2']['value'];
                        $date3 = $val['fields']['core']['fire_expiredate_3']['value'];
                        $date4 = $val['fields']['core']['fire_expiredate_4']['value'];
                        $date5 = $val['fields']['core']['fire_expiredate_5']['value'];

                    } elseif ($category == 'autobody'){

                        $date1 = $val['fields']['core']['autobody_expiredate']['value'];
                        $date2 = $val['fields']['core']['autobody_expiredate_2']['value'];
                        $date3 = $val['fields']['core']['autobody_expiredate_3']['value'];
                        $date4 = $val['fields']['core']['autobody_expiredate_4']['value'];
                        $date5 = $val['fields']['core']['autobody_expiredate_5']['value'];

                    } elseif ($category == 'supplementary'){

                        $date1 =$val['fields']['core']['supplementary_expiredate']['value'];
                        $date2 =$val['fields']['core']['supple_expiredate_2']['value'];
                        $date3 =$val['fields']['core']['supple_expiredate_3']['value'];
                        $date4 =$val['fields']['core']['supple_expiredate_4']['value'];
                        $date5 =$val['fields']['core']['supple_expiredate_5']['value'];

                    } else {

                        $date1 = $val['fields']['core']['thirdparty_expiredate']['value'];
                        $date2 = $val['fields']['core']['thirdparty_expiredate_2']['value'];
                        $date3 = $val['fields']['core']['thirdparty_expiredate_3']['value'];
                        $date4 = $val['fields']['core']['thirdparty_expiredate_4']['value'];
                        $date5 = $val['fields']['core']['thirdparty_expiredate_5']['value'];

                    }

                    $updateArray = [];

                    if($date1 != null){
                        $date1 = date("Y-m-d H:i:s",strtotime('+1 year'));

                        if($category == 'fire'){
                            $updateArray['fire_expiredate'] = $date1;
                        } elseif ($category == 'autobody'){
                            $updateArray['autobody_expiredate'] = $date1;
                        } elseif ($category == 'supplementary'){
                            $updateArray['supplementary_expiredate'] = $date1;
                        }else{
                            $updateArray['thirdparty_expiredate'] = $date1;
                        }
                    }
                    if($date2 != null){
                        $date2 = date("Y-m-d H:i:s",strtotime('+1 year'));

                        if($category == 'fire'){
                            $updateArray['fire_expiredate_2'] = $date2;
                        } elseif ($category == 'autobody'){
                            $updateArray['autobody_expiredate_2'] = $date2;
                        } elseif ($category == 'supplementary'){
                            $updateArray['supple_expiredate_2'] = $date2;
                        }else{
                            $updateArray['thirdparty_expiredate_2'] = $date2;
                        }
                    }
                    if($date3 != null){
                        $date3 = date("Y-m-d H:i:s",strtotime('+1 year'));

                        if($category == 'fire'){
                            $updateArray['fire_expiredate_3'] = $date3;
                        } elseif ($category == 'autobody'){
                            $updateArray['autobody_expiredate_3'] = $date3;
                        } elseif ($category == 'supplementary'){
                            $updateArray['supple_expiredate_3'] = $date3;
                        }else{
                            $updateArray['thirdparty_expiredate_3'] = $date3;
                        }
                    }
                    if($date4 != null){
                        $date4 = date("Y-m-d H:i:s",strtotime('+1 year'));
                        if($category == 'fire'){
                            $updateArray['fire_expiredate_4'] = $date4;
                        } elseif ($category == 'autobody'){
                            $updateArray['autobody_expiredate_4'] = $date4;
                        } elseif ($category == 'supplementary'){
                            $updateArray['supple_expiredate_4'] = $date4;
                        }else{
                            $updateArray['thirdparty_expiredate_4'] = $date4;
                        }
                    }
                    if($date5 != null){
                        $date5 = date("Y-m-d H:i:s",strtotime('+1 year'));
                        if($category == 'fire'){
                            $updateArray['fire_expiredate_5'] = $date5;
                        } elseif ($category == 'autobody'){
                            $updateArray['autobody_expiredate_5'] = $date5;
                        } elseif ($category == 'supplementary'){
                            $updateArray['supple_expiredate_5'] = $date5;
                        }else{
                            $updateArray['thirdparty_expiredate_5'] = $date5;
                        }
                    }
                    break;
                }
                $edit = $contactApi->edit($singleContact['id'], $updateArray);

                return $edit;
            }
        }else{
            throw new ApiAuthException('invalid api auth object',500);
        }
    }
}