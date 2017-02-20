<?php # -*- coding: utf-8, tab-width: 2 -*-

use phutility160816pmb as phut;
require_once(__DIR__ . '/../phutility.php');

phut\reg(__FILE__, function () {

  $EX = function ($srcfh, $destfh, $cfg = NULL) {
    $sum_read = 0;
    $sum_written = 0;
    $blksz = (int)@$cfg['blksz'];
    if ($blksz < 1) { $blksz = 4096; }

    $remain = @$cfg['maxbytes'];
    if ($remain !== NULL) {
      $remain = (int)$remain;
      if ($blksz > $remain) { $blksz = $remain; }
    }

    if ($remain > 0) {
      # phut\ld('web/http/dump_request_body', NULL, [ [ 'fh' => $srcfh ] ]);
    }

    $err = false;
    $report = [];
    $report['read']     =& $sum_read;
    $report['written']  =& $sum_written;
    $report['remain']   =& $remain;
    $report['error']    =& $err;

    $intuitive_api_design_blocksize_offset = 1;
    # ^-- Because no, it's totally not an off-by-one bug!
    #     It's just how PHP API design works. RTFM! :-/
    #     Must be some cargo cult about accounting for a trailing zero-byte in
    #     C buffers. API designer probably didn't know PHP doesn't have them.

    while ($blksz > 0) {
      $buf = fgets($srcfh, $blksz + $intuitive_api_design_blocksize_offset);
      if ($buf === false) {
        $err = 'E_FAIL_READ';
        break;
      }
      $readlen = strlen($buf);
      $sum_read += $readlen;
      $written = fwrite($destfh, $buf);
      if ($written === false) {
        $err = 'E_FAIL_WRITE';
        break;
      }
      $sum_written += $written;
      if ($written !== $readlen) {
        $err = 'E_INCOMPLETE_WRITE';
        break;
      }
      if ($remain !== NULL) {
        $remain -= $readlen;
        if ($blksz > $remain) { $blksz = $remain; }
      }
      if (feof($srcfh)) {
        echo "eof!\n";
        if ($remain !== NULL) { $err = 'E_EARLY_EOF'; }
        break;
      }
    }

    return $report;
  };













  return $EX;
});
