#!/bin/sed -nurf
# -*- coding: utf-8, tab-width: 2 -*-
s~^~\r~
s~^\r(\w+)\s*:#\s*pw="(.*)"\s*$~\1:\2~p
/^\r/!q
