<?php # -*- coding: utf-8, tab-width: 2 -*-

namespace phutility160816pmb;
require_once(__DIR__ . '/../../phutility.php');


reg(__FILE__, function () {
  $plaintext2html = function ($text, $indent = '    ') {
    if (is_int($indent)) { $indent = str_repeat(' ', $indent); }
    $text = htmlentities((string)$text, ENT_QUOTES, 'UTF-8');
    $text = str_replace("\n ", "\n&nbsp;", $text);
    $text = str_replace('  ', ' &nbsp;', $text);
    $text = str_replace("\n", "<br>\n" . $indent, $text);
    return $text;
  };
  return $plaintext2html;
});
