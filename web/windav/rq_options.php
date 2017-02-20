<?php # -*- coding: utf-8, tab-width: 2 -*-

use phutility160816pmb as phut;
require_once(__DIR__ . '/../../phutility.php');

phut\reg(__FILE__, function () {

  return function (&$cfg, &$rq, &$fatal) {
    header('DAV: 1');
    header('DAV: <http://apache.org/dav/propset/fs/1>', false);
    header('Content-Type: text/none');
    http_response_code(200);
    exit();
  };

});
