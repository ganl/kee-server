<?php
/**
 * Created by PhpStorm.
 * User: ganl
 * Date: 2017/8/10
 * Time: 14:30
 */

class Auth extends API_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->library('i2_auth');
    }

    protected $methods = [
        'token_post' => ['level' => 10, 'limit' => 300]// 300 requests per hour per user/key
    ];

    /**
     * @SWG\Post(
     *    path="/auth/token",
     *    tags={"auth"},
     *    summary="Obtain token",
     *    @SWG\Parameter(
     *        in="body",
     *        name="body",
     *        description="User info",
     *        required=true,
     *        @SWG\Schema(ref="#/definitions/Auth")
     *    ),
     *    @SWG\Response(
     *        response="200",
     *        description="Successful operation"
     *    )
     * )
     */
    public function token_post() {

        //check params and check user info
        $identity = $this->post('username');
        $password = $this->post('pwd');

        if($this->i2_auth->login($identity, $password)){
            // Build a new key
            $key = $this->_generate_key();

            // If no key level provided, provide a generic key
            $level = $this->post('level') ? $this->post('level') : 1;
            $ignore_limits = ctype_digit($this->post('ignore_limits')) ? (int) $this->post('ignore_limits') : 1;

            // Insert the new key
            if ($this->_insert_key($key, ['user_id' => $identity, 'level' => $level, 'ignore_limits' => $ignore_limits]))
            {
                $this->response([
                    'ret' => REST_Controller::HTTP_OK,
                    'data' => [
                        'returnCode' => 0,
                        'returnMsg' => 0,
                        'token' => $key
                    ],
                    'msg' => ''
                ], REST_Controller::HTTP_OK); // CREATED (201) being the HTTP response code ?
            }
        } else {
            $this->response([
                'ret' => REST_Controller::HTTP_BAD_REQUEST,
                'data' => [
                    'returnCode' => 0,
                    'returnMsg' => $this->i2_auth->errors(),
                    'token' => ''
                ],
                'msg' => 'Could not save the key'
            ], REST_Controller::HTTP_OK); // INTERNAL_SERVER_ERROR (500) being the HTTP response code
        }
    }

    public function refresh_token_post() {

    }
}