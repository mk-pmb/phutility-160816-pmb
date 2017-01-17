<?php # -*- coding: utf-8, tab-width: 2 -*-
#
# pardeg = parentheses + degree, the magic symbol used in this template
# function.

require_once(__DIR__ . '/../../../phutility.php');
use \phutility160816pmb as phut;

phut\reg(__FILE__, function () {

  $flatten_rgx = function ($rgx, $enclose = '()') use (&$flatten_rgx) {
    if (!is_array($rgx)) { return $rgx; }
    $rgx = implode('', array_map($flatten_rgx, $rgx));
    $rgx = @$enclose[0] . $rgx . @$enclose[1];
    return $rgx;
  };

  $pardeg_compile = function ($rgx, $abbrev = NULL) use (&$flatten_rgx) {
    $rgx = $flatten_rgx($rgx, '');

    if (is_array($abbrev)) {
      foreach ($abbrev as $abbr => $text) {
        $rgx = str_replace($abbr, $text, $rgx);
      }
    }

    $slot_map = [];
    $add_slot = function ($m) use (&$slot_map) {
      $slot_map[$m[1]] = count($slot_map) + 1;
      return '(';
    };
    $rgx = preg_replace_callback('!\(°([\+-<>@-Z_-z~]+)=!', $add_slot, $rgx);

    $pardeg_rx_func = function ($m = NULL) use (&$rgx, &$slot_map) {
      if ($m === NULL) { return $rgx; }
      if (is_array($m)) {
        return array_map(function ($grp) use ($m) {
          return (string)@$m[$grp];
        }, $slot_map);
      }
      throw new \Exception('unsupported argument to pardeg_rx_func');
    };

    return $pardeg_rx_func;
  };




















  return $pardeg_compile;
});
