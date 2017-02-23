#!/bin/sed -urf
# -*- coding: UTF-8, tab-width: 2 -*-

s~\r$~~
s~\b[A-Z][a-z]{2}, [0-9]{1,2} [A-Z][a-z]{2} 2[0-9]{3} [0-9:.]+~{{date/time}}~g
s~([Cc]ontent-?[Ll]ength[> :]+)[0-9]{3,}~\1{{100+}}~g

# sort the "Allow" header verbs:
s~^(Allow: ([A-Z]+,)*)(GET),([A-Z,]+)~\1\4,\3~
s~^(Allow: ([A-Z]+,)*)(HEAD),([A-Z,]+)~\1\4,\3~
s~^(Allow: ([A-Z]+,)*)(OPTIONS),([A-Z,]+)~\1\4,\3~
s~^(Allow: ([A-Z]+,)*)(POST),([A-Z,]+)~\1\4,\3~
s~^(Allow: ([A-Z]+,)*)(PUT),([A-Z,]+)~\1\4,\3~

/^<!DOCTYPE html><html class="errdoc-simple">/{
  : read_full_errdoc
  /<\/html>/!{N;b read_full_errdoc}
  s~\s~ ~g
  s~\s*</?br[ /]*>\s*~¶\n~g
  s~</title>~\t~
  s~<[^<>]*>~~g
  s~^\s+~~
  s~\s*(\t)\s*~\1~
  s~^([0-9]+ ([A-Za-z ]+)\t)\2~\1~
  s~^~{{errdoc simple="~
  s~\t\s*~"}} ~
  s~\s*$~~
}


s~^(Content-Type: text/[a-z]+; charset=|$\
  |Authorization: Basic |$\
  )\S+~\1{{...}}~

s~^(ETag: )[A-Z]*/?"[^"]+"~\1{{...}}~gi
/^(Server|X-Powered-By|Host|Connection|Expires|Cache-Control): /d

s~^Connection to .* succeeded!$~{{connect}}~
s~your-webspace/windav/demo\.[a-z]+\b~__demo_baseurl__~g
s~^(Destination: http://)[a-z0-9.-]+~\1__server__~
