<?php # -*- coding: utf-8, tab-width: 2 -*-

use phutility160816pmb as phut;
require_once(__DIR__ . '/../phutility.php');

phut\reg(__FILE__, function () {

  $EX = [           # octal     # binary (S_IFMT)
    'S_IFMT'    =>  0170000,    # … 1111 …
    'S_IFIFO'   =>  0010000,    # … ___1 …
    'S_IFCHR'   =>  0020000,    # … __1_ …
    'S_IFDIR'   =>  0040000,    # … _1__ …
    'S_IFBLK'   =>  0060000,    # … _11_ …
    'S_IFREG'   =>  0100000,    # … 1___ …
    'S_IFLNK'   =>  0120000,    # … 1_1_ …
    'S_IFSOCK'  =>  0140000,    # … 11__ …
    'S_IFWHT'   =>  0160000,    # … 111_ …
  ];

  $fi_s = [];
  foreach ($EX as $key => $val) { $fi_s[$val] = $key; }
  $EX['FI_S'] = $fi_s;

  $EX['type'] = function ($mode, $cmp = 0) use (&$EX, &$fi_s) {
    $t = $mode & $EX['S_IFMT'];
    if ($cmp === 0) { return $t; }
    if ($cmp === 'FI_S') { return @$fi_s[$t]; }
    if (is_string($cmp)) { $cmp = (int)@$EX[$cmp]; }
    return ($t === $cmp);
  };










  return $EX;
});
