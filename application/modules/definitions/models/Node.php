<?php
/**
 * Created by PhpStorm.
 * User: ganl
 * Date: 2017/8/22
 * Time: 17:38
 */

namespace I2Definitions;

/**
 * @SWG\Definition(required={"name", "photoUrls"}, type="object", @SWG\Xml(name="Pet"))
 */
class Node
{
    /**
     * @SWG\Property(format="int64")
     * @var int
     */
    public $id;
    /**
     * @SWG\Property(example="doggie")
     * @var string
     */
    public $name;
    /**
     * @var Category
     * @SWG\Property()
     */
    public $category;
    /**
     * @var string[]
     * @SWG\Property(@SWG\Xml(name="photoUrl", wrapped=true))
     */
    public $photoUrls;
    /**
     * @var Tag[]
     * @SWG\Property(@SWG\Xml(name="tag", wrapped=true))
     */
    public $tags;
    /**
     * pet status in the store
     * @var string
     * @SWG\Property(enum={"available", "pending", "sold"})
     */
    public $status;
}