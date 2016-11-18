<?php # -*- coding: utf-8, tab-width: 2 -*-

namespace phutility160816pmb;


function getlibdir () {
  static $libdir = false;
  if ($libdir === false) { $libdir = __DIR__; }
  return $libdir;
}


function loadlib ($relfn) {
  foreach (func_get_args() as $relfn) {
    require_once(getlibdir() . '/' . $relfn . '.php');
  }
  return true;
}
