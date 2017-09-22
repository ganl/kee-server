<?php
/**
 * Created by PhpStorm.
 * User: ganl
 * Date: 2017/8/24
 * Time: 11:09
 */

namespace I2Definitions;

/**
 * @SWG\Definition(required={"code", "message"}, type="object")
 */
class ApiResponse
{
    /**
     * @SWG\Property(format="int32")
     * @var int
     */
    public $code;

    /**
     * @var string
     * @SWG\Property()
     */
    public $message;

}
