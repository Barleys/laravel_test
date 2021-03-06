<?php

namespace App\Http\Controllers;

use App;
use App\Pay;
use App\Tag;
use App\Tree;
use Fenos\Notifynder\Facades\Notifynder;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\User;
use Dingo\Api\Exception\StoreResourceFailedException;
use App\Http\Controllers\Controller;
use Dingo\Api\Routing\Helpers;
//use Illuminate\Routing\Controller;
use Tymon\JWTAuth\Exceptions\JWTException;
use JWTAuth;

use TomLingham\Searchy\Facades\Searchy;

use Monolog\Logger;
use Monolog\Handler\StreamHandler;
use App\Http\Transformers\UserTransformer;
use App\Order;
use Illuminate\Support\Facades\Redis;
use App\Jobs\SendReminderEmail;
use Illuminate\Support\Facades\Crypt;

use Excel;

use Fenos\Notifynder\Traits\Notifable;

class UserController extends Controller
{

    private $logger = null;
    private $redis = null;

    public function __construct()
    {
        $this->logger = new Logger('APP');
        $this->logger->pushHandler(new StreamHandler('../storage/logs/app.log', Logger::DEBUG));

        $this->redis = Redis::connection('default');
    }

    public function hasMany(){}
    public function morphMany(){}

    use Helpers;
    use Notifable;

    public function notice()
    {
        $user = User::find(1);

//        $user->getNotifications($limit=null, $paginate=null, $order='desc');
//        $user->getNotificationNotRead($limit =null, $paginate=null, $order='desc');
//        $user->getNotification();
//        $user->countNotification();
//        $user->countNotificationNotRead();
//        $user->readAllNotification();

        $from_user_id = 1;
        $to_user_to = 2;

//        Notifynder::category('user.following')
//            ->from($from_user_id)
//            ->to($to_user_to)
//            ->url()
//            ->send();

        $userNotified = User::find($to_user_to);

        dd($userNotified);
    }


    public function exportExcel()
    {
        $cellData = User::all()->toArray();

        Excel::create('用户表', function ($excel) use ($cellData) {
            $excel->sheet('score', function ($sheet) use ($cellData) {
                $sheet->rows($cellData);
            });
        })->export('xlsx');
    }

    public function importExcel()
    {
        $file = 'storage/uploads/users.xlsx';

        Excel::load($file, function($reader){

//            $reader->toArray()->al
            $data = $reader->toArray();

            echo "<pre>";
            var_dump($data);
            echo "</pre>";
        });
    }

    public function test(Request $request)
    {
        echo $request->input('id') . 'is in range.';
    }

    public function crypt()
    {
        $user = User::findOrFail(1);

        $c = Crypt::encrypt($user->id);

        return response()->json(['c' => $c, 'd' => Crypt::decrypt($c)]);
    }

    public function purchasePodcast($userId, $podcastId)
    {
        $pod = Podcast::findOrFail($podcastId);

        Event::fire(new App\Events\PodcastWasPurchased($pod));
    }

    public function mail($id = 1)
    {
        $user = User::findOrFail($id);

        $job = (new SendReminderEmail($user))->delay(10);

        $stat = $this->dispatch($job);

        if ($stat == 0) {
            echo "OK";
        } else {
            echo "error: " . $stat;
        }
    }

    public function redis()
    {
        $this->redis->set('test', 'TEST111');

        $name = $this->redis->get('test');

        echo "<pre>";
        var_dump($name);
        echo "</pre>";
    }

    /**
     * @apiGroup User
     * @apiVersion 0.1.0
     * @api {post} /api/v1/user/signup 用户注册
     * @apiName signup
     * @apiParam (参数) {string} name 用户名称
     * @apiParam (参数) {string} password 用户密码
     * @apiDescription 用户注册
     * @apiSuccess (成功) {number} HTTP_CODE 201
     * @apiSuccess (成功) {json} DATA 响应的数据
     * @apiSuccessExample {json} 成功示例
     *      HTTP/1.1 201 Created
     *      {
     *          "token": "eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJzdWIiOjUxLCJpc3MiOiJodHRwOi8vbGFyYXRlc3QuY29tL3B1YmxpYy9pbmRleC5waHAvYXBpL3YxL3VzZXIvc2lnbnVwIiwiaWF0IjoxNTAwOTUyNjczLCJleHAiOjE1MDA5NTYyNzMsIm5iZiI6MTUwMDk1MjY3MywianRpIjoiUFJxYXVLQmZzWWV5ZTNxSyJ9.qXOmezxVBUl6joNaTnMOmPsdTg0Y3Ykexe-PCfwyK7M"
     *      }
     * @apiError (失败) {number} HTTP_CODE HTTP响应代码
     * @apiError (失败) {json} DATA 响应的数据
     * @apiErrorExample {json} 失败示例
     *      HTTP/1.1 422 Unprocessable Entity
     *      {
     *          "message": "Could not create new user.",
     *          "errors": {
     *              "password": [
     *                  "The password field is required."
     *              ]
     *          },
     *          "status_code": 422
     *      }
     *
     *      HTTP/1.1 500 Internal Server Error
     *      {
     *          "message": "SQLSTATE[23000]: Integrity constraint violation: 1062 Duplicate entry 'admin101@qq.com' for key 'users_email_unique' (SQL: insert into `users` (`name`, `password`, `email`, `updated_at`, `created_at`) values (admin, admin23, admin101@qq.com, 2017-07-25 03:30:34, 2017-07-25 03:30:34))",
     *          "code": "23000",
     *          "status_code": 500
     *      }
     */
    public function signup()
    {
        $rules = [
            'name' => ['required', 'alpha'],
            'password' => ['required', 'min:1']
        ];

        $payload = app('request')->only('name', 'password');

        $payload['email'] = 'admin' . rand(100, 101) . '@qq.com';

        $validator = app('validator')->make($payload, $rules);

        if ($validator->fails()) {
            throw new StoreResourceFailedException('Could not create new user.', $validator->errors());
        }

        $user = User::create($payload);

        if ($user->save()) {

            $token = JWTAuth::fromUser($user);

            return response()->json(['token' => $token], 201);
        }

        return response()->json(['error' => 'error'], 500);
    }

