<?php
/**
 * Created by PhpStorm.
 * User: Simanov
 * Date: 30.11.2017
 * Time: 15:15
 */
namespace Izv\Controller;

use Custom\BaseView;

function addDiv($r, $name, $class, $arr){
    $e = new BaseView($r, $name, $class, $arr);
    return $e->pr();
}
function createSub($r,$name,$style,$par,$title)
{
    $arr0 = ['div.phtml' => [
        ['class' => ' bb-a tac fs', 'style' => $style, 'cont' => $par],
        ['class' => ' tac fs', 'style' => $style, 'cont' => '&nbsp'],
    ]];
    $i6 = addDiv($r, $name, 'dib w-153 fs-0', $arr0);

    return ['div.phtml' =>[
        ['class' => 'fl br-a tac w-32 fs', 'style' => $style, 'cont' => $title],
        ['class' => 'fl tac w-153 fs', 'cont' => $i6],
    ]];
}
function createLine($r,$name,$style,$par,$title)
{
    $arr = ['div.phtml' =>[
        ['class' => 'fl br-a tac w-32 fs', 'style' => $style, 'cont' => $title],
        ['class' => 'fl tac w-153 fs','style' => $style, 'cont' => $par],
    ]];
    return addDiv($r, $name, 'w100 bt-a dib', $arr);
}