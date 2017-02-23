<?php # -*- coding: utf-8, tab-width: 2 -*-

namespace phutility160816pmb;
require_once(__DIR__ . '/../../phutility.php');


reg(__FILE__, function () {
  return function ($prefix, $text) {
    $len = strlen($prefix);
    return (substr($text, 0, $len) === $prefix ? substr($text, $len) : false);
  };
});
