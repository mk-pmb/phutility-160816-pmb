<?php # -*- coding: utf-8, tab-width: 2 -*-

namespace phutility160816pmb;
require_once(__DIR__ . '/../../phutility.php');


reg(__FILE__, function () {
  $rgxtmpl = ld('data/text/rgx/pardeg');
  $rx_pi = $rgxtmpl([ '!^',
    [ '°dir=(?:/æ)*(?:/|$)' ],
    [ '°fn=$|', [ '°bfn=æ' ], '\.', [ '°fext=µ' ] ],
    [ '?:$|', '/', [ '°func=Æ' ] ],
    [ '°args=(?:/Æ)*/?' ],
    '$!'
  ], [
    'Æ' => 'æ(?:\.µ)*',
    'æ' => '[A-Za-z0-9_\-]+',
    'µ' => '[A-Za-z0-9]{2,5}',
  ]);

  $parse_subfile_url = function ($pi = NULL) use (&$rx_pi) {
    if ($pi === NULL) { $pi = (string)@$_SERVER['PATH_INFO']; }
    if (preg_match($rx_pi(), $pi, $match)) {
      $pi = $rx_pi($match);
      $pi['dir'] = ltrim($pi['dir'], '/');
      $pi['relfn'] = $pi['dir'] . $pi['fn'];
      return $pi;
    }
    return NULL;
  };
  return $parse_subfile_url;
});
