<?php
/**
 * Created by PhpStorm.
 * User: Simanov
 * Date: 29.11.2017
 * Time: 7:42
 */

namespace Custom;


class Exec
{
    /**
     * @var string $command
     */
    protected $command;

    protected $options;

    public static $path = "C:\\nginx\\html\\Zend\\tmp\\";
    public static $contMovePath = "C:\\nginx\\html\\Zend\\public\\content\\";
    /**
     * Exec constructor.
     * @param array $arr
     * @param string $cmd
     */
    public function __construct($cmd, $arr)
    {
        $this->command = $cmd;
        $this->options = $arr;
    }
    public function prepare()
    {
        $opt = '';
        foreach ($this->options as $option) {
            $opt .= ' '. $option ;
        }
        $this->command .= $opt;
        $this->command = escapeshellcmd($this->command);
    }
    public function runSystem()
    {

    }
    public function runExec()
    {
        $this->prepare();
        return exec($this->command);
    }
}