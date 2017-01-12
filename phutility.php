<?php # -*- coding: utf-8, tab-width: 2 -*-

namespace phutility160816pmb;

function quot($s) { return '"' . addslashes((string)@$s) . '"'; }


function reg($mod_name, $factory = NULL) {
  static $modules = NULL;
  static $dirlen;
  if ($modules === NULL) {
    $modules = array();
    $dirlen = strlen(__DIR__) + 1;
  }
  if (substr($mod_name, -4) === '.php') {
    if (substr($mod_name, 0, $dirlen) === __DIR__ . '/') {
      $mod_name = substr($mod_name, $dirlen, -4);
    } else {
      throw new \Exception('unsupported module name: ' . quot($mod_name));
    }
  }
  if (array_key_exists($mod_name, $modules)) { return $modules[$mod_name]; }
  if ($factory === NULL) {
    throw new \Exception('request for unknown module: ' . quot($mod_name));
  }
  $mod_obj = call_user_func($factory);
  $modules[$mod_name] =& $mod_obj;
  return $mod_obj;
}


function ld($module, $feature = NULL, $invoke = NULL) {
  $mod_name = '<anonymous>';
  if (!is_array($module)) {
    $mod_name = (string)$module;
    $module = __DIR__ . '/' . $mod_name . '.php';
    if (file_exists($module)) { require_once($module); }
    $module = reg($mod_name);
  }
  $result =& $module;

  if ($feature !== NULL) {
    if (is_string($feature)) { $feature = explode('/', $feature); }
    call_user_func(function () use (&$result, $mod_name, $feature) {
      $path = NULL;
      foreach ($feature as $step) {
        if (!is_array($result)) {
          throw new \Exception('cannot retrieve key ' . quot($step)
            . ': intermediate result is not an array at '
            . ($path === NULL ? 'top-level' : 'path ' . quot($path))
            . ' of module ' . $mod_name);
        }
        if (!array_key_exists($step, $result)) {
          throw new \Exception('cannot find key ' . quot($step)
            . ' in intermediate result at '
            . ($path === NULL ? 'top-level' : 'path ' . quot($path))
            . ' of module ' . $mod_name);
        }
        $result = $result[$step];
        $path = ($path === NULL ? $step : $path . '/' . $step);
      };
    });
  }

  if ($invoke !== NULL) {
    if (!is_callable($result)) {
      throw new \Exception(($feature === NULL ? ''
          : 'feature ' . quot(implode('/', $feature)) . ' of ')
        . 'module ' . $mod_name . ' is not callable');
    }
    $result = ($invoke === true ? call_user_func($result)
      : call_user_func_array($result, $invoke));
  }

  return $result;
}






















# scroll
