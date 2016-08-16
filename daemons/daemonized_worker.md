
Does it have to be a daemon?
============================

If you're absolutely sure you want to write a daemon and want to
care about all the [chores expected from daemons][daemon-expectations],
go for it. Unfortunately, Phutility can't help with that, currently.

If instead you just want to have your script do its work independent
from a user's login session
(sometimes referred to as "contunie running after I disconnect SSH"),
a daemonized worker might be just the right thing for you.


Split the responsibilities
--------------------------

Let your script care about its actual work, and let another program
(examples below) care about what's required or expected to have your
worker script run continuously.

Phutility's `sanity_checks` function can help you protect your users
from accidentially starting your worker in bad ways. It won't ever be
fool-proof, as fools tend to be creative. It can detect some common
mishaps though:

  * Started as a website PHP script in a webserver.
  * Started with dangerous privileges.
  * (More checks to be added.)



Daemonizers
-----------
  * [daemonize][daemonize-web] ([Github repo][daemonize-github])
  * [pm2 (Process Manager 2)][pm2-npm]
    (`pm2 start my-worker.php --interpreter php5`)
  * (to be continued)












  [daemon-expectations]: https://www.python.org/dev/peps/pep-3143/#correct-daemon-behaviour
  [daemonize-web]: http://software.clapper.org/daemonize/
  [daemonize-github]: https://github.com/bmc/daemonize
  [pm2-npm]: https://www.npmjs.com/package/pm2
