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
        $this->load->library('rpc_client');
    }

    /**
     * @SWG\Get(
     *    path="/node/hello",
     *    tags={"node"},
     *    summary="send hello message to node",
     *    @SWG\Parameter(
     *        in="header",
     *        name="X-API-KEY",
     *        description="API Key",
     *        required=false,
     *        type="string"
     *    ),
     *    @SWG\Response(
     *        response="200",
     *        description="List of users",
     *        @SWG\Schema(type="array", @SWG\Items(ref="#/definitions/User"))
     *    )
     * )
     */
    public function hello_get()
    {
        $response = $this->rpc_client->node_hello('http://172.16.117.128:26821');
        $data['returnCode'] = $response;
        $this->response($data);
    }

    /**
     * @SWG\Post(
     *    path="/node/auth",
     *    tags={"node"},
     *    summary="send hello message to node",
     *    @SWG\Parameter(
     *        in="header",
     *        name="X-API-KEY",
     *        description="API Key",
     *        required=false,
     *        type="string"
     *    ),
     *    @SWG\Response(
     *        response="200",
     *        description="List of users",
     *        @SWG\Schema(type="array", @SWG\Items(ref="#/definitions/User"))
     *    )
     * )
     */
    public function auth_post()
    {
        $params = [
            'os_user' => 'ganl',
            'os_passwd' => '123456'
        ];
        $response = $this->rpc_client->node_os_auth('http://172.16.117.128:26821', $params);
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
     *    tags={"user"},
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
     *        @SWG\Schema(type="array", @SWG\Items(ref="#/definitions/User"))
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
     *    tags={"user"},
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
