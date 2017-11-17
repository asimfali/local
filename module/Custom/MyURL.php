<?php
/**
 * Created by PhpStorm.
 * User: Simanov
 * Date: 16.11.2017
 * Time: 14:45
 */

namespace Custom;


class MyURL
{
    /**
     * @var array $url
     */
    public $urls;
    /**
     * @var string $url
     */
    protected $url = '?';
    public function __construct($arr)
    {
        $this->urls = $arr;
        foreach ($this->urls as $k => $url) {
            if (empty($url)) unset($this->urls[$k]);
        }
        $this->create();
    }
    public function get()
    {
        return substr($this->url,0,-1);
    }
    public function create()
    {
        foreach ($this->urls as $k => $url) {
            $this->url .= $k . '=' . $url . '&';
        }
    }
}