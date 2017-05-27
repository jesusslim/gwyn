<?php
/**
 * Created by PhpStorm.
 * User: jesusslim
 * Date: 2017/5/27
 * Time: 上午10:53
 */

namespace gwyn\Src;


use Inject\InjectorInterface;

interface ContainerInterface extends InjectorInterface
{

    public function mapData($k,$v);

}