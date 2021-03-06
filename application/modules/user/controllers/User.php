<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Demo Controller with Swagger annotations
 * Reference: https://github.com/zircote/swagger-php/
 */
class User extends API_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->model('user_model', 'users');
    }

    /**
     * @SWG\Get(
     *    path="/user",
     *    tags={"user"},
     *    summary="List out users",
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
    public function index_get()
    {
        $data['users'] = $this->users
            ->select('user_uuid, username, email, active, first_name, last_name')
            ->as_array()->get_all();
        $this->success($data);
    }

    /**
     * @SWG\Get(
     *    path="/user/{user_uuid}",
     *    tags={"user"},
     *    summary="Look up a user",
     * 	@SWG\Parameter(
     *        in="path",
     *        name="user_uuid",
     *        description="User ID",
     *        required=true,
     *        type="integer"
     *    ),
     * 	@SWG\Response(
     *        response="200",
     *        description="User object",
     * 		@SWG\Schema(ref="#/definitions/User")
     *    ),
     * 	@SWG\Response(
     *        response="404",
     *        description="Invalid user ID"
     *    )
     * )
     */
    public function id_get($id)
    {
        $data = $this->users
            ->select('user_uuid, username, email, active, first_name, last_name')
            ->get($id);
        $this->success($data);
    }

    /**
     * @SWG\Put(
     *    path="/user/{uuid}",
     *    tags={"user"},
     *    summary="Update an existing user",
     * 	@SWG\Parameter(
     *        in="path",
     *        name="id",
     *        description="User ID",
     *        required=true,
     *        type="integer"
     *    ),
     * 	@SWG\Parameter(
     *        in="body",
     *        name="body",
     *        description="User info",
     *        required=true,
     * 		@SWG\Schema(ref="#/definitions/UserPut")
     *    ),
     * 	@SWG\Response(
     *        response="200",
     *        description="Successful operation"
     *    )
     * )
     */
    // TODO: user should be able to update their own account only
    public function id_put($id)
    {
        $data = elements(array('first_name', 'last_name'), $this->put());

        // proceed to update user
        $updated = $this->i2_auth->update($id, $data);

        // result
        ($updated) ? $this->success($this->i2_auth->messages()) : $this->error($this->i2_auth->errors());
    }
}
