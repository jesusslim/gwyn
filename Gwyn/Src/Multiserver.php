<?php
/**
 * Created by PhpStorm.
 * User: jesusslim
 * Date: 2017/5/26
 * Time: 下午1:32
 */

namespace gwyn\Src;


use Inject\AdvChains;
use Swoole\Http\Request;
use Swoole\Http\Response;

class MultiServer extends Server
{

    public function run(){
        $server = new \Swoole\Http\Server($this->host,$this->port);
        $server->on('Request', function(Request $request,Response $response) {
            $status = 0;
            $response->header('Content-Type','application/json');
            $response->header('charset','utf-8');
            if (!isset($request->get['services'])){
                $msg = 'empty services';
                $response->end(json_encode(compact('status','msg')));
            }
            $services = explode(',',$request->get['services']);
            $injector = new Container();
            $params = $this->post_params ? $request->post : $request->get;
            $injector->mapDatas($params);
            $injector->mapData(ContainerInterface::class,$injector);
            $chains = new AdvChains($injector);
            foreach ($services as $serv_str){
                $service_func = explode('|',$serv_str);
                $service = $service_func[0];
                if (!isset($this->map[$service])){
                    $msg = $service.' not found';
                    $response->end(json_encode(compact('status','msg')));
                }
                $callee = $this->map[$service];
                if ($callee instanceof \Closure){
                    $chains->chain($callee);
                }else{
                    if (!isset($service_func[1])){
                        $msg = 'empty function';
                        $response->end(json_encode(compact('status','msg')));
                    }
                    $function = $service_func[1];
                    $chains->chain($callee.'|'.$function);
                }
            }
            try{
                $result = $chains->run();
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