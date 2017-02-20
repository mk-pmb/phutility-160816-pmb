#!/bin/bash
# -*- coding: utf-8, tab-width: 2 -*-


function test_main () {
  local SELFPATH="$(readlink -m "$BASH_SOURCE"/..)"
  local SELFNAME="$(basename "$BASH_SOURCE" .sh)"
  cd "$SELFPATH" || return $?
  source req.sh --lib || return $?

  local DEMO_DIR= TESTNAME=
  for DEMO_DIR in ../demo.*/; do
    DEMO_DIR="$(basename "$DEMO_DIR")"
    TESTNAME="$SELFNAME.${DEMO_DIR#demo.}"
    req_test test_basic_reads || return $?
  done
  return 0
}


function test_basic_reads () {
  req OPTIONS ''
  req pf:0 ''
  req pf:1 ''
  local RAW_FN='raw-file-content-download.php'
  req OPTIONS "$RAW_FN"
  req GET     "$RAW_FN"
}










[ "$1" == --lib ] && return 0; test_main "$@"; exit $?
