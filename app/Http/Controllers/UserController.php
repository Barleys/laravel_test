<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\User;
use Dingo\Api\Exception\StoreResourceFailedException;
//use App\Http\Controllers\Controller;
use Dingo\Api\Routing\Helpers;
use Illuminate\Routing\Controller;
use Tymon\JWTAuth\Exceptions\JWTException;
use JWTAuth;

use TomLingham\Searchy\Facades\Searchy;

use Monolog\Logger;
use Monolog\Handler\StreamHandler;
use App\Http\Transformers\UserTransformer;

use Dingo\Api\Auth\Provider\OAuth2;
use App\Order;

class UserController extends Controller
{

    private $logger = null;

    public function __construct()
    {
        $this->logger = new Logger('APP');
        $this->logger->pushHandler(new StreamHandler('../storage/logs/app.log', Logger::DEBUG));
    }

    use Helpers;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }


    /** js
     * @api {post} /api/v1/user/signup User register
     * @apiName signup
     * @apiDescription register users
     * @apiSuccess (Reponse 200) {number} code 200
     * @apiSuccess (Reponse 200) {json} [data='""']
     * @apiSuccessExample {json} Response 200 Example
     *      HTTP/1.1 200 OK
     *      {
     *          "code": 200,
     *          "data": ""
     *      }
     */
    public function signup()
    {
        $rules = [
            'name' => ['required', 'alpha'],
            'password' => ['required', 'min:1']
        ];

        $payload = app('request')->only('name', 'password');

        $payload['email'] = 'admin'. rand(100, 999) . '@qq.com';

        $validator = app('validator')->make($payload, $rules);

        if ($validator->fails()) {
            throw new StoreResourceFailedException('Could not create new user.', $validator->errors());
        }

        $user = User::create($payload);

        if($user->save()){

            $token = JWTAuth::fromUser($user);

            return response()->json(['token' => $token], 200);
        }

        return response()->json(['error' => 'error'], 200);
    }

    public function login(Request $request)
    {
        $credentials = $request->only('name', 'password');

        $user = User::where($credentials)->first();

        if(!$user){
            return response()->json(['error' => 'invalid_name_or_passwrod'], 401);
        }

        $token = JWTAuth::fromUser($user);

        $this->logger->info("User login.");
        $this->logger->error("error occurs");

        return response()->json(compact('token'));
    }

    public function auth(Request $request)
    {
        try{
            if(!$user = JWTAuth::parseToken()->authenticate()) {
                return response()->json(['user_not_found'], 404);
            }
        }catch(Tymon\JWTAuth\Exceptions\TokenExpiredException $e){
            return response()->json(['token_expired'], $e->getStatusCode());
        }catch (Tymon\JWTAuth\Exceptions\TokenInvalidException $e) {
            return response()->json(['token_invalid'], $e->getStatusCode());
        } catch (Tymon\JWTAuth\Exceptions\JWTException $e) {
            return response()->json(['token_absent'], $e->getStatusCode());
        }

        $info = compact('user');

        $info['post'] = $request->input();

        return response()->json($info);
    }

    public function query($id)
    {
        $user = User::findOrFail($id);
        $this->logger->info("GET user info.");
        return $this->response->array($user->toArray());
    }

    public function search(Request $request)
    {
        $param = $request->input('param');

        $users = Searchy::search('users')
                        ->fields(['name', 'email'])
                        ->query($param)
                        ->select('name', 'email', 'created_at', 'updated_at')
                        ->get();

        return response()->json($users);
    }

    public function show()
    {

        $orders = (new Order())->getOrdersById(1);

        return $this->response->array($orders);
    }

    public function middlewares($id)
    {
        $user = User::findOrFail($id);
        $this->logger->info("GET user info.");
        return $this->response->array($user->toArray());
    }
}
































