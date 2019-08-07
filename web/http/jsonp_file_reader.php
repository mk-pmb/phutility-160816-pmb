<?php

header('Content-Type: application/javascript; charset=UTF-8');

function conforms($start, $extra_chars, $input, $dflt = false) {
  $match = NULL;
  $rgx = '"^(?:' . $start . '[A-Za-z][A-Za-z0-9_' . $extra_chars . ']*)+$"';
  if (preg_match($rgx, (string)$input, $match)) { return $match[0]; }
  return $dflt;
}

function reply($status, $msg = false) {
  http_response_code($status);
  echo conforms('^', '', @$_REQUEST['cb'], 'invalid_cb_param');
  echo "($status, ";
  if ($msg) { die('"' . $msg . '"' . ");\n"); }
}

$fn = substr(conforms('/', '\.\-', @$_SERVER['PATH_INFO'], ''), 1);
if (!$fn) { reply(403, 'Unsafe subpath'); }
if (!@is_file($fn)) { reply(404, 'Not found'); }

$lines = @file($fn);
if (!is_array($lines)) { reply(500, 'Failed to read file'); }
$json_opts = (0
  # | JSON_INVALID_UTF8_IGNORE
  | JSON_INVALID_UTF8_SUBSTITUTE
  # | JSON_PRETTY_PRINT
  # | JSON_THROW_ON_ERROR
  | JSON_UNESCAPED_UNICODE
  );
reply(200);
echo '""';
foreach ($lines as $ln) {
  echo "\n  + ", json_encode($ln, $json_opts);
}
echo ");\n";
