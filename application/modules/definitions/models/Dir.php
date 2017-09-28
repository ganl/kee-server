<?php
/**
 * Created by PhpStorm.
 * User: ganl
 * Date: 2017/8/22
 * Time: 17:44
 */

namespace I2Definitions;


/**
 * @SWG\Definition(type="object", @SWG\Xml(name="Dir"))
 */
class Dir
{
    /**
     * @SWG\Property()
     * @var string
     */
    public $name;

    /**
     * @SWG\Property()
     * @var string
     */
    public $size;

    /**
     * @SWG\Property()
     * @var boolean
     */
    public $isdir;

    /**
     * @var string
     * @SWG\Property()
     */
    public $time;
}

/**
 * @SWG\Definition(type="object", @SWG\Xml(name="DirOp"))
 */
class DirOp
{
    /**
     * @SWG\Property()
     * @var string
     */
    public $nodeuuid;
    /**
     * @SWG\Property()
     * @var string
     */
    public $path;
}

/**
 * @SWG\Definition(type="object", @SWG\Xml(name="DirCheck"))
 */
class DirCheck
{
    /**
     * @SWG\Property()
     * @var string
     */
    public $checkResult;
}