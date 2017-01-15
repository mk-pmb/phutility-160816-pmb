<?php # -*- coding: utf-8, tab-width: 2 -*-

namespace phutility160816pmb;
require_once(__DIR__ . '/../injector.php');
loadlib('data/jsonify');

use phutility160816pmb\data as phu_data;

function jsay($x, $opt = NULL) { echo phu_data\jsonify($x, $opt), "\n"; }


jsay(0);        #= `0`
jsay(-0);       #= `0`
jsay(1);        #= `1`
jsay(-1);       #= `-1`
jsay(2.2);      #= `2.2`
jsay(-3.3);     #= `-3.3`

jsay(true);     #= `true`
jsay(false);    #= `false`
jsay(NULL);     #= `null`
jsay(NULL);     #= `null`


$x = array(
  );
jsay($x);












# scroll
