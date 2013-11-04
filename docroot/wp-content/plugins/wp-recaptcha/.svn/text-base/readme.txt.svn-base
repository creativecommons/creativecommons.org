=== Plugin Name ===
Contributors: BlaenkDenum
Tags: comments, registration, recaptcha, antispam, mailhide, captcha
Requires at least: 2.7
Tested up to: 2.9.1
Stable tag: 3.1.6

Integrates reCAPTCHA anti-spam methods with WordPress including comment, registration, and email spam protection.

== Description ==

= Notice =

If anyone is interested in taking up development of this plugin, please contact me at blaenk@gmail.com. I would be glad to provide access to the plugin repository. Preferably someone who has experience with the WP and WP multisite APIs (primarily their authorization and options APIs).

If you would like to simply contribute patches, you are welcome to do so at the [github repository](https://github.com/blaenk/wp-recaptcha).

Otherwise, if anyone encounters issues with this plugin, you might want to give [this one](http://wordpress.org/extend/plugins/bwp-recaptcha/) a try.

= What is reCAPTCHA? =

[reCAPTCHA](http://recaptcha.net/ "reCAPTCHA") is an anti-spam method originating from [Carnegie Mellon University](http://www.cmu.edu/index.shtml "Carnegie Mellon University"), then acquired by [Google](http://www.google.com/recaptcha) which uses [CAPTCHAs](http://recaptcha.net/captcha.html "CAPTCHA") in a [genius way](http://recaptcha.net/learnmore.html "How Does it Work? - reCAPTCHA"). Instead of randomly generating useless characters which users grow tired of continuosly typing in, risking the possibility that spammers will eventually write sophisticated spam bots which use [OCR](http://en.wikipedia.org/wiki/Optical_character_recognition "Optical Character Recognition - Wikipedia") libraries to read the characters, reCAPTCHA uses a different approach.

The world is in the process of digitizing books by use of automated machines which employ the use of Optical Character Recognition software. Sometimes the certain words cannot be read by the software. reCAPTCHA uses a combination of these words, further distorts them, and then constructs a CAPTCHA image. After a certain percentage of users solve the 'unknown' word the same way, it is assumed that it is the correct spelling of the word. This helps digitize books, giving users a ***reason*** to solve reCAPTCHA forms. Because the industry level scanners and OCR software which are used to digitize the books can't read the words with which the CAPTCHAs are constructed, it is safe to assume that in-house spam-bot OCR techniques will not be able to bypass the resulting CAPTCHA, which is a further distortion of the unreadable word.

reCAPTCHA is probably the most popular and widely accepted CAPTCHA systems by both end-users and site-owners. It is used by such sites prominent sites as [Facebook](http://www.facebook.com), [Twitter](http://www.twitter.com), to the Average Joe's little blog out there on the corner of the Internet.

It is accessible by everyone. If the user has trouble reading the CAPTCHA challenge, he or she has the option of requesting a new one. If this does not help, there is also an audio challenge which users may use.

== Installation ==

To install in regular WordPress and [WordPress MultiSite](http://codex.wordpress.org/Create_A_Network):

1. Upload the `wp-recaptcha` folder to the `/wp-content/plugins/` directory
1. Activate the plugin through the `Plugins` menu in WordPress
1. Get the reCAPTCHA keys [here](http://recaptcha.net/api/getkey?domain=www.blaenkdenum.com&app=wordpress "reCAPTCHA API keys") and/or the MailHide keys [here](http://mailhide.recaptcha.net/apikey "MailHide keys")

== Requirements ==

* You need the reCAPTCHA keys [here](http://recaptcha.net/api/getkey?domain=www.blaenkdenum.com&app=wordpress "reCAPTCHA API keys") and/or the MailHide keys [here](http://mailhide.recaptcha.net/apikey "MailHide keys")
* If you plan on using MailHide, you will need to have the [mcrypt](http://php.net/mcrypt "mcrypt") PHP module loaded (*Most servers do*)
* If you turn on XHTML 1.0 Compliance you and your users will need to have Javascript enabled to see and complete the reCAPTCHA form
* Your theme must have a `do_action('comment_form', $post->ID);` call right before the end of your form (*Right before the closing form tag*). Most themes do.

== ChangeLog ==

= Version 3.1.6 =
* WordPress MS fixes. Should now work out of the box at the individual blog level. Thanks to [huyz](http://huyz.us/)
* NOTICE: If anyone is interested in taking up development of this plugin, please contact me at blaenk@gmail.com.
= Version 3.1.5 =
* Thanks to [Ken Newman](https://github.com/WraithKenny) for these changes
* Update author website
* Stop generating javascript errors on unnecessary pages
* Better SSL support
= Version 3.1.4 =
* Fixed an XSS vulnerability
= Version 3.1.3 =
* Added a collision aversion prefix to the Plugin class. bbouton from github alerted me to a collision between WP-reCAPTCHA's plugin class and the JW Player Plugin's Plugin class.
= Version 3.1.2 =
* Fixed option migration. The plugin was actually written to be made to import the old options, but the hook that functionality was registered to does not fire when the wordpress interface updates a plugin, only when a plugin is updated manually. Now the plugin will import or register default options as long as the options don't already exist.
* Fixed a case where recaptcha theme would not change. This happened because of the above problem, creating a situation in which the tab index field could be empty, and being empty this then caused a problem with the recaptcha options when they were output to the page. If you're running version 3.0 of the plugin, go ahead and add a number to the tab index (e.g. 5 for comments, 30 for registration), if not, this plugin should automatically fix it next time you change save the settings.
* Modified the options page submit buttons to more explicitly show that they are specific to their own respective sections, that is, one button ONLY saves the changes for one reCAPTCHA, and the other ONLY saves the changes for MailHide.
= Version 3.0 =
* Rewrote the entire plugin in an object-oriented manner with separation of concerns in mind to increase maintainability and extensibility
* Implemented the ability to import the options from the option set of older versions of the plugin, such as 2.9.8.2 and less
* Redesigned the options page for the plugin, now using newer wordpress options/form functionality with support for input-validation
* Options for recaptcha and mailhide are independent of each other
* Added support for localization, using gettext
* Fixed the issue where comments would not be saved if the reCAPTCHA was entered incorrectly (or not entered at all). requires javascript
* Fixed an issue where saved comments (from bad reCAPTCHA response) would replace double quotes with backslashes
* Fixed an issue in wordpress 3 and above in which mailhide would not work due to interference with a new filter, make_clickable, which auto-links emails and URLs
* Fixed a role-check issue in wordpress 3 and above. level_10 (and all levels for that matter) have been deprecated. now using activate_plugins instead.
= Version 2.9.8.2 =
* Fixed a bug with WordPress 3.0 Multi-Site
= Version 2.9.8 =
* Added support for WordPress 3.0 Multi-Site thanks to Tom Lynch
= Version 2.9.7 =
* Fixed a relatively new [critical bug](http://www.blaenkdenum.com/2010/03/recaptcha-marking-all-comments-as-spam/) which marked new comments as spam regardless of reCAPTCHA response
= Version 2.9.6 =
* Fixed a careless bug affecting custom hidden emails
* Fixed broken links in readme.txt
= Version 2.9.5 =
* Added flexibility to the enabling of MailHide. Can now separately choose to enable/disable MailHide for posts/pages, comments, RSS feed of posts/pages, and RSS feed of comments
* Fixed an ['endless redirection' bug](http://wordpress.org/support/topic/245154?replies=1 "endless redirection in wp-reCAPTCHA options form") thanks to Edilton Siqueira
* Fixed a bug in WPMU where wp-admin/user-new.php kept trying to validate the user registration with reCAPTCHA information despite not having shown the reCAPTCHA form, thanks to [Daniel Collis-Puro](http://blogs.law.harvard.edu/ "Weblogs at Harvard Law School") for letting me know
* Added a line break after the reCAPTCHA form to add some padding space between it and the submit button. Due to [popular demand](http://www.chriscredendino.com/2009/03/08/adding-space-between-recaptcha-and-the-comment-submit-button-on-wordpress/ "Adding space between reCAPTCHA and the comment Submit Button on WordPress")
* Fixed a validation problem where a style attribute was missing. Thanks to [nv1962](http://wordpress.org/support/profile/304093 "nv1962's profile")
* Public and Private keys are now trimmed since they are usually pasted from the recaptcha site, to avoid any careless errors
* Fixed the regular expressions for matching the emails, email@provider.co.uk type emails now work
= Version 2.9.4 =
* Fixed a bug where the comment would not be saved if the CAPTCHA wasn't entered correctly. Thanks to Justin Heideman.
= Version 2.9.3 =
* Fixed the `recaptcha_wp_saved_comment` function. Thanks to Tomi M.
= Version 2.9.2 =
* 'Beautified' the options page.
* Added two options to allow users to enter their own custom error messages. Also good for foreign language support.
* Fixed a conflict bug with the OpenID plugin where the reCAPTCHA form would show under the OpenID section in the registration form.
* Added two new options which allow one to choose the text to be shown for all hidden Emails and/or the title of the link.
* Fixed a 'Could not open socket' error in recaptchalib.php. [Bug ID 26](http://code.google.com/p/recaptcha/issues/detail?id=26 "recaptchalib.php: Could not open socket (Fix included)")
* Fixed a WPMU issue where blog registrations weren't possible due to a redirection to the first step in the registration process. Thanks to [Edward](http://yisheng.wordpress.com/2008/08/14/wp-recaptcha-for-wpmu-26/ "Edward").
= Version 2.9.1 =
* Forgot that if you can see emails in their true form, then you shouldn't have to see the [nohide][/nohide] tags either. Fixed.
= Version 2.8.6 =
* Administration interface is now integrated with 2.5's look and feel. Thanks to [Jeremy Clarke](http://simianuprising.com/ "Jeremy Clarke").
* Users can now have more control over who sees the reCAPTCHA form and who can see emails in their true form (If MailHide is enabled). Thanks to [Jeremy Clarke](http://simianuprising.com/ "Jeremy Clarke").
* Fixed a very stupid (**One character deal**) fatal error on most Windows Servers which don't support short tags (short_open_tag). I'm speaking of the so called 'Unexpected $end' error.
* Accommodated for the fact that in +2.6 the wp-content folder can be anywhere.

== Frequently Asked Questions ==

= HELP, I'm still getting spam! =
There are four common issues that make reCAPTCHA appear to be broken:

1. **Moderation Emails**: reCAPTCHA marks comments as spam, so even though the comments don't actually get posted, you will be notified of what is supposedly new spam. It is recommended to turn off moderation emails with reCAPTCHA.
1. **Akismet Spam Queue**: Again, because reCAPTCHA marks comments with a wrongly entered CAPTCHA as spam, they are added to the spam queue. These comments however weren't posted to the blog so reCAPTCHA is still doing it's job. It is recommended to either ignore the Spam Queue and clear it regularly or disable Akismet completely. reCAPTCHA takes care of all of the spam created by bots, which is the usual type of spam. The only other type of spam that would get through is human spam, where humans are hired to manually solve CAPTCHAs. If you still get spam while only having reCAPTCHA enabled, you could be a victim of the latter practice. If this is the case, then turning on Akismet will most likely solve your problem. Again, just because it shows up in the Spam Queue does NOT mean that spam is being posted to your blog, it's more of a 'comments that have been caught as spam by reCAPTCHA' queue.
1. **Trackbacks and Pingbacks**: reCAPTCHA can't do anything about pingbacks and trackbacks. You can disable pingbacks and trackbacks in Options > Discussion > Allow notifications from other Weblogs (Pingbacks and trackbacks).
1. **Human Spammers**: Believe it or not, there are people who are paid (or maybe slave labor?) to solve CAPTCHAs all over the internet and spam. This is the last and rarest reason for which it might appear that reCAPTCHA is not working, but it does happen. On this plugin's [page](http://www.blaenkdenum.com/wp-recaptcha/ "WP-reCAPTCHA - Blaenk Denum"), these people sometimes attempt to post spam to try and make it seem as if reCAPTCHA is not working. A combination of reCAPTCHA and Akismet might help to solve this problem, and if spam still gets through for this reason, it would be very minimal and easy to manually take care of.

= Why am I getting Warning: pack() [function.pack]: Type H: illegal hex digit?
You have the keys in the wrong place. Remember, the reCAPTCHA keys are different from the MailHide keys. And the Public keys are different from the Private keys as well. You can't mix them around. Go through your keys and make sure you have them each in the correct box.

= Aren't you increasing the time users spend solving CAPTCHAs by requiring them to type two words instead of one? =
Actually, no. Most CAPTCHAs on the Web ask users to enter strings of random characters, which are slower to type than English words. reCAPTCHA requires no more time to solve than most other CAPTCHAs.

= Are reCAPTCHAs less secure than other CAPTCHAs that use random characters instead of words? =
Because we ask users to enter two words instead of one, we can increase the security of reCAPTCHA against programs that attempt to guess the words using a dictionary. Whenever an IP address fails one reCAPTCHA, we can show them more distorted words, and give them challenges for which we know both words. The probability of randomly guessing both words correctly would be less than one in ten million.

= Are CAPTCHAs secure? I heard spammers are using porn sites to solve them: the CAPTCHAs are sent to a porn site, and the porn site users are asked to solve the CAPTCHA before being able to see a pornographic image. =

CAPTCHAs offer great protection against abuse from automated programs. While it might be the case that some spammers have started using porn sites to attack CAPTCHAs (although there is no recorded evidence of this), the amount of damage this can inflict is tiny (so tiny that we haven't even seen this happen!). Whereas it is trivial to write a bot that abuses an unprotected site millions of times a day, redirecting CAPTCHAs to be solved by humans viewing pornography would only allow spammers to abuse systems a few thousand times per day. The economics of this attack just don't add up: every time a porn site shows a CAPTCHA before a porn image, they risk losing a customer to another site that doesn't do this.

== Screenshots ==

1. The reCAPTCHA Settings
2. The MailHide Settings
