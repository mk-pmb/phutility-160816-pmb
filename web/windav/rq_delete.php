<?php # -*- coding: utf-8, tab-width: 2 -*-

use phutility160816pmb as phut;
require_once(__DIR__ . '/../../phutility.php');

phut\reg(__FILE__, function () {

  return function (&$cfg, &$rq, &$fatal) {
    $fn = (string)@$rq['fspath'];
    $state = 'other';
    if (is_link($fn)) {
      # test before file_exists() b/c that one would check the link target.
      $state = 'link';
    } elseif (!file_exists($fn)) {
      $fatal(404, 'No need to delete it.');
    } elseif (is_dir($fn)) {
      $state = 'dir';
    }

    if ($state === 'dir') {
      if (@rmdir($fn)) { $fatal(204, 'Empty directory was deleted.'); }
      if (@$cfg['rm-r'] === true) {
        $fatal(501, 'Recursive delete not implemented yet');
      }
      $fatal(403, 'Failed to delete, probably not empty.');
    }

    if (@unlink($fn)) { $fatal(204, 'File was deleted.'); }
    $fatal(403, 'Failed to delete, check filesystem permissions.');
  };

});
