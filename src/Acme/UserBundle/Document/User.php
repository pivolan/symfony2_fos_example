<?php

namespace Acme\UserBundle\Document;

use FOS\UserBundle\Document\User as BaseUser;
use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;

/**
 * @MongoDB\Document
 */
class User extends BaseUser
{
    /**
     * @MongoDB\Id(strategy="auto")
     */
    protected $id;
    /**
     * @var string
     * @MongoDB\String
     */
    protected $firstname;

    /**
     * @var string
     * @MongoDB\String
     */
    protected $lastname;

    /**
     * @var string
     * @MongoDB\String
     */
    protected $facebookID;

    /**
     * @var String
     * @MongoDB\String
     */
    protected $avatar_url;

    /**
     * @var String = male|female
     * @MongoDB\String
     */
    protected $gender;

    /**
     * @var Int = timestamp
     * @MongoDB\Timestamp
     */
    protected $birthday;

    public function __construct()
    {
        parent::__construct();
        // your own logic
    }

    public function serialize()
    {
        return serialize(array($this->facebookID, parent::serialize()));
    }

    public function unserialize($data)
    {
        list($this->facebookID, $parentData) = unserialize($data);
        parent::unserialize($parentData);
    }

    /**
     * @return string
     */
    public function getFirstname()
    {
        return $this->firstname;
    }

    /**
     * @param string $firstname
     */
    public function setFirstname($firstname = null)
    {
        $this->firstname = $firstname;
    }

    /**
     * @return string
     */
    public function getLastname()
    {
        return $this->lastname;
    }

    /**
     * @param string $lastname
     */
    public function setLastname($lastname = null)
    {
        $this->lastname = $lastname;
    }

    /**
     * Get the full name of the user (first + last name)
     *
     * @return string
     */
    public function getFullName()
    {
        return $this->getFirstName() . ' ' . $this->getLastname();
    }

    /**
     * @param string $facebookID
     *
     * @return void
     */
    public function setFacebookID($facebookID = null)
    {
        $this->facebookID = $facebookID;
        if ($this->username == "") //si on n'a pas de username
        {
            //on met le facebook id a la place
            $this->setUsername($facebookID);
        }
        $this->salt = '';
    }

    /**
     * @return string
     */
    public function getFacebookID()
    {
        return $this->facebookID;
    }

    /**
     * @param Array
     */
    public function setFBData($fbdata)
    {
        if (isset($fbdata['id'])) {
            $this->setFacebookID($fbdata['id']);
            $this->addRole('ROLE_FACEBOOK');
        }
        if (isset($fbdata['first_name'])) {
            $this->setFirstname($fbdata['first_name']);
        }
        if (isset($fbdata['last_name'])) {
            $this->setLastname($fbdata['last_name']);
        }
        if (isset($fbdata['email'])) {
            $this->setEmail($fbdata['email']);
        }
        if (isset($fbdata['picture'], $fbdata['picture']['data']['url'])) {
            $this->setAvatarUrl($fbdata['picture']['data']['url']);
        }
        if (isset($fbdata['gender'])) {
            $this->setGender($fbdata['gender']);
        }
        if (isset($fbdata['birthday']) && !empty($fbdata['birthday'])) {
            $timestamp = strtotime($fbdata['birthday']);
            $this->setBirthday($timestamp);
        }
    }

    /**
     * @param String $avatar_url
     */
    public function setAvatarUrl($avatar_url)
    {
        $this->avatar_url = $avatar_url;
    }

    /**
     * @return String
     */
    public function getAvatarUrl()
    {
        return $this->avatar_url;
    }

    /**
     * @return String
     */
    public function getGender()
    {
        return $this->gender;
    }

    /**
     * @param String $gender
     */
    public function setGender($gender)
    {
        $this->gender = $gender;
    }

    /**
     * @return Int
     */
    public function getBirthday()
    {
        return $this->birthday;
    }

    /**
     * @param Int $birthday
     */
    public function setBirthday($birthday)
    {
        $this->birthday = $birthday;
    }
}