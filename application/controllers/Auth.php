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
    public function token_post()
    {

        // todo: check params and check user info
        $identity = $this->post('username');
        $password = $this->post('pwd');

        if ($this->i2_auth->login($identity, $password)) {
            // Build a new key
            $key = $this->_generate_key();

            // If no key level provided, provide a generic key
            $level = $this->post('level') ? $this->post('level') : 1;
            $ignore_limits = ctype_digit($this->post('ignore_limits')) ? (int)$this->post('ignore_limits') : 1;

            // Insert the new key
            if ($this->_insert_key($key, ['user_id' => $identity, 'level' => $level, 'ignore_limits' => $ignore_limits])) {
                $this->success(array('token' => $key));
            }
        } else {
            $this->success(array(), Err::$errCodes['user.name_or_pwd_invalid'], $this->i2_auth->errors());
        }
    }

    public function refresh_token_post()
    {

    }
}