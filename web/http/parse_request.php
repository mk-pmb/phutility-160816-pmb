<?php # -*- coding: utf-8, tab-width: 2 -*-

use phutility160816pmb as phut;
require_once(__DIR__ . '/../../phutility.php');

phut\reg(__FILE__, function () {
  $EX = [];

  $sv = function ($keys, $dflt = NULL) {
    foreach ($keys as $key) {
      $val = (string)@$_SERVER[$key];
      if ($val !== '') { return $val; }
    }
    if ($dflt !== NULL) { return $dflt; }
    throw new \Exception('Expected at least one of ' . implode(', ', $keys));
  };
  $EX['var'] =& $sv;


  $url2path = function ($docurl) use (&$sv) {
    $baseurl = $sv([ 'CONTEXT_PREFIX' ], '/');
    $baselen = strlen($baseurl);
    if (substr($docurl, 0, $baselen) !== $baseurl) {
      throw new \Exception('Request path not within webspace context.');
    }
    $basepath = $sv([ 'CONTEXT_DOCUMENT_ROOT', 'DOCUMENT_ROOT' ], '/');
    if (substr($basepath, 0, 1) !== '/') {
      throw new \Exception('Webspace root path must be absolute.');
    }
    $sub = substr($docurl, $baselen);
    $sub = urldecode($sub);
    $sub = trim($sub, '/');
    # ^-- Trim slashes on both sides, since we can't rely on the client for
    #     assumptions about the local file system anyway. If you want to
    #     determine client's intent, you can still look at the docurl.
    return $basepath . $sub;
  };
  $EX['docurl2filepath'] =& $url2path;


  $EX['rq'] = function () use (&$sv, &$url2path) {
    $docurl = $sv([ 'REDIRECT_URL', 'REQUEST_URI' ]);
    # ^-- doc(ument) as in: w/o query string
    return [
      'status'  => (int)@$_SERVER['REDIRECT_STATUS'],
      'method'  => $sv([ 'REDIRECT_REQUEST_METHOD', 'REQUEST_METHOD' ]),
      'docurl'  => $docurl,
      'fspath'  => $url2path($docurl),
      'clen'    => (int)@$_SERVER['CONTENT_LENGTH'],
    ];
  };










  return $EX;
});
