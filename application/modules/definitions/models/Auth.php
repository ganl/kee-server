<?php
/**
 * Created by PhpStorm.
 * User: ganl
 * Date: 2017/8/23
 * Time: 11:10
 */

namespace I2Definitions;

/**
 * @SWG\Definition()
 */
class Auth
{
    /**
     * @var string
     * @SWG\Property()
     */
    public $username;

    /**
     * @var string
     * @SWG\Property()
     */
    public $pwd;

}