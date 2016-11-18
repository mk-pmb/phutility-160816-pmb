<?php # -*- coding: utf-8, tab-width: 2 -*-

namespace phutility160816pmb\data;


function array_sorted($a, $flags) { sort($a, $flags); return $a; }
function array_sorted_inplace(&$a, $flags) { sort($a, $flags); return $a; }
function array_ksorted($a, $flags) { ksort($a, $flags); return $a; }
function array_ksorted_inplace(&$a, $flags) { ksort($a, $flags); return $a; }


function array_transpose($arr) {
  $trans = array();
  foreach ($arr as $row_idx => $cells) {
    foreach ($cells as $col_idx => $cell) {
      if (!isset($trans[$col_idx])) { $trans[$col_idx] = array(); }
      $trans[$col_idx][$row_idx] = $cell;
    }
  }
  return $trans;
};












# scroll
