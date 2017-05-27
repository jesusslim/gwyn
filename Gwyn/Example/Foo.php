<?php
/**
 * Created by PhpStorm.
 * User: jesusslim
 * Date: 2017/5/15
 * Time: 下午5:15
 */

namespace gwyn\Example;


class Foo
{

    static public function run(){
        $map = [
            'test_class' => Bar::class,
            'test_closure' => function($a , $b){ return $a * $b;}
        ];
        $server = new \gwyn\Src\Server($map);
        $server->run();
    }

    static public function runMulti(){
        $map = [
            'test_class' => \gwyn\Example\Bar::class,
            'test_closure' => function($a , $b){ return $a * $b;}
        ];
        $server = new \gwyn\Src\MultiServer($map);
        $server->run();
    }

}