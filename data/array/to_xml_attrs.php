<?php # -*- coding: utf-8, tab-width: 2 -*-

require_once(__DIR__ . '/../../phutility.php');
use \phutility160816pmb as phut;

phut\reg(__FILE__, function () {

  return function ($a) {
    $o = '';
    if (!is_array($a)) { return $o; }
    foreach ($a as $k => $v) {
      $o .= ' ' . (string)$k . '="'
        . htmlspecialchars((string)$v) . '"';
    }
    return $o;
  };

});
