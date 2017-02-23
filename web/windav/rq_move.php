<?php # -*- coding: utf-8, tab-width: 2 -*-

use phutility160816pmb as phut;
require_once(__DIR__ . '/../../phutility.php');

phut\reg(__FILE__, function () {

  return function (&$cfg, &$rq, &$fatal) {
    $exorsym = function ($fn) { return (file_exists($fn) || is_link($fn)); };
    $srcfn = (string)@$rq['fspath'];

    if (!$exorsym($srcfn)) { $fatal(404, 'Cannot move what is not there.'); }
    list ($destfn, $ovwr) = phut\ld('web/windav/desturl2path', NULL, [$fatal]);
    if ($srcfn === $destfn) { $fatal(403, 'Source and target are the same.'); }

    $srcdir = dirname($srcfn);
    $destdir = dirname($destfn);
    if ($srcdir !== $destdir) {
      $fatal(502, 'Moving across collections is not supported yet.');
    }
    $existed = $exorsym($destfn);
    if ($existed && (!$ovwr)) { return $fatal(412, 'Destination exists.'); }
    if (is_dir($destfn)) { $fatal(403, 'Cannot overwrite a collection.'); }

    if ($existed) {
      if (!@unlink($destfn)) {
        $fatal(403, 'Failed to unmap destination URL.');
      }
    }
    if (@rename($srcfn, $destfn)) { $fatal($existed ? 204 : 201); }

    if (!is_writable($srcdir)) {
      $fatal(403, 'Cannot unmap source: Cannot modify its parent collection.');
    }

    phut\ld('web/windav/err_not_writable', NULL, [ $fatal,
      'move', $destfn, $existed ]);
  };

});
