#!/bin/bash
# -*- coding: utf-8, tab-width: 2 -*-


function test_main () {
  local SELFPATH="$(readlink -m "$BASH_SOURCE"/..)"
  local SELFNAME="$(basename "$BASH_SOURCE" .sh)"
  cd "$SELFPATH" || return $?
  source req.sh --lib || return $?

  local DEMO_DIR= TESTNAME=
  for DEMO_DIR in ../demo.*/; do
    case "$DEMO_DIR" in
      *.errdoc/ ) continue;;
    esac
    DEMO_DIR="$(basename "$DEMO_DIR")"
    TESTNAME="$SELFNAME.${DEMO_DIR#demo.}"
    req_test test_put_del || return $?
  done
  return 0
}


function test_put_del () {
  local UP_FN='subdir/upload.txt'
  req PUT "$UP_FN" L=0

  req PUT "$UP_FN" +auth L=0
  req GET "$UP_FN"
  req PUT "$UP_FN" +auth L=30 '' "Hello, I'm a" 'request' 'body.'
  req GET "$UP_FN"

  req DELETE "$UP_FN"
  req DELETE "$UP_FN" +auth
  req HEAD "$UP_FN"
}










[ "$1" == --lib ] && return 0; test_main "$@"; exit $?
