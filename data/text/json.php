<?php # -*- coding: utf-8, tab-width: 2 -*-

require_once(__DIR__ . '/../../phutility.php');
use \phutility160816pmb as phut;

phut\reg(__FILE__, function () {

  $json = array();

  $defopt = array(
    'escape' => 'recommended',
    'finalWsp' => NULL,
    'indent' => 0,
    'indentMaxDepth' => PHP_INT_MAX,
    'parentContainerType' => NULL,
    'sortKeys' => false,
    );
  $defopt['esc_recommended'] = (0
    #| JSON_HEX_AMP
    #| JSON_HEX_APOS
    #| JSON_HEX_QUOT
    #| JSON_HEX_TAG
    | JSON_UNESCAPED_SLASHES
    );
  $defopt['esc_minimal'] = ($defopt['esc_recommended']
    | JSON_UNESCAPED_UNICODE
    );
  $json['defaultOpts'] =& $defopt;

  $chkNumbersOnly = function (&$list) {
    foreach ($list as $item) {
      if (!is_numeric($item)) { return false; }
    }
    return true;
  };

  $stfy = function ($data, $defuser = NULL, $indent = NULL
    ) use (&$defopt, &$stfy, &$chkNumbersOnly)
  {
    $opts = $defuser;
    if (!is_array($defuser)) {
      $opts = array();
      if ($defuser !== NULL) { $opts['defuser'] = $defuser; }
    }
    $opts += $defopt;
    $escape = $opts['escape'];
    if (is_string($escape)) { $escape = $opts['esc_' . $escape]; }
    if (!(is_int($escape) || is_long($escape))) {
      throw new \Exception('Option escape must be a number');
    }

    if (!is_array($data)) {
      if ($data === NULL) { return 'null'; }
      return json_encode($data, $escape);
    }
    $keys = array_keys($data);
    if (count($keys) === 0) { return '{}'; }

    $isnumarray = $chkNumbersOnly($keys);
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
      }
      if ($finalWsp === NULL) { $finalWsp = "\n"; }
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
        $val = $stfy($val, array(
          'parentContainerType' => $json[0],
          ) + $opts);
        $val = str_replace("\n", "\n$indent", $val);
      } else {
        $val = json_encode($val, $escape);
      }
      $json .= $val;
    }
    return $json . $finalWsp . ($isnumarray ? ']' : '}');
  };
  $json['stringify'] = $stfy;




















  return $json;
});
