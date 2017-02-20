#!/bin/sed -nurf
# -*- coding: UTF-8, tab-width: 2 -*-

: skip
  \~^[ +-]?[A-Z]+ /\S+ HTTP/[0-9]+\.[0-9]+$~b verb_ln
  n
b skip


: verb_ln
  s~^[ +-]~~
  s~^([A-Z]+ )\S*(/dav/)in/upload/~\1\2up/~
: headers
  N
  s~\n[ +-]~\n~g
  /\n$/!b headers

  \~^PROPFIND \S*/in/upload ~d    # directory redirect
  \~^PROPFIND \S*/\.git/? ~d

  s~\ntranslate: f\n~\n~g

  s~\n($\
    |Cache-Control|$\
    |Connection|$\
    |ETag|$\
    |Host|$\
    |Keep-Alive|$\
    |Pragma|$\
    |User-Agent|$\
    |$\
    ): [^\n]+~~ig

  s~\nContent-Length: ~\nL=~ig
  s~\nContent-Type: ~\nT=~ig
  s~(\nT=\S+); charset=(\S+)~\1~g
  s~(<opaquelocktoken:[A-Za-z0-9]+)-[A-Za-z0-9-]+~\1~g
  s~\nDepth: ~\nD=~ig

  s~\s+$~~
  s~\n~¶ ~g
  p
b skip


