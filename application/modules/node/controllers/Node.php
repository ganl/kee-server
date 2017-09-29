<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Demo Controller with Swagger annotations
 * Reference: https://github.com/zircote/swagger-php/
 */
class Node extends API_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->model('node_model', 'nodes');
        $this->load->library('rpc/base_rpc');
    }

    /**
     * @SWG\Get(
     *    path="/node/hello",
     *    tags={"node"},
     *    summary="send hello message to node",
     *    @SWG\Parameter(
     *        in="query",
     *        name="ip",
     *        description="node address",
     *        required=true,
     *        type="string"
     *    ),
     *    @SWG\Response(
     *        response="200",
     *        description="List of users",
     *        @SWG\Schema(type="array", @SWG\Items(ref="#/definitions/User"))
     *    ),
     *    security={{
     *      "token":{}
     *    }}
     * )
     */
    public function hello_get()
    {
        $ip = $this->get('ip');
        $response = $this->base_rpc->node_hello($ip);
        $this->success(array(), $response);
    }

    /**
     * @SWG\Post(
     *    path="/node/auth",
     *    tags={"node"},
     *    summary="send hello message to node",
     *    @SWG\Parameter(
     *        in="body",
     *        name="body",
     *        description="",
     *        required=true,
     *        @SWG\Schema(ref="#/definitions/Node")
     *    ),
     *    @SWG\Response(
     *        response="200",
     *        description="List of users",
     *        @SWG\Schema(type="array", @SWG\Items(ref="#/definitions/Node"))
     *    )
     * )
     */
    public function auth_post()
    {
        $params = [
            'os_user' => 'ganl',
            'os_passwd' => '123456'
        ];
        $response = $this->base_rpc->node_os_auth('172.16.117.128', $params);
        $data['returnCode'] = $response;
        $data['returnMsg'] = '';
        if (in_array($data['returnCode'], array(1, 2))) {
            $data['osType'] = $response;
            $data['returnCode'] = 0;
        }
        $this->response($data);
    }

    /**
     * @SWG\Get(
     *    path="/node/index",
     *    tags={"node"},
     *    summary="List out users",
     *  @SWG\Parameter(
     *        in="header",
     *        name="X-API-KEY",
     *        description="API Key",
     *        required=false,
     *        type="string"
     *    ),
     *  @SWG\Response(
     *        response="200",
     *        description="List of users",
     *        @SWG\Schema(type="array", @SWG\Items(ref="#/definitions/Node"))
     *    )
     * )
     */
    public function index_get()
    {
        $data = $this->users
            ->select('user_id, node_uuid, node_name')
            ->get_all();
        $this->response($data);
    }

    /**
     * @SWG\Get(
     *    path="/node/{node_uuid}",
     *    tags={"node"},
     *    summary="Look up a user",
     *    @SWG\Parameter(
     *        in="path",
     *        name="user_uuid",
     *        description="User ID",
     *        required=true,
     *        type="integer"
     *    ),
     *    @SWG\Response(
     *        response="200",
     *        description="User object",
     *        @SWG\Schema(ref="#/definitions/Node")
     *    ),
     *    @SWG\Response(
     *        response="404",
     *        description="Invalid user ID"
     *    )
     * )
     */
    public function id_get($id)
    {
        $data = $this->users
            ->select('user_id, node_uuid, node_name')
            ->get($id);
        $this->response($data);
    }

    /**
     * @SWG\Put(
     *    path="/node/{uuid}",
     *    tags={"node"},
     *    summary="Update an existing user",
     *    @SWG\Parameter(
     *        in="path",
     *        name="id",
     *        description="User ID",
     *        required=true,
     *        type="integer"
     *    ),
     *    @SWG\Parameter(
     *        in="body",
     *        name="body",
     *        description="User info",
     *        required=true,
     *        @SWG\Schema(ref="#/definitions/Node")
     *    ),
     *    @SWG\Response(
     *        response="200",
     *        description="Successful operation"
     *    )
     * )
     */
    // TODO: user should be able to update their own account only
    public function id_put($id)
    {
    }
}
