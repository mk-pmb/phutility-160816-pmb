<?php #># -*- coding: utf-8, tab-width: 2 -*-

use phutility160816pmb as phut;
require_once(__DIR__ . '/../../phutility.php');

phut\reg(__FILE__, function () {

  $inode_types = phut\ld('filesys/stat-inodetypes', 'type');

  return function ($url, $stat) use (&$inode_types) {
    $size = $stat['size'];
    $inode_type = $inode_types($stat['mode'], 'FI_S');
    $res_type = '';
    $url = rtrim($url, '/');
    switch ($inode_type) {
    case 'S_IFDIR':
      $res_type = [ 'collection' => '' ];
      $url .= '/';
      $size = NULL;
      break;
    }

    return [
      '' => 'response',
      'href' => $url,
      'propstat' => [
        'status' => 'HTTP/1.1 200 OK',
        'prop' => [
          'resourcetype' => $res_type,
          'creationdate' => gmdate('r', $stat['ctime']),
          'getlastmodified' => gmdate('r', $stat['mtime']),
          'getcontentlength' => $size,
          'supportedlock' => '', 'lockdiscovery' => '',
        ],
      ],
    ];
  };







  return $EX;
});
