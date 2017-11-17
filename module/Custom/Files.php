<?php
/**
 * Created by PhpStorm.
 * User: Simanov
 * Date: 16.11.2017
 * Time: 8:30
 */

namespace Custom;
//use Icewind\SMB\Server;

class Files
{
    protected $path;
    public $d;
    public $f;
    /**
     * @var array $files
     */
    public $files;
    public function __construct($path)
    {
        $this->path = $path;
    }
    public function getDirs()
    {
        $this->d = opendir($this->path);
        if (!$this->d) return;
        while (($e = readdir($this->d)) !== false){
            if ($e == '.' || $e == '..') continue;
            if (!is_dir($e)) continue;
        }
    }
    public function getFiles()
    {
//        $server = new Server('191.168.0.161', 'simanov', 'P@ssword1');
//        $shares = $server->listShares();
//        foreach ($shares as $share) {
//            echo $share->getName(). "\n";
//        }
        $pattern = $this->path . '*\\*.pdf';
        $pattern = "T:\\Д А Н И Л А\\И З В Е Щ Е Н И Я\\2017\\ТПМШ.012-17\\*.pdf";
        $this->files = glob($pattern, GLOB_NOSORT);
    }
}