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


s~^(Content-Type: text/[a-z]+; charset=|$\
  |Authorization: Basic |$\
  )\S+~\1{{...}}~

s~^(ETag: )[A-Z]*/?"[^"]+"~\1{{...}}~gi
/^(Server|X-Powered-By|Host|Connection): /d

s~^Connection to .* succeeded!$~{{connect}}~
s~your-webspace/windav/demo\.[a-z]+\b~__demo_baseurl__~g
