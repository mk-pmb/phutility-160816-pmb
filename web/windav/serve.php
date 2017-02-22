<?php # -*- coding: utf-8, tab-width: 2 -*-

use phutility160816pmb as phut;
require_once(__DIR__ . '/../../phutility.php');

phut\reg(__FILE__, function () {

  return function ($cfg) {
    $fatal = phut\ld('web/http/errdoc/simple', 'fatal');
    http_response_code(500);  # <-- true if we fail to set another one later
    if (!is_array($cfg)) { $fatal(500, 'Config must be an array'); }

    $rq = phut\ld('web/http/errdoc/parse_request', NULL, true);
    if (is_string(@$rq['fspath'])) {
      if (substr($rq['fspath'], -1) === '/') {
        $rq['fspath'] = rtrim($rq['fspath'], '/');
      }
    }

    switch (@$cfg['mechanism']) {
    case 'errdoc405':
      if ($rq['status'] !== 405) { $fatal(500, 'Unexpected invocation'); }
      break;
    case 'direct':
      break;
    default:
      $fatal(500, 'Unsupported config value for "mechanism"');
    }

    $mtd = strtolower($rq['method']);
    switch ($mtd) {
    case 'delete':
    case 'mkcol':
    case 'options':
    case 'propfind':
    case 'put':
      $args = [];
      $args[] =& $cfg;
      $args[] =& $rq;
      $args[] =& $fatal;
      phut\ld('web/windav/rq_' . $mtd, NULL, $args);
      break;
    }

    # print_r($_SERVER); print_r($_POST); exit();
    $fatal(405, 'method: ' . $rq['method']);
  };

});
