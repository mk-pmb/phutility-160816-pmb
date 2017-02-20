#!/bin/bash
# -*- coding: utf-8, tab-width: 2 -*-


function test_main () {
  local SELFPATH="$(readlink -m "$BASH_SOURCE"/..)"
  local SELFNAME="$(basename "$BASH_SOURCE" .sh)"
  cd "$SELFPATH" || return $?
  source req.sh --lib || return $?

  local AUTH="$(./guess_login.sed ../.htpasswd)"
  [ -n "$AUTH" ] || return 3$(echo 'E: failed to guess login' >&2)
  AUTH="Authorization: Basic $(echo -n "$AUTH" | base64)"

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

  req PUT "$UP_FN" "$AUTH" L=0
  req GET "$UP_FN"
  req PUT "$UP_FN" "$AUTH" L=30 '' "Hello, I'm a" 'request' 'body.'
  req GET "$UP_FN"

  req DELETE "$UP_FN"
  req DELETE "$UP_FN" "$AUTH"
  req HEAD "$UP_FN"
}










[ "$1" == --lib ] && return 0; test_main "$@"; exit $?
