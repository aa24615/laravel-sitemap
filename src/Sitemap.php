<?php

namespace Zyan\Sitemap;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class Sitemap
{
    protected $table = null;
    protected $tableName = null;
    protected $where = [];
    protected $field = [];
    protected $url = null;
    protected $map = [];
    protected $path = '';
    public function __construct(string $path = null)
    {
        if($path){
            $this->path = rtrim($path,'/').'/';
        }else{
            $this->path = public_path('/map/');
        }

        config([
            'filesystems.disks.sitemap' =>[
                'driver' => 'local',
                'root' => $this->path,
                'visibility' => 'public',
        ]]);
    }

    protected function getTable(){
        return DB::table($this->tableName);
    }

    public function table(string $tableName){
        $this->tableName = $tableName;
        return $this;
    }

    public function where(array $where){
        $this->where[] = $where;
        return $this;
    }

    public function field(array $field){
        $this->field = $field;
        return $this;
    }

    public function url(string $url){
        $this->url = $url;
        return $this;
    }

    protected function write($p,$content){
        Storage::disk('sitemap')->put($this->tableName.'_'.$p.'.txt',$content);

        $this->map[] = config('app.url').'/'.$this->tableName.'_'.$p.'.txt';
    }

    public function make(){
        $db = $this->getTable();
        $count = $db->where($this->where)->count();
        $p = ceil($count/50000);
        $i = 1;

        while ($i<=$p){
            $url = [];
            $list = $db->where($this->where)->select($this->field)->forPage($i,50000)->get();
            foreach ($list as $val){
                $url[] = 'http://www.php127.com/'.$val->id.'.html';
            }

            $this->write($i,join("\n",$url));
            $i++;
        }

    }

    public function __destruct()
    {
        Storage::disk('sitemap')->put('map.txt',join("\n",$this->map));
    }
}
