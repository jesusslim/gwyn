<?php
/**
 * Created by PhpStorm.
 * User: jesusslim
 * Date: 2017/5/15
 * Time: 下午4:53
 */

namespace gwyn\Example;

use gwyn\Src\ContainerInterface;

class Bar
{

    public function sum($a,$b = 1){
        return $a + $b;
    }

    public function sum2(ContainerInterface $gwyn_container,$a,$b = 1){
        $a = $a + $b;
        $gwyn_container->mapData('a',$a);
        $gwyn_container->mapData('b',200);
        return $a;
    }
}