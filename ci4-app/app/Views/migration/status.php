<!doctype html><html lang="th"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1"><title>Migration Status</title>
<style>body{margin:0;font-family:Tahoma,Arial;background:#f3f5f8;color:#18202a}main{max-width:1100px;margin:auto;padding:28px 18px}pre{white-space:pre-wrap;background:#fff;border:1px solid #dfe5ec;padding:22px;line-height:1.6}table{width:100%;border-collapse:collapse;background:#fff;margin-bottom:24px}th,td{padding:10px;border:1px solid #dfe5ec;text-align:left}.native{color:#087830}.pending{color:#b00020}a{color:#b5121b}</style></head><body><main>
<p><a href="<?= site_url('dashboard') ?>">กลับ Dashboard</a></p><h1>CI4 migration coverage: <?= esc($inventory['coverage_percent']) ?>%</h1>
<table><thead><tr><th>Module</th><th>Status</th><th>Owner</th><th>Evidence / remaining</th></tr></thead><tbody>
<?php foreach($inventory['modules'] as $name=>$module): ?><tr><td><?= esc($name) ?></td><td class="<?= esc($module['status']) ?>"><?= esc($module['status']) ?></td><td><?= esc($module['owner']) ?></td><td><?= esc($module['evidence']) ?></td></tr><?php endforeach ?>
</tbody></table><pre><?= esc($content) ?></pre></main></body></html>
