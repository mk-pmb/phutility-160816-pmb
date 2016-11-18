<?php # -*- coding: utf-8, tab-width: 2 -*-

namespace phutility160816pmb;
require_once(__DIR__ . '/../injector.php');
loadlib('compat/opsys');

namespace phutility160816pmb\daemons\daemonized_worker;
use \phutility160816pmb\compat\opsys as os_compat;


function sanity_checks () {
  $server_vars = array('GATEWAY_INTERFACE', 'REMOTE_ADDR', 'REQUEST_METHOD');
  foreach ($server_vars as $sv) {
    if (os_compat\getEnvVar($sv, false) !== false) {
      sanity_checks__warn('environment looks like a webserver');
    }
  }
  sanity_checks__stdin_at_eof();
  return true;
}


function sanity_checks_str () {
  try {
    sanity_checks();
  } catch (\Exception $err) {
    return $err->getMessage();
  }
  return '';
}


function sanity_checks_sane_or_die () {
  $diag = sanity_checks_str();
  if ($diag === '') { return true; }
  die($diag . "\n");
}


function sanity_checks__stdin_at_eof () {
  $stdin = fopen('php://stdin', 'r');
  if(feof($stdin)) { return true; }

  stream_set_blocking($stdin, false);
  $new_buflen = 0;
  stream_set_read_buffer($stdin, $new_buflen);

  $check_readable = array($stdin);
  $check_writeable = NULL;
  $check_out_of_band = NULL;
  $wait_timeout = 0;
  $ready_streams_cnt = stream_select($check_readable, $check_writeable,
    $check_out_of_band, $wait_timeout);
  if ($ready_streams_cnt === false) {
    # select() failed, so we can't safely read without the risk of blocking.
    # Chances are we wouldn't be able to read at all, and we don't have any
    # evidence of stdin being connected to something, so let's assume it's
    # properly disconnected.
    return true;
  }
  if ($ready_streams_cnt > 0) { fgetc($stdin); }
  if (feof($stdin)) { return true; }
  sanity_checks__warn('stdin seems to be connected');
}


function sanity_checks__warn ($reason) {
  throw new \Exception('Probably not daemonized: ' . $reason);
}




















# scroll
