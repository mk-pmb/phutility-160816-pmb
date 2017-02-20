<?php # -*- coding: utf-8, tab-width: 2 -*-

use phutility160816pmb as phut;
require_once(__DIR__ . '/../../../phutility.php');

phut\reg(__FILE__, function () {
  $EX = [
    'hint404' => 'No such file or directory.',
  ];
  $text2html = phut\ld('data/text/plain2html');

  $ed = function ($code, $hint = NULL, $opt = NULL) use (&$text2html, &$EX) {
    if ($opt === 'fail') { $opt = array( 'fail' => true ); }

    $title = \phutility160816pmb\ld('web/http/statuscode2msg', $code);
    $ctype = 'text/html; charset=UTF-8';
    $html = "<!DOCTYPE html><html><head>\n"
      . '  <meta http-equiv="Content-Type" content="' . $ctype . '">'
      . "\n  <title>$code $title</title>\n"
      . "</head><body>\n"
      . "  <h1>$title</h1>\n";

    if ($hint === NULL) { $hint = @$EX['hint' . $code]; }
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
  $EX['custom'] =& $ed;


  $EX[500] = function ($hint = NULL) use (&$ed) { $ed(500, $hint, 'fail'); };


  $EX['fatal'] = function ($code, $hint = NULL) use (&$ed) {
    $ed($code, $hint, 'fail');
  };


  $EX['clen'] = function ($maxlen = NULL, $clen = NULL) use (&$ed) {
    if ($clen === NULL) { $clen = (string)@$_SERVER['CONTENT_LENGTH']; }
    if (!is_int($clen)) {
      $as_str = (string)$clen;
      $clen = (int)$clen;
      if ($as_str !== (string)$clen) { $clen = -1; }
    }
    if (($maxlen !== NULL) && ($clen > $maxlen)) { $ed(413, NULL, 'fail'); }
    if ($clen >= 0) { return $clen; }
    $ed(411, NULL, 'fail');
  };

















  return $EX;
});
