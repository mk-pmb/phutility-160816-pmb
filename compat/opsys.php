<?php # -*- coding: utf-8, tab-width: 2 -*-

namespace phutility160816pmb\compat\opsys;

function getEnvVar($name, $dflt = '') {
  $ev = getenv($name);
  if (!empty($ev)) { return $ev; }
  if (isset($_ENV[$name])) { return $_ENV[$name]; }
  if (isset($_SERVER[$name])) { return $_SERVER[$name]; }
  return $dflt;
};


function guessUserHomeDir() {
  static $home = NULL;
  if ($home !== false) {
    if ($home !== NULL) { return $home; }
    $home = getEnvVar('HOME');
    if (empty($home)) { $home = getEnvVar('USERPROFILE'); }
    if (empty($home)) { $home = getEnvVar('HOMEPATH'); }
    if (!empty($home)) {
      if (is_dir($home)) { return $home; }
    }
    $home = false;
  }
  throw new \Exception('Unable to guess user home dir');
}
















# scroll
