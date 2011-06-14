=== Plugin Name ===
Contributors: sidewindernet
Tags: album, gallery
Requires at least: 2.9
Tested up to: 3.1.3
Stable tag: 1.0

This plugin allows users to create albums (gallery containers).

== Description ==

WordPress already has phenomenal, built-in support for image galleries, and theme developers are starting to take full advantage of these features. However, one of the features missing is an easy to way create albums (a container for galleries). Picasso aims to solve this problem by introducing a new [album] short-code. It works by grabbing the featured image of child pages, and linking each image to its respective page. Thus, the parent page becomes the album, and each child page is a gallery.

If you are having issues with this plugin please submit a comment <a href="http://www.johnciacia.com/projects/picasso/">here</a>.

== Installation ==

1. Download picazzo.zip and unzip it.
2. Upload the plugin folder to wp-content/plugins/ and activate from the Plugin administrative menu.

== Frequently Asked Questions ==
= How to I create an album? =
Create a page, and use the [album] short-code. Be sure all your galleries use this page as their parent.
= I can’t seem to set the featured image on my pages or posts. =
You need to explicitly add support by appending add_theme_support( 'post-thumbnails' ); to your functions.php file.