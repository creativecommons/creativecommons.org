=== Category Cloud Widget ===
Contributors: vertino
Donate link: http://www.dreamhost.com/donate.cgi?id=7992
Tags: category, cloud, widget, tags, sidebar
Minimum version: 2.0
Requires at least: 2.0
Tested up to: 2.3
Stable tag: trunk

The Category Cloud Widget is a widget that displays your categories as a tag cloud in your sidebar.

== Installation ==

1. Firstly, if you are running WordPress 2.1 or lower, please make sure you have the Widget plugin available at http://automattic.com/code/widgets/
2. Copy `category-cloud.php` to your plugins folder, `/wp-content/plugins/widgets/`
3. Click the 'Activate' link for Category Cloud Widget on your Plugins page (in the WordPress admin interface)
4. Go to Presentation->Sidebar Widgets and drag and drop the widget to wherever you want to show it.
5. Use the configuration menu to customise the widget.

== Frequently Asked Questions ==

= How do I find the widget configuration menu? =

In the WP Admin, navigate to Presentation->Sidebar Widgets. Click on the small configure icon in Category Cloud widget; Edit options to suit your requirements; Save changes; Done.

= Does this work with the Sidebar Modules (SBM) plugin? =

Yes, it should work fine with the latest version of [SBM](http://nybblelabs.org.uk/projects/sidebar-modules/).
Just remember that you will need to activate the widget via the Plugins panel.

= Can I modify the permalinks of the category URLs? =

No you can't. That is beyond the scope of this plugin.

= How do I change the colour of the links? =

Since every WordPress Theme is styled differently, sometimes the CSS styles aren't applied to the Category Cloud widget.
If you are experiencing any problems with the style (i.e. the colour of the links), add the following CSS rules to the `style.css` file in your theme:

`
.catcloud a {color:blue;}
.catcloud a:hover {color:red;}
`

Make sure that you change the colour values above to match your theme's style/colour-scheme.

= My theme puts each category link on a new line, how can I stop this? =

Sometimes theme developers do include CSS style rules that force each hyperlink in your sidebar to be on a new line.
The easiest way to prevent that from happening is to add the following CSS rule to your `style.css` file in your theme:

`
.catcloud a {display:inline!important;}
`

= I can't find the configuration menu for the widget! =

For more information about how to administer and configure your widgets, please refer to the WordPress documentation:

[http://codex.wordpress.org/Widgets_SubPanel](http://codex.wordpress.org/Widgets_SubPanel)
[http://automattic.com/code/widgets/use/](http://automattic.com/code/widgets/use/)

== Examples ==

You can see this in action on my site: [http://leekelleher.com/linklog/](http://leekelleher.com/linklog/)

== Special Thanks ==

Thank you to Matt Kingston, as this widget was based on his [Weighted Categories](http://www.hitormiss.org/projects/weighted-categories/) plugin.
Thanks to [Peter Hasperhoven](http://www.minmen.nl/) for introducing the 'minimum number of posts' option.
