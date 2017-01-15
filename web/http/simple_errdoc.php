<?php # -*- coding: utf-8, tab-width: 2 -*-

use phutility160816pmb as phut;
require_once(__DIR__ . '/../../phutility.php');

phut\reg(__FILE__, function () {
  $text2html = phut\ld('data/text/plain2html');
  return function ($code, $hint = '', $opt = NULL) use (&$text2html) {
    if ($opt === 'fail') { $opt = array( 'fail' => true ); }

    $title = \phutility160816pmb\ld('web/http/statuscode2msg', $code);
    $ctype = 'text/html; charset=UTF-8';
    $html = "<!DOCTYPE html><html><head>\n"
      . '  <meta http-equiv="Content-Type" content="' . $ctype . '">'
      . "\n  <title>$code $title</title>\n"
      . "</head><body>\n"
      . "  <h1>$title</h1>\n";
    if (!empty($hint)) {
      $add_para = function ($text) use (&$html, &$text2html) {
        $text = (string)$text;
        if ($text === '') { return; }
        $html .= '  <p>' . $text2html($text) . "</p>\n";
      };
      if (is_array($hint)) {
        array_map($add_para, $hint);
      } else {
        $add_para($hint);
      }
    }

    if (@$opt['end'] !== false) { $html .= "</body></html>\n"; }
    $fail = (bool)@$opt['fail'];
    $ifnull = function ($a, $b) { return ($a === NULL ? $b : $a); };
    if ($ifnull(@$opt['headers'], $fail)) {
      http_response_code($code);
      header('Content-Type: ' . $ctype);
    }
    if ($ifnull(@$opt['print'], $fail)) { echo $html; }
    if ($ifnull(@$opt['exit'], $fail)) { exit(); }
    return $html;
  };
});
