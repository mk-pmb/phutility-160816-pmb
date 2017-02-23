#!/bin/bash
# -*- coding: utf-8, tab-width: 2 -*-


function proxy () {
  export LANG{,UAGE}=en_US.UTF-8

  [ -n "$RMT_HOST" ] || local RMT_HOST="$HOSTNAME"
  [ -n "$RMT_PORT" ] || local RMT_PORT=80
  [ -n "$LSN_PORT" ] || local LSN_PORT=1042
  local LOGFN="log.$RMT_HOST.$(date +%y%m%d-%H%M%S).diff"

  local LSN_SOCK="TCP4-LISTEN"
  LSN_SOCK+=":$LSN_PORT"
  LSN_SOCK+=",pf=ip4"
  LSN_SOCK+=",reuseaddr"

  local RMT_SOCK="TCP4"
  RMT_SOCK+=":$RMT_HOST:$RMT_PORT"

  while sleep 0.2s; do
    socat -d -d -v "$LSN_SOCK" "$RMT_SOCK" 2>&1 || return $?
  done | sed -urf unblink.sed | sed -urf 'socat-log-optim.sed' | tee "$LOGFN"
  return 0
}










[ "$1" == --lib ] && return 0; proxy "$@"; exit $?
