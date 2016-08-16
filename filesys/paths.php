<?php # -*- coding: utf-8, tab-width: 2 -*-

namespace phutility160816pmb;
require_once(dirname(__FILE__) . '/../injector.php');
loadlib('compat/opsys');

namespace phutility160816pmb\filesys;
use \phutility160816pmb\compat\opsys as os_compat;

function translate_path($path) {
  if (is_array($path)) { return array_map($path, 'translate_path'); }
  if (substr($path, 0, 2) === '~/') {
    return os_compat\guessUserHomeDir() . substr($path, 1);
  }
  return $path;
}


