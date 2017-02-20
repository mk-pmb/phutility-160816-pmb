<?php #># -*- coding: utf-8, tab-width: 2 -*-

if (!@chdir(__DIR__)) { die('failed to chdir to script'); }
require_once(__DIR__ . '/../../../../phutility.php');
use phutility160816pmb as phut;

$davcfg = [
  'mechanism' => 'errdoc405',
  # Pro: Handler can be inside the directory that shall be shared.
  # Con: Read-only.
  ];

phut\ld('web/windav/serve', NULL, [ $davcfg ]);
