<?php # -*- coding: utf-8, tab-width: 2 -*-

use phutility160816pmb as phut;
require_once(__DIR__ . '/../../phutility.php');

phut\reg(__FILE__, function () {

  return function (&$cfg, &$rq, &$fatal) use (&$rfc4918_ch_9_3) {
    $destfn = (string)@$rq['fspath'];
    if (@file_exists($destfn)) { $fatal(405, 'Target already exists.'); }

    $pardir = dirname($destfn);
    if (!@file_exists($pardir)) {
      $fatal(409, 'Parent collection does not exist.');
    }
    if (!is_dir($pardir)) {
      $fatal(403, 'Cannot create collection inside a non-collection.');
    }
    if (!is_writeable($pardir)) {
      $fatal(403, 'Parent collection does not accept new members.');
    }

    $clen = (int)@$_SERVER['CONTENT_LENGTH'];
    if ($clen !== 0) {
      $fatal(415, 'Initial collection content is not supported yet.');
    }

    if (!@mkdir($destfn)) { $fatal(403, 'Failed to create the collection.'); }

    $fmode = @$cfg['chmod_dir'];
    if (is_int($fmode) || is_string($fmode)) { @chmod($destfn, $fmode); }

    $fatal(201);
  };

});
