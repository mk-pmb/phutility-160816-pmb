<?php # -*- coding: utf-8, tab-width: 2 -*-

namespace phutility160816pmb\data;


function array_samekeys_deeply_strict_equal ($a, $b) {
  if (!is_array($a)) { return false; }
  if (!is_array($b)) { return false; }
  $equal = array_samekeys_cmp($a, $b, function ($x, $y) {
    return ($a === $b ? NULL : false);
  });
  if ($equal === NULL) { $equal = true; }
  return $equal;
}


function array_samekeys_cmp ($a, $b, $cmpfunc, $keysdiff = false) {
  if (is_array($a) && is_array($b)) {
    $keys_a = sorted(array_keys($a));
    $keys_b = sorted(array_keys($b));
    if ($keys_a !== $keys_b) {
      if ($keysdiff !== NULL) { return $keysdiff; }
    }
    foreach (array_unique(array_merge($keys_a, $keys_b)) as $key) {
      $keysdiff = array_samekeys_cmp($a[$key], $b[$key], $cmpfunc, NULL);
      if ($keysdiff !== NULL) { return $keysdiff; }
    }
    return NULL;
  }
  return $cmpfunc($a, $b);
}












# scroll
