<?php # -*- coding: utf-8, tab-width: 2 -*-

use phutility160816pmb as phut;
require_once(__DIR__ . '/../../phutility.php');

phut\reg(__FILE__, function () {

  return function ($cfg) {
    if (!is_array($cfg)) { throw new \Exception('expected an array as cfg'); }
    $text2html = phut\ld('data/text/plain2html');
    $files = @$cfg['files'];
    $opt = function ($key, $dflt = NULL) use (&$cfg) {
      $val = @$cfg[$key];
      return (isset($val) ? $val : $dflt);
    };

    if ($opt('head', true) === true) {
      $title = $text2html((string)$opt('title', 'Directory index'));
      http_response_code(is_array($files) ? 200 : 500);
      $ctype = 'text/html; charset=UTF-8';
      header('Content-Type: ' . $ctype);
      echo implode("\n", [
        '<!DOCTYPE html><html><head>',
        '  <meta http-equiv="Content-Type" content="' . $ctype . '">',
        '  <title>', $title, '</title>',
        '  <style type="text/css">',
        '    a { text-decoration: none; }',
        '    #files-list li a { font-family: monospace; }',
        '  </style>',
        '</head><body>',
        '  <h2>', $title, '</h2>',
        '']);
    }

    $cdup = $opt('cdup', true);
    if ($cdup !== false) {
      $cdup = ($cdup === true ? '../' : $text2html($cdup));
      echo '<p id="parent-dir-link"><a href="', $cdup,
        '">&nwarr; Parent directory</a>', "\n";
    }

    $fcnt = 0;
    $files = $opt('files');
    if (is_array($files)) {
      echo "  <ul id=\"files-list\">\n";
      $filter_func = $opt('filter', function ($fn) {
        return (preg_match('!^[A-Za-z0-9_]!', $fn) ? $fn : NULL);
      });
      foreach ($files as $fn) {
        $fn = $filter_func((string)$fn);
        if (!is_string($fn)) { continue; }
        $fn = explode("\t", $text2html($fn), 2);
        if (count($fn) < 2) { $fn[1] = $fn[0]; }
        echo '    <li><a href="', $fn[0], '">', $fn[1], "</a></li>\n";
        $fcnt += 1;
      }
      echo "  </ul>\n";
    } else {
      $files = (string)$files;
      if ($files === '') { $files = '(unknown reason)'; }
      echo "  <p id=\"files-list\" class=\"error\">Cannot list files: ",
        $text2html($files), "</p>\n";
    }

    if ($opt('foot', true) === true) {
      echo implode("\n", [
        '</body></html>',
        '']);
    }
    if ($opt('exit', true) === true) { exit(); }
  };

});
