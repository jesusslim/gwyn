<?php
/**
 * Created by PhpStorm.
 * User: jesusslim
 * Date: 2017/5/15
 * Time: 下午4:02
 */

namespace gwyn\Src;

use Swoole\Http\Request;
use Swoole\Http\Response;

class Server
{

    protected $host;
    protected $port;
    protected $map;
    protected $post_params;

    public function __construct($map,$host = '127.0.0.1',$port = '9876',$use_post = true)
    {
        $this->host = $host;
        $this->port = $port;
        $this->map = $map;
        $this->post_params = $use_post;
    }

    public function run(){
        $server = new \Swoole\Http\Server($this->host,$this->port);
        $server->on('Request', function(Request $request,Response $response) {
            $status = 0;
            $response->header('Content-Type','application/json');
            $response->header('charset','utf-8');
            if (!isset($request->get['service'])){
                $msg = 'empty service';
                $response->end(json_encode(compact('status','msg')));
            }
            if (!isset($this->map[$request->get['service']])){
                $msg = $request->get['service'].' not found';
                $response->end(json_encode(compact('status','msg')));
            }
            $callee = $this->map[$request->get['service']];
            $params = $this->post_params ? $request->post : $request->get;
            $injector = new Container();
            try{
                $injector->mapDatas($params);
                if ($callee instanceof \Closure){
                    $result = $injector->call($callee,$params);
                }else{
                    if (!isset($request->get['function'])){
                        $msg = 'empty function';
                        $response->end(json_encode(compact('status','msg')));
                    }
                    $function = $request->get['function'];
                    $result = $injector->callInClass($callee,$function,$params);
                }
                $status = 1;
                $response->end(json_encode(compact('status','result')));
            }catch (\Exception $e){
                $msg = $e->getMessage();
                $response->end(json_encode(compact('status','msg')));
            }
        });
        $server->start();
    }
}