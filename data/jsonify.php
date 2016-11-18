<?php # -*- coding: utf-8, tab-width: 2 -*-

namespace phutility160816pmb;
use phutility160816pmb as PHULY;
require_once(__DIR__ . '/../injector.php');
loadlib('data/arr_inspect');

namespace phutility160816pmb\data;


define('JSON_ESCAPE_RECOMMENDED', 0
  #| JSON_HEX_AMP
  #| JSON_HEX_APOS
  #| JSON_HEX_QUOT
  #| JSON_HEX_TAG
  | JSON_UNESCAPED_SLASHES
  );
define('JSON_ESCAPE_MINIMAL', JSON_ESCAPE_RECOMMENDED
  | JSON_UNESCAPED_UNICODE
  );


function jsonify_default_opts () {
  static $opts = array(
    'escape' => JSON_ESCAPE_RECOMMENDED,
    'finalWsp' => NULL,
    'indent' => 0,
    'indentMaxDepth' => PHP_INT_MAX,
    'sortKeys' => false,
    'parentContainerType' => NULL,
    );
  return $opts;
}


function jsonify($data, $defuser = NULL, $indent = NULL) {
  $opts = $defuser;
  if (!is_array($defuser)) {
    $opts = array();
    if ($defuser !== NULL) { $opts['defuser'] = $defuser; }
  }
  $opts += jsonify_default_opts();
  $escape = $opts['escape'];
  if (!is_array($data)) { return json_encode($data, $escape); }
  $keys = array_keys($data);
  if (count($keys) === 0) { return '{}'; }

  $isnumarray = array_values_check_all_of_type($keys, 'integer');
  if (!$isnumarray) {
    $sort_flags = $opts['sortKeys'];
    if ($sort_flags === true) { $sort_flags = 0; }
    if (is_int($sort_flags)) { sort($keys, $sort_flags); }
  }
  $json = ($isnumarray ? '[' : '{');

  if ($indent === NULL) { $indent = $opts['indent']; }
  $finalWsp = $opts['finalWsp'];
  if (is_string($indent)) {
    $json .= "\n" . $indent;
    if ($finalWsp === NULL) { $finalWsp = "\n"; }
  } else {
    $indent = (int)$indent;
    if ($indent === 0) {
      $indent = '';
      if ($finalWsp === NULL) { $finalWsp = ''; }
    } elseif ($indent < 0) {
      $indent = str_repeat(' ', -$indent);
      $json .= substr($indent, 1);
      if ($finalWsp === NULL) { $finalWsp = ' '; }
    } else {
      $indent = str_repeat(' ', $indent);
      $json .= "\n" . $indent;
      if ($finalWsp === NULL) { $finalWsp = "\n"; }
    }
  }

  $sep = 1;
  foreach ($keys as $key) {
    if ($sep === 1) {
      $sep = ',';
      if ($indent !== '') { $sep .= "\n" . $indent; }
    } else {
      $json .= $sep;
    }
    if (!$isnumarray) {
      $key = (string)$key;
      $json .= json_encode($key, $escape) . ': ';
    }
    $val = $data[$key];
    if (is_array($val)) {
      $val = jsonify($val, array(
        'parentContainerType' => $json[0],
        ) + $opts);
      $val = str_replace("\n", "\n$indent", $val);
    } else {
      $val = json_encode($val, $escape);
    }
    $json .= $val;
  }
  return $json . $finalWsp . ($isnumarray ? ']' : '}');
}








# scroll
