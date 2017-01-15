<?php # -*- coding: utf-8, tab-width: 2 -*-

namespace phutility160816pmb;
require_once(__DIR__ . '/../phutility.php');

reg(__FILE__, function () {
  return function ($x) {
    if (is_string($x)) { return false; }
    return is_numeric($x);
  };
});
