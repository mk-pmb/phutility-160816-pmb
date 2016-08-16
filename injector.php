<?php # -*- coding: utf-8, tab-width: 2 -*-

namespace phutility160816pmb;

define('PHULY_DIR', dirname(__FILE__));

function loadlib($relfn) {
  if (is_array($relfn)) {
    foreach ($relfn as $rfn) {
      require_once(PHULY_DIR . '/' . $rfn . '.php');
    }
    return true;
  }
  require_once(PHULY_DIR . '/' . $relfn . '.php');
  return true;
}
