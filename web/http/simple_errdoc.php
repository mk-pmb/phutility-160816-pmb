<?php # -*- coding: utf-8, tab-width: 2 -*-

require_once(__DIR__ . '/../../phutility.php');

\phutility160816pmb\reg(__FILE__, function () {
  return function ($code, $hint = '', $fail = false) {
    $title = \phutility160816pmb\ld('web/http/statuscode2msg', $code);
    $ctype = 'text/html; charset=UTF-8';
    $html = "<!DOCTYPE html><html><head>\n"
      . '  <meta http-equiv="Content-Type" content="' . $ctype . '">'
      . "\n  <title>$code $title</title>\n"
      . "</head><body>\n"
      . "  <h1>$title</h1>\n";
    if (!empty($hint)) {
      $add_para = function ($text) use (&$html) {
        $text = (string)$text;
        if ($text === '') { return; }
        $html .= '  <p>' . htmlentities($text, ENT_QUOTES, 'UTF-8') . "</p>\n";
      };
      if (is_array($hint)) {
        array_map($add_para, $hint);
      } else {
        $add_para($hint);
      }
    }
    $html .= "</body></html>\n";
    if ($fail) {
      http_response_code($code);
      header('Content-Type: ' . $ctype);
      echo $html;
      exit();
    }
    return $html;
  };
});
