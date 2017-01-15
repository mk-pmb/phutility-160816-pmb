<?php # -*- coding: utf-8, tab-width: 2 -*-

require_once(__DIR__ . '/../../phutility.php');
use \phutility160816pmb as phut;

phut\reg(__FILE__, function () {

  $ari = array();
  $isnum = phut\ld('data/isnum');

  $ari['ckvAllOfType'] = function (&$arr, $exected_type) use ($isnum) {
    foreach ($arr as $val) {
      if (gettype($val) !== $exected_type) { return false; }
    }
    return true;
  };











  return $ari;
});
