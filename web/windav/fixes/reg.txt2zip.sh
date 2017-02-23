#!/bin/bash
# -*- coding: utf-8, tab-width: 2 -*-


function reg_txt2zip_sh () {
  local SELFPATH="$(readlink -m "$BASH_SOURCE"/..)"
  cd "$SELFPATH" || return $?
  local REG_FN=
  for REG_FN in *.reg.txt; do
    REG_FN="${REG_FN%.txt}"
    cp -- "$REG_FN"{.txt,} || return $?
    [ ! -f "$REG_FN".zip ] || rm -- "$REG_FN".zip || return $?
    zip -r9 "$REG_FN"{.zip,} || return $?
    rm -- "$REG_FN" || return $?
  done

  return 0
}










reg_txt2zip_sh "$@"; exit $?
