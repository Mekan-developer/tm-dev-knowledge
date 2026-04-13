<?php

/**
 * Türkmençe barlag habarlary: EN ýygyndan alnyp, esasy düzgünler üýtgedildi.
 */
$p = require __DIR__.'/../en/validation.php';

$p['required'] = ':attribute hökmandy doldurylmaly.';
$p['email'] = ':attribute dogry e-poçta salgysy bolmaly.';
$p['string'] = ':attribute setir bolmaly.';
$p['array'] = ':attribute massiw bolmaly.';
$p['unique'] = 'Bu :attribute eýýäm ýazgyda bar.';
$p['confirmed'] = ':attribute tassyklamasy gabat gelmeýär.';
$p['exists'] = 'Saýlanan :attribute nädogry.';
$p['in'] = 'Saýlanan :attribute nädogry.';

$p['min']['string'] = ':attribute aň az :min simwoldan ybarat bolmaly.';
$p['min']['numeric'] = ':attribute aň az :min bolmaly.';
$p['min']['array'] = ':attribute aň az :min element bolmaly.';

$p['max']['string'] = ':attribute :max simwoldan köp bolman oýnalýar.';
$p['max']['array'] = ':attribute :max elementden köp bolman oýnalýar.';
$p['max']['numeric'] = ':attribute :max-dan uly bolman oýnalýar.';

$p['attributes'] = array_merge($p['attributes'] ?? [], [
    'name' => 'at',
    'email' => 'e-poçta',
    'password' => 'açar söz',
    'password_confirmation' => 'açar söz tassyklamasy',
    'title' => 'ady',
    'guide_category_id' => 'kategoriýa',
    'description' => 'düşündiriş',
    'tags' => 'bellikler',
    'steps' => 'ädimler',
    'subject' => 'mowzuk',
    'message' => 'hat',
    'website' => 'web',
    'color' => 'reňk',
    'sort_order' => 'tertip',
    'remember' => 'ýatda sakla',
]);

return $p;
