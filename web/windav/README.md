
WinDAV
======

Scripts to help my Apache (v2.4+) share files with Windows via its
"Network Location" feature. Masquerades as WebDAV but implements only
a minimal subset and deviates freely where the standard is too strict,
especially about symlinks.





Known Windows behaviors
-----------------------

### Win7 Pro

* ["Using the WebDAV Redirector"][ms-webdav-tutorial]:
  An official Microsoft WebDAV tutorial. A chapter at the bottom explains some
  config settings that you may want to customize. You can also edit and import
  my favorite settings: [.reg.txt](fixes/win7-webdav-cfg.reg.txt) /
  [.reg.zip](fixes/win7-webdav-cfg.reg.zip)
  * Allow [basic auth][wp-basic-auth] over __unencrypted HTTP__.
    If your password is valuable, make sure to arrange some other security
    mechanism around that HTTP connection, like a trusted LAN connection, a VPN
    or an SSH tunnel.
  * Increase the __50 MB default file size limit__ to 2 GB.

* After a fresh boot, the first attempt to access the WebDAV share fails,
  stating the drive is not ready. On the server side this shows as requests
  to the vhost's root directory, which was not WebDAV enabled when this
  problem was first encountered. Not a problem though: When I tried again
  to open the drive, Windows requested the proper URL with full path to the
  WebDAV directory, and it opened without problem.








&nbsp;

  [netloca]: http://web.archive.org/web/20170217131241/http://it.nmu.edu/docs/adding-network-location-windows
  [ms-webdav-tutorial]: http://web.archive.org/web/20170223202425/https://www.iis.net/learn/publish/using-webdav/using-the-webdav-redirector
  [wp-basic-auth]: https://en.wikipedia.org/wiki/Basic_access_authentication

License
-------
<!--#echo json="package.json" key=".license" -->
ISC
<!--/#echo -->
