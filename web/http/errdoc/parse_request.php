<?php # -*- coding: utf-8, tab-width: 2 -*-

use phutility160816pmb as phut;
require_once(__DIR__ . '/../../../phutility.php');

phut\reg(__FILE__, function () {

  return function () {
    $fail = function ($why) { throw new \Exception($why); };

    $sv = function ($keys, $dflt = NULL) {
      foreach ($keys as $key) {
        $val = (string)@$_SERVER[$key];
        if ($val !== '') { return $val; }
      }
      if ($dflt !== NULL) { return $dflt; }
      $fail('Expected at least one of ' . implode(', ', $keys));
    };

    $rq = [
      '$' => $sv,
      'status' => (int)@$_SERVER['REDIRECT_STATUS'],
      'method' => $sv([ 'REDIRECT_REQUEST_METHOD', 'REQUEST_METHOD' ]),
      'baseurl' => $sv([ 'CONTEXT_PREFIX' ]),
    ];

    $docurl = $sv([ 'REDIRECT_URL', 'REQUEST_URI' ]);
    # ^-- doc(ument) as in: w/o query string
    $rq['docurl'] = $docurl;

    $sub = explode($rq['baseurl'], $docurl, 2);
    if ($sub[0] !== '') { $fail('Request path not within context'); }
    $sub = (string)@$sub[1];
    $rq['subpath'] = $sub;
    $basepath = $sv([ 'CONTEXT_DOCUMENT_ROOT' ]);
    $rq['fsbase'] = $basepath;
    if (substr($basepath, 0, 1) !== '/') {
      $fail('Cannot resolve target file name.');
    }
    $rq['fspath'] = $rq['fsbase'] . $sub;

    $rq['clen'] = (int)@$_SERVER['CONTENT_LENGTH'];

    return $rq;
  };
});
