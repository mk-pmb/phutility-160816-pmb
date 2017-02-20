<?php # -*- coding: utf-8, tab-width: 2 -*-

require_once(__DIR__ . '/../../phutility.php');

\phutility160816pmb\reg(__FILE__, function () {
  return function ($cfg = NULL) {
    if (@$cfg['head'] !== false) {
      $code = @$cfg['statuscode'];
      if ($code !== false) {
        $code = (int)$code;
        if ($code < 100) { $code = 500; }
        http_response_code($code);
      }
      header('Content-Type: text/plain; charset="UTF-8"');
    }

    $d = function ($descr, $val) { echo $descr, ': '; var_dump($val); };
    $d('PHP version', phpversion());

    $sum_read = 0;
    $in_fh = @$cfg['fh'];
    if ($in_fh === NULL) { $in_fh = fopen('php://input', 'rb'); }
    while (true) {
      if (feof($in_fh)) { echo "eof! "; break; }
      $buf = fgets($in_fh);
      $d('read buf', urlencode($buf));
      if ($buf !== false) { $sum_read += strlen($buf); }
    }

    $d('bytes read', $sum_read);
    $d('C-Len', $_SERVER['CONTENT_LENGTH']);

    if (@$cfg['exit'] !== false) { exit(); }
  };
});
