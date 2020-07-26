<?php # -*- coding: utf-8, tab-width: 2 -*-

use phutility160816pmb as phut;
require_once(__DIR__ . '/../phutility.php');

phut\reg(__FILE__, function () {

  return function ($cmd) {
    http_response_code(500);
    if (!is_array($cmd)) { $cmd = [ (string)$cmd ]; }
    $cmd = implode(' ', array_map('escapeshellarg', $cmd));
    foreach ($_SERVER as $key => $val) {
      if (is_string($val)) { putenv("$key=$val"); }
    }
    putenv('GATEWAY_INTERFACE=CGI/1.1');
    $pipe = @popen($cmd . ' 2>&1', 'r');
    if ($pipe === false) {
      phut\ld('web/http/simple_errdoc', NULL, 500,
        'cgi-emu: popen failed', 'fail');
    }
    $headers = [
      'Status' => 200,
      'Content-Type' => 'text/plain',
      ];
    $output = false;
    while (true) {
      $lnbuf = fgets($pipe, 4096);
      if ($lnbuf === false) { break; }
      if ($output === false) {
        if (preg_match("!^([A-Za-z0-9\\-]{2,}):\\s+!", $lnbuf, $hdr)) {
          $lnbuf = trim(substr($lnbuf, strlen($hdr[0])));
          $hdr = $hdr[1];
          $lchdr = strtolower($hdr);
          switch ($lchdr) {
          case 'status':
          case 'content-type':
            $hdr = str_replace(' ', '-',
              ucwords(str_replace('-', ' ', $lchdr)));
            break;
          }
          $headers[$hdr] = $lnbuf;
          continue;
        }
        $output = '';
        if ((strlen($lnbuf) < 4) && (trim($lnbuf) === '')) { continue; }
      }
      $output .= $lnbuf;
    }
    $retval = pclose($pipe);
    if ($retval !== 0) {
      phut\ld('web/http/simple_errdoc', NULL, 500,
        'cgi-emu: exit code = ' . $retval . ', output (' . strlen($output)
        . " bytes):\n" . $output, 'fail');
    }

    $status = (int)substr($headers['Status'], 0, 3);
    unset($headers['Status']);
    if (($status < 200) || ($status > 999)) { $status = 500; }
    http_response_code($status);
    foreach ($headers as $key => $val) { header($key . ': ' . $val); }
    echo $output;
    exit();
  };
});
