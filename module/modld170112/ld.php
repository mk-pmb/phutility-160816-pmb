<?php # -*- coding: utf-8, tab-width: 2 -*-

# This file shows how you can create a named function that wraps the
# module manager (anonymous function) produced by the factory script.
# You can use this approach to store the module manager in PHP's global
# (or namespaced) function namespace.


namespace phutility160816pmb {
  call_user_func(function () {
    if (function_exists(__NAMESPACE__ . '\ld')) { return; }

    function ld() {
      static $f = NULL;
      if ($f !== NULL) { return call_user_func_array($f, func_get_args()); }
      $f = func_get_arg(0);
      return $f;
    }

    ld(call_user_func(require(__DIR__ . '/module/modld170112/factory.php'),
      array( 'modsrc_prefix' => __DIR__ . '/' )));
  });
}
