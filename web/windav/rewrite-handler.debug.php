<?php #># -*- coding: utf-8, tab-width: 2 -*-

http_response_code(500);  # certainly true if we fail to set a better one.
Header('Content-Type: text/plain; charset=UTF-8');
Header('Expires: Mon, 01 Jan 1990 00:00:00 UTC');

error_reporting(E_ALL);
ini_set('display_errors', '1');
ini_set('html_errors', '0');
ini_set('log_errors', '0');

ob_start();
if (!@chdir(__DIR__)) { die('failed to chdir to script'); }
require_once(__DIR__ . '/../../phutility.php');
use phutility160816pmb as phut;

$davcfg = [
  'mechanism'   => 'direct',
  'chmod_file'  => 0664,
  'chmod_dir'   => 0775,
  ];

phut\ld('web/windav/serve', NULL, [ $davcfg ]);
