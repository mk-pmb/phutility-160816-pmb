
WinDAV
======

Scripts to help your Apache (v2.4+) share files with Windows via its
"Network Location" feature. Masquerades as WebDAV but implements only
a minimal subset and deviates freely where the standard is too strict,
especially about symlinks.





Known Windows anomalies
=======================

Win7 Pro
--------

* After a fresh boot, the first attempt to access the WebDAV share fails,
  stating the drive is not ready. On the server side this shows as requests
  to the vhost's root directory, which was not WebDAV enabled when this
  problem was first encountered. Not a problem though: When I tried again
  to open the drive, Windows requested the proper URL with full path to the
  WebDAV directory, and it opened without problem.







&nbsp;

  [netloca]: http://web.archive.org/web/20170217131241/http://it.nmu.edu/docs/adding-network-location-windows

License
-------
<!--#echo json="package.json" key=".license" -->
ISC
<!--/#echo -->
