<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Dir extends API_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('node_model', 'nodes');
    }

    /**
     * @SWG\Get(
     *    path="/dir",
     *    tags={"dir"},
     *    summary="lists out directory",
     *    @SWG\Parameter(
     *        in="query",
     *        name="nodeuuid",
     *        description="node uuid",
     *        required=true,
     *        type="string"
     *    ),
     *    @SWG\Parameter(
     *        in="query",
     *        name="path",
     *        description="absolute path",
     *        required=true,
     *        type="string"
     *    ),
     *    @SWG\Response(
     *        response="200",
     *        description="directory",
     *        @SWG\Schema(type="array", @SWG\Items(ref="#/definitions/Dir"))
     *    ),
     *    security={{
     *      "token":{}
     *    }}
     * )
     */
    public function index_get()
    {

    }

    /**
     * @SWG\Post(
     *    path="/dir",
     *    tags={"dir"},
     *    summary="create a folder",
     *    @SWG\Parameter(
     *        in="body",
     *        name="body",
     *        description="",
     *        required=true,
     *        @SWG\Schema(ref="#/definitions/DirOp")
     *    ),
     *    @SWG\Response(
     *        response="200",
     *        description="create folder"
     *    ),
     *    security={{
     *      "token":{}
     *    }}
     * )
     */
    public function index_post()
    {

    }

    /**
     * @SWG\Post(
     *    path="/dir/check",
     *    tags={"dir"},
     *    summary="check directory exist or not",
     *    @SWG\Parameter(
     *        in="body",
     *        name="body",
     *        description="",
     *        required=true,
     *        @SWG\Schema(ref="#/definitions/DirOp")
     *    ),
     *    @SWG\Response(
     *        response="200",
     *        description="check directory",
     *        @SWG\Schema(type="array", @SWG\Items(ref="#/definitions/DirCheck"))
     *    ),
     *    security={{
     *      "token":{}
     *    }}
     * )
     */
    public function check_post()
    {

    }

}