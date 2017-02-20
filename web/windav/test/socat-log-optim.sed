#!/bin/sed -urf
# -*- coding: UTF-8, tab-width: 2 -*-

s~\\r$~~    # ignore them in log

s~^(<\S+>|)[0-9/]+ [0-9:]+ socat\[[0-9]+\]( N|)~\1\n#~
  #   ^-- log message after connection data with no final EOL
s~^\n~~
/^[<>] [0-9/]+ [0-9:]+\.[0-9]+  length=[0-9]+ from=[0-9]+ to=[0-9]+$/d

/^# (accept|open)ing connection (from|to) /d
/^# successfully connected from local address /d
/^# starting data transfer loop /d
s~(^|\n)# socket \S+ \(fd \S+\) is at EOF$~~

/\S/s~^|\n~& ~g
s~^ ([A-Z]+ .* HTTP/\S+)$~+\1~
s~^ (HTTP/)~-\1~
