<?php
namespace App\Libraries;
class MigrationInventoryService
{
    public function report(): array
    {
        $modules = config('MigrationInventory')->modules;
        $weights = ['native'=>1,'partial'=>.5,'readonly'=>.25,'pending'=>0];
        $score = array_sum(array_map(static fn($m) => $weights[$m['status']] ?? 0, $modules));
        return ['generated_at'=>date(DATE_ATOM),'modules'=>$modules,'counts'=>array_count_values(array_column($modules,'status')),'coverage_percent'=>round($score/max(1,count($modules))*100,1),'menu_parity'=>(new ParityService())->report()];
    }
}
