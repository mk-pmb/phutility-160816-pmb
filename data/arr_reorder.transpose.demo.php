<?php # -*- coding: utf-8, tab-width: 2 -*-

namespace phutility160816pmb;
require_once(__DIR__ . '/../injector.php');
loadlib('data/arr_reorder', 'data/jsonify');

use phutility160816pmb\data as phu_data;

function jsay($x) { echo phu_data\jsonify($x, array('indent' => -2 )), "\n"; }


$names = ['Anna', 'Bernd', 'Cassie'];
$verbs = ['eats', 'likes', 'learns'];
$nouns = ['meat', 'fruit', 'a poem'];

jsay(phu_data\array_transpose(array($names, $verbs, $nouns)));
#= `[ [ "Anna",`
#= `    "eats",`
#= `    "meat" ],`
#= `  [ "Bernd",`
#= `    "likes",`
#= `    "fruit" ],`
#= `  [ "Cassie",`
#= `    "learns",`
#= `    "a poem" ] ]`













# scroll
