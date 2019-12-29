<?php


namespace shayand\marketingAutomationApi;



class Contact
{
    //                    $arr = $contacts->create([
//                        'firstname' => 'Shayan',
//                        'lastname' => 'Davarzani',
//                        'email' => 'hasan3@jalilvand.com',
//                        'ipAddress' => $_SERVER['REMOTE_ADDR'],
//                        'mobile' => '09373636369',
//                        'tags' => ['majid'],
//                        'thirdparty_expiredate' => date('Y-m-d H:i:s'),
//                        'thirdparty_expiredate_2' => date('Y-m-d H:i:s')
//                    ]);

    /**
     * @var String $firstname
     */
    private $firstname;

    /**
     * @var String $lastname
     */
    private $lastname;

    /*
     * var String | null $email
     */
    private $email = null;

    /**
     * @var String $ipAddress
     */
    private $ipAddress = null;

    /**
     * @var string $mobile
     */
    private $mobile;

    /**
     * @var array
     */
    private $tags;

    /**
     * Contact constructor.
     * @param $mobile
     * @param String $firstname
     * @param String $lastname
     */
    public function __construct($mobile , $firstname, $lastname)
    {
        $this->firstname = $firstname;
        $this->lastname = $lastname;
        $this->mobile = $this->formatMobileNo($mobile);
    }

    /**
     * @return string
     */
    public function getMobile()
    {
        return $this->mobile;
    }

    /**
     * @param $mobile
     * @return false|mixed|string
     */
    public function formatMobileNo($mobile)
    {
        $formattedMobile = str_replace('+9809','9',$mobile);
        $formattedMobile = str_replace('+989','9',$formattedMobile);
        if (substr($formattedMobile,0,1) == "0"){
            $formattedMobile = substr($formattedMobile,1);
        }
        return $formattedMobile;
    }

    /**
     * @param mixed $email
     */
    public function setEmail($email)
    {
        $this->email = $email;
    }

    /**
     * @param String $ipAddress
     */
    public function setIpAddress($ipAddress)
    {
        $this->ipAddress = $ipAddress;
    }

    public function toArray()
    {
        $ret = [
            'firstname' =>  $this->firstname,
            'lastname' => $this->lastname,
            'mobile' => $this->mobile
        ];

        if($this->email != null){
            $ret['email'] = $this->email;
        }
        if($this->ipAddress != null){
            $ret['ipAddress'] = $this->ipAddress;
        }

        if(count($this->tags) > 0){
            $ret['tags'] = $this->tag;
        }
        return $ret;

    }

    /**
     * @param array $tags
     * @return Contact
     */
    public function setTags($tags)
    {
        $this->tags = $tags;
        return $this;
    }

}