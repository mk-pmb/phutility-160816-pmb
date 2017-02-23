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
    req_test test_mkcol || return $?
  done
  return 0
}


function test_mkcol () {
  BASEURL+='subdir/'
  req HEAD    foo
  req MKCOL   foo/bar
  req MKCOL   foo/bar   +auth
  req MKCOL   foo       +auth   L=7 '' hello
  req MKCOL   foo       +auth
  req MKCOL   foo/bar   +auth
  req MKCOL   foo/bar   +auth
  req MKCOL   foo/bar/  +auth
  req DELETE  foo       +auth
  req DELETE  foo/      +auth
  req DELETE  foo/bar   +auth
  req DELETE  foo/bar/  +auth
  req DELETE  foo/bar/  +auth
  req DELETE  foo       +auth
  req DELETE  foo/      +auth
  req DELETE  foo/      +auth
  req DELETE  foo       +auth
}










[ "$1" == --lib ] && return 0; test_main "$@"; exit $?
