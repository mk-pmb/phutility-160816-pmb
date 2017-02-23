<?php # -*- coding: utf-8, tab-width: 2 -*-

use phutility160816pmb as phut;
require_once(__DIR__ . '/../../phutility.php');

phut\reg(__FILE__, function () {

  return function ($fail, $cannot, $fn, $exists = NULL) {
    if ($exists === NULL) { $exists = @file_exists($destfn); }
    $cannot = 'Cannot ' . $cannot;
    if ($exists) {
      if (!@is_writable($fn)) {
        return $fail(403, $cannot . ': Target exists but is not writable.');
      }
      return $fail(403, $cannot .
        ', even though target exists and is writable.');
    }
    $pardir = dirname($fn);
    if (!@is_dir($pardir)) {
      return $fail(409, $cannot . ': Intermediate collection(s) required.');
    }
    if (!@is_writable($pardir)) {
      return $fail(403, $cannot .
        ': Parent collection does not accept new members.');
    }
    return $fail(403, $cannot . '.');
  };

});
