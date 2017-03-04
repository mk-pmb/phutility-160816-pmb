<?php # -*- coding: utf-8, tab-width: 2 -*-

use phutility160816pmb as phut;
require_once(__DIR__ . '/../../phutility.php');

phut\reg(__FILE__, function () {

  $s2r = phut\ld('web/windav/stat2response');
  $EX = 'pre-declare';

  $EX = function ($fs_path, $depth = 0, $url = '/') use (&$s2r, &$EX) {
    $stat = @stat($fs_path);
    if (!is_array($stat)) { return false; }
    $resp = $s2r($url, $stat);
    $found = [ $resp ];
    if ($depth < 1) { return $found; }
    $url = $resp['href'];
    if (substr($url, -1) !== '/') { return $found; }

    $sub = @scandir($fs_path);
    if (!is_array($sub)) { return $found; }
    $depth -= 1;
    if (substr($fs_path, -1) !== '/') { $fs_path .= '/'; }
    natsort($sub);
    foreach ($sub as $fn) {
      if (substr($fn, 0, 1) === '.') { continue; }
      $resp = $EX($fs_path . $fn, $depth, $url . rawurlencode($fn));
      # NB: Make sure to encode space as %20 (rawurlencode does this)
      #     as opposed to '+' (which urlencode would): Windows 7 Pro
      #     understands a plus sign literally, displays it as '+' (not
      #     space) and sends it verbatim in PROPFIND. Depending on our
      #     PHP script's parsing module, that '+' might become a space,
      #     and the PROPFIND might fail or check the wrong resource.
      #     Sending %20 makes scandir independent from whether '+' is
      #     translated by a future parsing module.
      if ($resp !== false) { $found = array_merge($found, $resp); }
    }

    return $found;
  };






  return $EX;
});
