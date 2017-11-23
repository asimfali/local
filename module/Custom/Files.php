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
    /**
     * @var array $names
     */
    protected $names;
    public function __construct($v)
    {
        if (!isset($v))
            return;
        else if (is_array($v)){
            $this->files = $v['tmp_name'];
            $this->names = $v['name'];
        } else {
            $this->path = $v;
        }
    }
    public function copy($path)
    {
        $this->path = $path;
        $i = 0;
        foreach ($this->files as $file) {
            $name =  $this->names[$i];
//            $name = mb_convert_encoding($name, 'CP1251', mb_detect_encoding($name));
            $b = move_uploaded_file($file, $path . $name);
            $i++;
        }
        return $b;
    }
    public function pdf($name)
    {
//        $name = idn_to_utf8($name);
//        $name = mb_convert_encoding($name, 'UTF-8', mb_detect_encoding($name));
//        $f_pdf = file_get_contents($name);
        $items = file($name);
//        if (empty($f_pdf)) return;
        ob_start ();
        header ('Content-type: application/pdf');
        header ('Content-disposition: inline; filename = ' . $name);
//        $f = fopen($name, 'r');
//        if ($f){
//            while (!feof($f)){
//                $bufs[] = fgetc($f, 400);
//            }
//            fclose($f);
//            foreach ($bufs as $buf) {
//                echo $buf;
//            }
//        }
//        echo $f_pdf;
        foreach ($items as $item) {
            echo $item;
        }

        ob_end_flush ();
    }
    public function pr($path)
    {
        foreach ($this->names as $name) {
            $n = urlencode($name);
            echo "<a href=\"/{$path}/?pdf={$n}\" target='_blank'>{$name}</a><br>";
        }
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