<?php # -*- coding: utf-8, tab-width: 2 -*-

use phutility160816pmb as phut;
require_once(__DIR__ . '/../../phutility.php');

phut\reg(__FILE__, function () {

  return function (&$cfg, &$rq, &$fatal) {
    $destfn = (string)@$rq['fspath'];
    $clen = phut\ld('web/http/errdoc/simple', 'clen', true);
    $srcfh = @fopen('php://input', 'rb');
    if (!$srcfh) { $fatal(500, 'Cannot read request body'); }

    $existed = @file_exists($destfn);
    $destfh = @fopen($destfn, 'w');
    if (!$destfh) {
      if ($existed && @is_dir($destfn)) {
        $fatal(405, 'Collection (directory) does not support PUT.');
      }
      phut\ld('web/windav/err_not_writable', NULL, [ $fatal,
        'open target file', $destfn, $existed ]);
    }

    $fmode = @$cfg['chmod_file'];
    if (is_int($fmode) || is_string($fmode)) { @chmod($destfn, $fmode); }

    $report = phut\ld('filesys/copy_fh2fh', NULL, [ $srcfh, $destfh,
      [ 'maxbytes' => $clen,
        'blksz' => @$cfg['file_io_blocksize'],
      ] ]);
    @fclose($srcfh);
    @fflush($destfh);
    @fclose($destfh);
    $copy_err = $report['error'];
    $written = $report['written'];
    $report = "\nBytes expected: $clen"
      . "\nBytes read: " . $report['read']
      . "\nBytes written: $written";
    if ($copy_err !== false) {
      $fatal(500, 'File system write error: ' . $copy_err . $report);
    }
    if ($written === $clen) { $fatal(($existed ? 204 : 201), $byte_stats); }
    $fatal(500, 'Byte counts do not match.' . $report);
  };

});
