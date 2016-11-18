<?php # -*- coding: utf-8, tab-width: 2 -*-

namespace phutility160816pmb\data;


function array_values_check_all_of_type($arr, $exected_type) {
  foreach ($arr as $val) {
    if (gettype($val) !== $exected_type) { return false; }
  }
  return true;
}












# scroll
