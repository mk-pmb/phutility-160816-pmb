<?php # -*- coding: utf-8, tab-width: 2 -*-

return function ($cfg) {
  $quot = function($s) { return '"' . addslashes((string)@$s) . '"'; };

  $modules = array();
  $srcprfx = $cfg['modsrc_prefix'];

  $reg = function($mod_name, $factory = NULL) use (&$modules, $srcprfx, $quot) {
    static $prfxlen = NULL;
    static $srcsufx = '.php';
    static $sufxlen = 4;
    if ($prfxlen === NULL) { $prfxlen = strlen($srcprfx); }
    if (substr($mod_name, -$sufxlen) === $srcsufx) {
      if (substr($mod_name, 0, $prfxlen) === $srcprfx) {
        $mod_name = substr($mod_name, $prfxlen, -$sufxlen);
      } else {
        throw new \Exception('unsupported module name: ' . $quot($mod_name));
      }
    }
    if (array_key_exists($mod_name, $modules)) { return $modules[$mod_name]; }
    if ($factory === true) {
      $factory = NULL;
      $src_fn = $srcprfx . $mod_name . $srcsufx;
      if (@file_exists($src_fn)) { require_once($src_fn); }
    }
    if (array_key_exists($mod_name, $modules)) { return $modules[$mod_name]; }
    if ($factory === NULL) {
      throw new \Exception('request for unknown module: ' . $quot($mod_name));
    }
    $mod_obj = call_user_func($factory);
    $modules[$mod_name] =& $mod_obj;
    return $mod_obj;
  };
  $modules['*reg']  = $reg;

  $ld = function ($module, $feature = NULL, $invoke = NULL) use ($reg, $quot) {
    $mod_name = '<anonymous>';
    if (!is_array($module)) {
      $mod_name = (string)$module;
      $module = $reg($mod_name, true);
    }
    $result =& $module;

    if ($feature !== NULL) {
      $dive = function ($step) use (&$result, $mod_name, $feature) {
        static $path = NULL;
        if (!is_array($result)) {
          throw new \Exception('cannot retrieve key ' . $quot($step)
            . ': intermediate result is not an array at '
            . ($path === NULL ? 'top-level' : 'path ' . $quot($path))
            . ' of module ' . $mod_name);
        }
        if (!array_key_exists($step, $result)) {
          throw new \Exception('cannot find key ' . $quot($step)
            . ' in intermediate result at '
            . ($path === NULL ? 'top-level' : 'path ' . $quot($path))
            . ' of module ' . $mod_name);
        }
        $result = $result[$step];
        $path = ($path === NULL ? $step : $path . '/' . $step);
      };
      if (is_string($feature)) { $feature = explode('/', $feature); }
      if (is_array($feature)) {
        array_map($dive, $feature);
      } else {
        $dive($feature);
      }
    }

    if ($invoke !== NULL) {
      if (!is_callable($result)) {
        throw new \Exception(($feature === NULL ? ''
            : 'feature ' . $quot(implode('/', $feature)) . ' of ')
          . 'module ' . $mod_name . ' is not callable');
      }
      if ((!is_array($invoke)) || (func_num_args() > 3)) {
        $invoke = array_slice(func_get_args(), 2);
      }
      $result = call_user_func_array($result, $invoke);
    }

    return $result;
  };

  $modules['*ld']  = $ld;























  return $ld;
};