    public function login(Request $request)
    {
        $credentials = $request->only('name', 'password');

        $user = User::where($credentials)->first();

        if (!$user) {
            return response()->json(['error' => 'invalid_name_or_passwrod'], 401);
        }

        $token = JWTAuth::fromUser($user);

        $this->logger->info("User login.");
        $this->logger->error("error occurs");

        return response()->json(compact('token'));
    }

    public function auth(Request $request)
    {
        try {
            if (!$user = JWTAuth::parseToken()->authenticate()) {
                return response()->json(['user_not_found'], 404);
            }
        } catch (Tymon\JWTAuth\Exceptions\TokenExpiredException $e) {
            return response()->json(['token_expired'], $e->getStatusCode());
        } catch (Tymon\JWTAuth\Exceptions\TokenInvalidException $e) {
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

    public function pays()
    {
        $pays = (new User())->getPaysById(2);

        return $this->response->array($pays->toArray());
    }



    /************************Article tag relations********************************/

    /**
     * @apiGroup About
     *
     * @api {get} /about/events 大事记列表
     * @apiVersion 0.1.0
     * @apiSuccessExample {json} Success-Response:
     * HTTP/1.1 200 OK
     * {
     *  "events_list": [
     *   {
     *     "id": 4,
     *     "title": "北大金秋正式成立3",
     *     "time_node": "2017-06-23 13:34:38",
     *     "avatar": "URL"
     *   },
     *   {
     *     "id": 3,
     *     "title": "北大金秋正式成立2",
     *     "time_node": "2017-06-23 13:31:40",
     *     "avatar": "URL"
     *   },
     *   {
     *     "id": 2,
     *     "title": "北大金秋正式成立1",
     *     "time_node": "2017-06-23 13:31:38",
     *     "avatar": "URL"
     *   },
     *   {
     *     "id": 1,
     *     "title": "北大金秋正式成立",
     *     "time_node": "2017-06-23 13:29:38",
     *     "avatar": "URL"
     *   }
     *  ]
     * }
     */
    public function tags()
    {
        $tags = (new Tag())->getTagsById(2);

        return $this->response->array($tags->toArray());
    }

    public function info($id)
    {
        if ($id > 100) {
            return $this->response->array([
                'error' => "ur id is ({$id}) bigger than 100",
            ]);
        }

        return $this->response->array([
            'id' => $id,
        ]);
    }

    public function nodes()
    {
        $obj = (new Tree())->test();

        return response()->json($obj);
    }

    public function tq()
    {
        $obj = (new Tree())->tq();

        return response()->json($obj);
    }

    public function level()
    {
        $level = (new Tree())->level();

        return response()->json($level);
    }

    public function upload()
    {
        return view('upload');
    }

    public function doupload(Request $request)
    {
        if (!$request->isMethod('POST')) {
            return $this->response->array([
                'error' => 'request method error'
            ]);
        }

        $post = $request->input();

        $file = $request->file('file');

        $filepath = app_path('../storage/uploads');

        //保存片段
        if (!$file->isValid()) {
            return $this->response->array([
                'error' => 'file is invalid.',
            ]);
        }

        $index = ($post['chunk'] == "false") ? $post['chunks'] : $post['chunk'];

        $newName = $index . '.part';

        if (!$file->move($filepath, $newName)) {
            $this->response->array(['error' => 'move_failed']);
        }

        if ($post['chunk'] != "false") {
            return $this->response->array(['error' => 'piece']);
        }

        $dirs = scandir($filepath);

        unset($dirs[0], $dirs[1]);

        $fp = fopen($filepath . '/' . $post['name'], 'a');

        rsort($dirs);

        foreach ($dirs as $item) {

            $temp = $filepath . '/' . $item;

            $buff = file_get_contents($temp);

            fwrite($fp, $buff);
        }

        fclose($fp);

        $know = ($post['chunk'] == "false") ? "complete" : "piece";

        return $this->response->array(['error' => $know]);

    }

    public function pdf()
    {
        $pdf = App::make('dompdf.wrapper');

        $pdf->loadHTML('<h1 color="red">PDF TEST</h1>');

        return $pdf->stream();
    }

    public function pdfdown()
    {
        $pdf = PDF::loadView('pdf.invoice', $data);

        return $pdf->download('invoice.pdf');
    }


    /*helper array functions and collect*/
    public function arraytest()
    {
        $arr = [
            'a' => 'b',
            'c' => [
                'name' => 'admin',
                'age' => 1,
                'desc' => [
                    'height' => 12,
                    'weight' => 3,
                ]
            ]
        ];

        $val = array_get($arr, 'c.desc.height');

        return $this->response->array([
            'val' => $val,
        ]);
    }

}




























