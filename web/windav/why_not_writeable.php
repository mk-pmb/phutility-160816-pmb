<?php # -*- coding: utf-8, tab-width: 2 -*-

use phutility160816pmb as phut;
require_once(__DIR__ . '/../../phutility.php');

phut\reg(__FILE__, function () {

  return function ($fn) {
    $pardir = dirname($fn);
    if (!is_dir($pardir)) { return 'Parent is not a directory'; }
    if (!is_writable($pardir)) { return 'Parent directory is not writeable'; }
    return '';
  };

});
