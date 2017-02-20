<?php # -*- coding: utf-8, tab-width: 2 -*-

use phutility160816pmb as phut;
require_once(__DIR__ . '/../../phutility.php');

phut\reg(__FILE__, function () {

  return function (&$cfg, &$rq, &$fatal) {
    $fn = (string)@$rq['fspath'];
    if (is_dir($fn)) {
      if (@$cfg['rm-r'] === true) {
        $fatal(501, 'Recursive delete not implemented yet');
      }
      if (@rmdir($fn)) { $fatal(204, 'Empty directory was deleted.'); }
    } else {
      if (@unlink($fn)) { $fatal(204, 'File was deleted.'); }
    }
    $fatal(403, 'Failed to delete, check filesystem permissions.');
  };

});
