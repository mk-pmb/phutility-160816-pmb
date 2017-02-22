#!/bin/bash
# -*- coding: utf-8, tab-width: 2 -*-


export LANG{,UAGE}=en_US.UTF-8


function req () {
  local SEP_LINE='==---==---==---=='
  if [ "$#:$1" == '1:bom+line' ]; then
    echo $'\xEF\xBB\xBF'"$SEP_LINE"
    return 0
  fi

  [ -n "$HTTP_VERB" ] || local HTTP_VERB="$1"; shift
  [ -n "$HTTP_URL" ]  || local HTTP_URL="$1"; shift

  [ -n "$DAV_HOST" ]  || local DAV_HOST='localhost'
  [ -n "$DAV_PORT" ]  || local DAV_PORT=80
  [ -n "$OUTPUT_FILTER" ] || local OUTPUT_FILTER='unblink.sed'

  local RQLN=(
    "Connection: Close"
    "Host: $DAV_HOST"
    )

  case "$HTTP_VERB" in
    pf:* )
      RQLN+=( 'Content-Length: 0' "Depth: ${HTTP_VERB#*:}" )
      HTTP_VERB='PROPFIND';;
  esac

  local ARG=
  for ARG in "$@"; do case "$ARG" in
    D=* ) RQLN+=( "Depth: ${ARG#*=}" );;
    L=* ) RQLN+=( "Content-Length: ${ARG#*=}" );;
    T=* ) RQLN+=( "Content-Type: ${ARG#*=}" );;
    *': '* ) RQLN+=( "$ARG" );;
    *:* ) RQLN+=( "${ARG/:/: }" );;
    * ) RQLN+=( "$ARG" );;
  esac; done

  RQLN=( "$HTTP_VERB $BASEURL$HTTP_URL HTTP/1.1" "${RQLN[@]}" '' )
  printf '%s\n' "${RQLN[@]}" | sed -rf "$OUTPUT_FILTER"
  (
    # sleep 0.1s
    printf '%s\r\n' "${RQLN[@]}"
    sleep 0.1s
  ) | nc -vvvv -q 2 "$DAV_HOST" "$DAV_PORT" 2>&1 | sed -rf "$OUTPUT_FILTER"

  echo $'\n'"$SEP_LINE"
  return 0
}


function req_test () {
  [ -n "$BASEURL" ] || local BASEURL="/your-webspace/windav/$DEMO_DIR/"
  local LOG_FN="${DAV_HOST:-lh}.$TESTNAME.log"
  ( req bom+line; "$@" ) 2>&1 | req_logsave || return $?
  req_logdiff || return $?
  return 0
}


function req_logsave () {
  export LANG=C
  tee "$LOG_FN" | sed -runf <(echo '
    \~ HTTP/[0-9.]+$~{
      s~^(\S+)\s+~\1\t~
      s~^(\S+\s+)/__demo_baseurl__~\1…~
      s!\s+\S+$!\t-> !p
    }
    s~^HTTP/[0-9.]+ ~~p
    ') | sed -ure '/ $/{N;s~ \n~ ~}'
}


function req_logdiff () {
  local DIFF_FN="${LOG_FN%.log}.diff"
  local MAXLN=90020
  local DIFF_FILES=( "$TESTNAME.good" "$LOG_FN" )
  diff -sU "$MAXLN" "${DIFF_FILES[@]}" >"$DIFF_FN"
  # rm -- "$LOG_FN"
  local DIFF_RV="$?"
  [ "$DIFF_RV" == 0 ] && cat "$DIFF_FN"
  [ "$DIFF_RV" == 1 ] && colordiff -sU 2 "${DIFF_FILES[@]}" | less -R +Gg
  return "$DIFF_RV"
}












[ "$1" == --lib ] && return 0; req "$@"; exit $?
