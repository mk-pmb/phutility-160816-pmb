<?php # -*- coding: utf-8, tab-width: 2 -*-

namespace phutility160816pmb;
require_once(__DIR__ . '/../../phutility.php');


reg(__FILE__, function () {
  $rx_fn = "[a-z0-9_\\-]+";
  $rx_fxt = "[a-z0-9]{2,5}";
  $rx_pi = "(dir=(?:/$rx_fn)*)(?:/|$)"
      . "(fn=$|(bfn=$rx_fn)\\.(fext=$rx_fxt))"
      . "(?:\$|/(func=$rx_fn(?:\\.$rx_fxt)*))"
      . "(args=(?:/$rx_fn(?:\\.$rx_fxt)*)*/?)";

  $slot_map = array();
  $add_slot = function ($m) use (&$slot_map) {
    $slot_map[$m[1]] = count($slot_map) + 1;
    return '(';
  };
  $rx_pi = preg_replace_callback("!\\((\\w+)=!", $add_slot, $rx_pi);

  $parse_subfile_url = function ($pi = NULL) use (&$rx_pi, &$slot_map) {
    if ($pi === NULL) { $pi = (string)@$_SERVER['PATH_INFO']; }
    if (preg_match("!^$rx_pi\$!", $pi, $match)) {
      $pi = array_map(function ($slot_num) use ($match) {
        return (string)@$match[$slot_num];
      }, $slot_map);
      $pi['dir'] = ltrim($pi['dir'], '/');
      return $pi;
    }
    return NULL;
  };
  return $parse_subfile_url;
});
