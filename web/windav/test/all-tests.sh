#!/bin/bash
# -*- coding: utf-8, tab-width: 2 -*-


function test_all () {
  local SELFPATH="$(readlink -m "$BASH_SOURCE"/..)"
  cd "$SELFPATH" || return $?
  local TEST_FN=
  for TEST_FN in basics.*.sh; do
    ./"$TEST_FN" || return $?
  done
  return 0
}



test_all "$@"; exit $?
