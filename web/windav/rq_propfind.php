<?php # -*- coding: utf-8, tab-width: 2 -*-

use phutility160816pmb as phut;
require_once(__DIR__ . '/../../phutility.php');

phut\reg(__FILE__, function () {

  $rfc4918_ch_9_1 = [
    'default_depth' => INF,
    'max_depth' => 1,
  ];

  return function (&$cfg, &$rq, &$fatal) use (&$rfc4918_ch_9_1) {
    $rq_depth = (string)@$_SERVER['HTTP_DEPTH'];
    $rq_depth = (is_numeric($rq_depth) ? (int)$rq_depth
      : $rfc4918_ch_9_1['default_depth']);
    $max_depth = strtolower((string)@$cfg['max_depth']);
    $max_depth = (substr($max_depth, 0, 3) === 'inf' ? INF : (int)$max_depth);
    if ($max_depth < 1) { $max_depth = $rfc4918_ch_9_1['max_depth']; }
    if ($rq_depth > $max_depth) {
      $fatal(501, "Cannot dive this deep. Maximum depth is $max_depth.");
    }

    $scandir = @$cfg['scandir'];
    if ($scandir === NULL) { $scandir = phut\ld('web/windav/scandir'); }
    $scan = $scandir($rq['fspath'], $rq_depth, $rq['docurl']);
    # print_r($rq); exit();
    if (!is_array($scan)) { $fatal(404); }
    $scan += [ '' => 'multistatus', 'xmlns=' => 'DAV:' ];
    $scan = phut\ld('data/array/to_xml_tags', NULL, [ $scan,
      [ 'indent' => '  ' ] ]);

    http_response_code(207);
    $charset = 'UTF-8';
    header('Content-Type: text/xml; charset="' . $charset . '"');
    echo '<?xml version="1.0" encoding="' . $charset . '"?>', "\n",
      $scan, "\n";
    exit();
  };

});
