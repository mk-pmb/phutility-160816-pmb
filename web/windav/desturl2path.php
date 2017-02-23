<?php #># -*- coding: utf-8, tab-width: 2 -*-

use phutility160816pmb as phut;
require_once(__DIR__ . '/../../phutility.php');

phut\reg(__FILE__, function () {

  return function ($fail, $desturl = NULL) {
    $desturl = (string)@$desturl;
    if ($desturl === '') { $desturl = (string)@$_SERVER['HTTP_DESTINATION']; }
    if ($desturl === '') { return $fail(400, 'Destination URL missing.'); }

    # Check whether it looks like the client meant to operate within the same
    # server, so we can justly discard the domain part of the URI.
    $sv = function ($k, $d) {
      $v = (string)@$_SERVER[$k];
      return ($v === '' ? $d : $v);
    };
    $src_origin = $sv('REQUEST_SCHEME', 'dav') . '://'
      . $sv('HTTP_HOST', '.') . '/';
    $desturl = phut\ld('data/text/unprefix', NULL, [ $src_origin, $desturl ]);
    if ($desturl === false) {
      return $fail(502, 'Unsupported remote destination.' . $src_origin);
    }
    try {
      $destfn = phut\ld('web/http/parse_request', 'docurl2filepath',
        '/' . $desturl);
    } catch (\Exception $err) {
      return $fail(403, 'Cannot map destination: ' . $err->getMessage());
    }

    $ovwr = (string)@$_SERVER['HTTP_OVERWRITE'];
    switch ($ovwr) {
    case '':    # default = true
    case 'T':
      $ovwr = true;
      break;
    case 'F':
      $ovwr = false;
      break;
    default:
      return $fail(500, 'Unsupported value for Overwrite: header field.');
    }

    return [ $destfn, $ovwr ];
  };










});
