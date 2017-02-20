<?php # -*- coding: utf-8, tab-width: 2 -*-

require_once(__DIR__ . '/../../phutility.php');
use \phutility160816pmb as phut;

phut\reg(__FILE__, function () {

  $toattrs = phut\ld('data/array/to_xml_attrs');
  $EX = 'pre-declare';

  $EX = function ($arr, $cfg = NULL, $indent = "\n") use (&$EX, &$toattrs) {
    $add_indent = @$cfg['indent'];
    $add_indent = (is_int($add_indent) ? str_repeat(' ', $add_indent)
      : (string)$add_indent);
    $sub_indent = $indent . $add_indent;

    $innerxml = '';
    $attrs = $toattrs(@$arr['=']);
    $tagname = (string)@$arr[''];
    # * There's no $cfg to override it because you can just pass
    #   ([ '' => 'mytagname' ] + $original_arr) as $arr.
    # * We accept the empty string as a tag name because it's just one
    #   example of many bad tag name strings you could have in your array,
    #   and checking one failure mode invites feature creep.

    foreach ($arr as $subtag => $content) {
      $subtag = (string)$subtag;
      if ($subtag === '') { continue; }
      if ($content === NULL) { continue; }
      # no: might be empty subtag! # if ($content === '') { continue; }
      $sub_alpha = ctype_alpha($subtag[0]);
      if ($sub_alpha) {
        if (substr($subtag, -1) === '=') {
          $attrs .= ' ' . $subtag . '"'
            . htmlspecialchars((string)$content) . '"';
          continue;
        }
        if (!is_array($content)) { $content = [ $content ]; }
      }
      if ($sub_alpha || is_numeric($subtag)) {
        if (is_array($content)) {
          if ($sub_alpha) { $content += [ '' => $subtag ]; }
          $innerxml .= $sub_indent . $EX($content, $cfg, $sub_indent);
          continue;
        }
        $innerxml .= htmlspecialchars((string)$content);
        continue;
      }
      $content = 'unsupported: ' . htmlspecialchars((string)$subtag
        . ' = ' . (string)$content);
      if (@$cfg['errors'] !== 'inline') { throw new \Exception($content); }
      $innerxml .= "$sub_indent<!-- $content -->";
    }

    if (substr($innerxml, -1) === '>') { $innerxml .= $indent; }
    return ('<' . $tagname . $attrs . ($innerxml === '' ? '/>'
      : ">$innerxml</$tagname>"));
  };






  return $EX;
});
