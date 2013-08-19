/**
* Require tree:
* "/*"        - require subdirs
* "*.coffee"  - require by mask
* Example: require scripts/*\/*.coffee - require all coffee files recursive
* Manifest announcement: "*= require", "//= require", "/*= require"
*
* Manifest data:
*= require scripts/test/Ololo.coffee
*= require scripts/*.coffee
*/