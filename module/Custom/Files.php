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
            $this->slash();
        }
    }
    public function copy($path)
    {
        $this->path = $path;
        if (!file_exists($this->path))
            $this->mkDir($path);
        $i = 0; $b = false;
        foreach ($this->files as $file) {
            $name =  $this->names[$i];
//            $name = mb_convert_encoding($name, 'CP1251', mb_detect_encoding($name));
            $this->slash();
            $b = move_uploaded_file($file, $this->path . $name);
            $i++;
        }
        return $b;
    }
    public function remove($name)
    {
        unlink($this->path . $name . '.pdf');
        $this->path .= $name .'/';
        $files = $this->getF('*');
        foreach ($files as $file) {
            unlink($file);
        }
        rmdir($this->path);
    }
    public function zip($name, $rname)
    {
        $z = new \ZipArchive();
        $izv = $this->path . $rname . '.pdf';
        $this->path .= $name . '/';
        if ($z->open($name, \ZipArchive::CREATE) === true) {
            $files = $this->getF('*');
            foreach ($files as $file) {
                $z->addFile($file,basename($file));
            }
            $z->addFile($izv,basename($izv));
            $z->close();
            header("Content-Type: application/octet-stream");
            header("Accept-Ranges: bytes");
            header("Content-Length: " . filesize($name));
            header("Content-Disposition: attachment; filename=" . $rname . '.zip');
            readfile($name);
            unlink($name);
            } else {
            die ("Ошибка создания архива");
        }
    }
    public function readIzv()
    {
        $files = $this->getF('*.izv');
        if (empty($files)) return false;
        $f = file_get_contents($files[0]);
        if (empty($f)) return false;
        $spl = explode("\n",$f);
        $arr = [];
        foreach ($spl as $item) {
            $tmp = explode('=',$item);
            if (isset($tmp) && count($tmp) == 3){
                $n = $tmp[0];
                $arr[$n]['link'] = trim($tmp[0]);
                $arr[$n]['name'] = trim($tmp[1]);
                $arr[$n]['act'] = trim($tmp[2]);
            } else {
                $arr[$n]['cont'] .= trim($item) . "\n";
            }
        }
        foreach ($arr as &$item) {
            $item['cont'] = trim($item['cont']);
        }
        return $arr;
    }
    public function slash()
    {
        if (substr($this->path,-1) != '/')
            $this->path .= '/';
    }
    public function pdf($name)
    {
//        $name = idn_to_utf8($name);
//        $name = mb_convert_encoding($name, 'UTF-8', mb_detect_encoding($name));
//        $f_pdf = file_get_contents($name);
        $items = file($name);
        if (empty($items)) return;
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

    public function mkDir($path)
    {
        mkdir($path,0777,true);
    }
    public function getF($pat)
    {
        $pattern = $this->path . $pat;
        return $this->files = glob($pattern, GLOB_NOSORT);
    }
    public function parseF($pat)
    {
        $this->getF($pat);
        foreach ($this->files as &$file) {
            $link = $file = basename($file);
            if (!stripos($file,'KEV')) {$file = ['name' => $link, 'link' => $link]; continue;}
            $m = [];
            preg_match('/KEV_([A-Z0-9]*)_(\d+)_(\d+)/is',$file, $m);
            unset($m[0]);
            $file = 'КЭВ-' . implode('.',$m);
            $file = str_replace('P','П',$file);
            $file = ['name' => $file, 'link' => $link];
        }
        return $this->files;
    }
    public function countF($pat)
    {
        $this->getF($pat);
        return count($this->files);
    }
    public function linkF($href)
    {
        foreach ($this->files as $file) {
            $file = basename($file);
            echo "<a href=\"{$href}?pdf={$file}\" target='_blank'>{$file}</a><br>";
        }
    }
    public function getFiles()
    {
        $this->getF('*.pdf');
        $this->linkF('/izv/all/show/');
    }
}