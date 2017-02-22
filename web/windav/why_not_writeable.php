<?php # -*- coding: utf-8, tab-width: 2 -*-

use phutility160816pmb as phut;
require_once(__DIR__ . '/../../phutility.php');

phut\reg(__FILE__, function () {

  return function ($fn, $exists = NULL) {
    if ($exists === NULL) { $exists = @file_exists($destfn); }
    if ($exists) {
      if (!@is_writable($fn)) { return 'Target exists but not writable.'; }
      return '';
    }
    $pardir = dirname($fn);
    if (!@is_dir($pardir)) { return 'Parent is not a directory'; }
    if (!@is_writable($pardir)) { return 'Parent directory is not writeable'; }
    return '';
  };

});
