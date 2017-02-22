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
    req_test test_mkcol || return $?
  done
  return 0
}


function test_mkcol () {
  local FOO='subdir/foo'
  req HEAD    $FOO
  req MKCOL   $FOO/bar
  req MKCOL   $FOO/bar    "$AUTH"
  req MKCOL   $FOO        "$AUTH"   L=7 '' hello
  req MKCOL   $FOO        "$AUTH"
  req MKCOL   $FOO/bar    "$AUTH"
  req MKCOL   $FOO/bar    "$AUTH"
  req MKCOL   $FOO/bar/   "$AUTH"
  req DELETE  $FOO        "$AUTH"
  req DELETE  $FOO/       "$AUTH"
  req DELETE  $FOO/bar    "$AUTH"
  req DELETE  $FOO/bar/   "$AUTH"
  req DELETE  $FOO/bar/   "$AUTH"
  req DELETE  $FOO        "$AUTH"
  req DELETE  $FOO/       "$AUTH"
  req DELETE  $FOO/       "$AUTH"
  req DELETE  $FOO        "$AUTH"
}










[ "$1" == --lib ] && return 0; test_main "$@"; exit $?
