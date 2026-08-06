<?php
namespace App\Libraries;
class ParityService
{
    public function report(): array
    {
        $source=file_get_contents(APPPATH.'Config/Routes.php');$paths=config('ParityPaths')->paths;$missing=[];
        foreach($paths as $path)if(!str_contains($source,"('{$path}'"))$missing[]=$path;
        return ['total'=>count($paths),'explicit'=>count($paths)-count($missing),'missing'=>$missing,'coverage_percent'=>round((count($paths)-count($missing))/max(1,count($paths))*100,1)];
    }
}
