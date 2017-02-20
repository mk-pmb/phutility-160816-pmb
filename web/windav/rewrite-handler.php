<?php #># -*- coding: utf-8, tab-width: 2 -*-

if (!@chdir(__DIR__)) { die('failed to chdir to script'); }
require_once(__DIR__ . '/../../phutility.php');
use phutility160816pmb as phut;

$davcfg = [
  'mechanism' => 'direct',
  # Pro: Can receive the body of PUT requests.
  # Con: Config must avoid loops, e.g. by storing this handler outside of the
  #      directories on which it shall operate.
  ];

phut\ld('web/windav/serve', NULL, [ $davcfg ]);
