=== Easy Gravatars ===

Contributors: dougal
Donate link: http://dougal.gunters.org/donate
Tags: comments, gravatars, gravatar, avatars, avatar, images, personalization
Requires at least: 2.0.4
Tested up to: 2.3
Stable tag: 1.0

Add Gravatars to your comments without modifying any template files. Just
activate, and you're done!

== Description ==

According to the Gravatar.com website, Gravatars are Globally Recognized
Avatars, or an "avatar image that follows you from weblog to weblog
appearing beside your name when you comment on gravatar enabled sites." 
You register with the Gravatar server, and upload an image which you will
use as your avatar. The gravatar image is keyed to your email address, so
that it is unique to you. 

This plugin will display gravatars for the people who comment on your posts.
You do not need to modify any of your template files -- just activate the
plugin, and it will add gravatars to your comments template automatically.

== Installation ==

Copy the easygravatars folder and its contents to your wp-content/plugins
directory, then activate the plugin.

The plugin will add a new Easy Gravatars section under the Options menu.
There, you can configure the size and maximum rating of the gravatars that
you wish to display. You can also set the location of a default image to
display for users who have no gravatar (the default is a 1px transparent
gif), and you can tweak the CSS for the wrapper around the image. 

By default, the gravatar is floated to the right of the comment author's
name, which should work well with most templates.

== Credits ==

Based on a code snippet from Matt Mullenweg:
  http://photomatt.net/2007/10/20/gravatar-enabled/
  http://pastebin.ca/743979

Props to David Potter for pointing out that Gravatar normalizes email
addresses to lowercase before hashing with MD5:
  http://dpotter.net/Technical/index.php/2007/10/22/integrating-gravatar-support/

