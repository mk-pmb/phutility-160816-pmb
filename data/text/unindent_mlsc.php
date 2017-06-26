<?php # -*- coding: utf-8, tab-width: 2 -*-

require_once(__DIR__ . '/../../phutility.php');
use \phutility160816pmb as phut;

phut\reg(__FILE__, function () {

  $unindent_mlsl = function ($tx) {
    # mlsl = multi-line string literal
    $tx = str_replace("\r", '', $tx);
    if (preg_match("!^\n*( *)!s", $tx, $ind)) {
      $tx = substr($tx, strlen($ind[0]));
      if ($ind[1] !== '') { $tx = str_replace("\n" . $ind[1], "\n", $tx); }
    }
    return $tx;
  };











  return $unindent_mlsl;
});
