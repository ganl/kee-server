<?php
/**
 * Created by PhpStorm.
 * User: ganl
 * Date: 2017/8/24
 * Time: 12:39
 */

namespace I2Definitions;


/**
 * @SWG\Definition(
 *     definition="Response",
 *     required={"ret", "msg"},
 *     type="object"
 * )
 */
class Response
{
    /**
     * @SWG\Property(format="int32")
     * @var int
     */
    public $ret;

    /**
     * @SWG\Property(ref="#/definitions/ApiResponse")
     * @var object
     */
    public $data;

    /**
     * @var string
     * @SWG\Property()
     */
    public $msg;

}