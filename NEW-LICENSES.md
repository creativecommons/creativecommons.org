# How to add test new licenses on creativecommons.org
Updated August 2014

There are two steps to previewing a license on the website.

* Making a branch of the creativecommons.org website.

* Pointing a testing server at your branch by editing the appropriate
file on the 'server-config' repository and waiting a few minutes.

Let's get started.

In our example, we're working on the German translation of CC4.0

You can do most of this via the GitHub website.

## Branch the website

First, make a branch of the website by clicking the 'branch: master'
dropdown at <https://github.com/creativecommons/creativecommons.org>

Type in the name of your new branch, ie: cc4-de-legalcode

Now in your local copy of the code, checkout the branch:

    git checkout cc4-de-legalcode

Make your changes, edits, new files, etc. To add everything, type:

    git add .

And then:

    git commit

Review the commit carefully, check all the files you wanted are going
up. If they are and everything looks good, write a little message to
explain what's going on. Keep it short and reasonably on-point. Extra
points for in-jokes, memes, etc.

Now head back to
<https://github.com/creativecommons/creativecommons.org> and you
should see your updates. 

## Now, point a testing server at your code.

For this, go to <https://github.com/creativecommons/server-config> and
click on a test server -- testing5, for example.

Click the pencil icon at the top right. Change the owner to your name,
and the branch to the name of your branch.

Hit Commit changes, go make coffee and when you come back it should be
live on the server you chose. Just visit
<http://testing5.creativecommons.org> and you'll see it.
