#!/bin/bash
# -*- coding: utf-8, tab-width: 2 -*-


function test_main () {
  local SELFPATH="$(readlink -m "$BASH_SOURCE"/..)"
  local SELFNAME="$(basename "$BASH_SOURCE" .sh)"
  cd "$SELFPATH" || return $?
  source req.sh --lib || return $?

  local DEMO_DIR= TESTNAME= UPLOADS=
  for DEMO_DIR in ../demo.*/; do
    case "$DEMO_DIR" in
      *.errdoc/ ) continue;;
    esac
    UPLOADS="$DEMO_DIR/subdir"
    DEMO_DIR="$(basename "$DEMO_DIR")"
    TESTNAME="$SELFNAME.${DEMO_DIR#demo.}"
    req_test test_rename || return $?
  done
  return 0
}


function test_rename () {
  BASEURL+='subdir/'
  local DEST="Destination: http://<<DAV_HOST>><<BASEURL>>"
  local NF='New%20Folder%20%28_%29'
  local NF2="${NF/_/2}"
  local BLD='HeavyBoulder'
  local KHF='KneeHighFence'

  req MKCOL   $KHF  +auth
  req MKCOL   $NF2/ +auth
  req PUT     $BLD  +auth   L=9 ''  'I rock.'
  req MOVE    $NF2/ +auth   "$DEST"$NF2 -ovr  X=same
  req MOVE    $NF2/ +auth   "$DEST"$NF2       X=same
  req MOVE    $NF2/ +auth   "$DEST"$KHF -ovr  X=flinch
  req MOVE    $NF2/ +auth   "$DEST"$KHF       X=insurmountable
  req DELETE  $KHF/ +auth   X=was-dir

  req MOVE    $NF2/ +auth   "$DEST"$BLD -ovr  X=flinch
  req GET     $BLD  +auth   X=file/heavy
  req MOVE    $NF2/ +auth   "$DEST"$BLD       X=smash
  req DELETE  $BLD/ +auth   X=was-dir
  req DELETE  $NF2/ +auth   X=was-moved
}










[ "$1" == --lib ] && return 0; test_main "$@"; exit $?
