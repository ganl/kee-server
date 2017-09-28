<?php
/**
 * Created by PhpStorm.
 * User: ganl
 * Date: 2017/8/22
 * Time: 17:50
 */

/**
 * @SWG\SecurityScheme(
 *   securityDefinition="token",
 *   type="apiKey",
 *   in="header",
 *   name="Authorization"
 * )
 */

/**
 * @SWG\SecurityScheme(
 *   securityDefinition="petstore_auth",
 *   type="oauth2",
 *   authorizationUrl="API_HOST",
 *   flow="implicit",
 *   scopes={
 *     "read:pets": "read your pets",
 *     "write:pets": "modify pets in your account"
 *   }
 * )
 */