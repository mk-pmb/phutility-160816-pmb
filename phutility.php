<?php # -*- coding: utf-8, tab-width: 2 -*-

# !! Keep the basics of this loader in sync with module/modld170112/ld.php
# !! to make sure the latter actually works as example.

namespace phutility160816pmb {
  call_user_func(function () {
    if (function_exists(__NAMESPACE__ . '\ld')) { return; }

    $loaderFactorySrc = __DIR__ . '/module/modld170112/factory.php';
    $loaderFactoryFunc = require($loaderFactorySrc);

    function ld() {
      static $f = NULL;
      if ($f !== NULL) { return call_user_func_array($f, func_get_args()); }
      $f = func_get_arg(0);
      return $f;
    }
    ld(call_user_func($loaderFactoryFunc, array(
      'modsrc_prefix' => __DIR__ . '/',
      )));

    function reg() {
      static $f = NULL;
      if ($f !== NULL) { return call_user_func_array($f, func_get_args()); }
      $f = func_get_arg(0);
      return $f;
    }
    reg(ld('*reg'));

    reg($loaderFactorySrc, function () use (&$loaderFactoryFunc) {
      return $loaderFactoryFunc;
    });
  });
}
