<?php
/**
 * Created by PhpStorm.
 * User: ganl
 * Date: 2017/8/22
 * Time: 17:44
 */

namespace I2Definitions;


/**
 * @SWG\Definition(type="object", @SWG\Xml(name="User"))
 */
class User
{
    /**
     * @SWG\Property(format="int64")
     * @var int
     */
    public $id;
    /**
     * @SWG\Property()
     * @var string
     */
    public $username;
    /**
     * @SWG\Property()
     * @var string
     */
    public $first_name;
    /**
     * @SWG\Property()
     * @var string
     */
    public $last_name;
    /**
     * @var string
     * @SWG\Property()
     */
    public $email;
    /**
     * @var string
     * @SWG\Property()
     */
    public $password;
    /**
     * @var string
     * @SWG\Property()
     */
    public $phone;
    /**
     * User Status
     * @var int
     * @SWG\Property(format="int32")
     */
    public $userStatus;
}

/**
 * @SWG\Definition()
 */
class UserPut {

    /**
     * @var string
     * @SWG\Property()
     */
    public $first_name;

    /**
     * @var string
     * @SWG\Property()
     */
    public $last_name;
}