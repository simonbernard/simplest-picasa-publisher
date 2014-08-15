simplest-picasa-publisher
=========================

This repo hosts a very simple script to publish Picasa HTML exports both - public or password protected

What does simple mean? Here are the steps to publish your HTML export:
* First export the pictures you’d like to publish
* Replace all spaces in your generated folder name by underscores
* Upload your export to folder “static”
* If your gallery should be available to public, you are done, if not continue with step 5
* Open admin console, go to Users tab and create a new user
* Go to Galleries tab and mark your gallery as private
* Go to Permissions tab and select which users shall have access to your gallery
* Open Url to your webserver, your new gallery should be listed here.

So this is quite simple. Of course you have to set up the whole thing first and these are the required steps:
* Upload scripts to your webserver
* Update the path to your .htpasswd file in file admin/.htaccess
* Add a user to the admin/.htpasswd file
* Optional: change the configuration in file admin/config.ini
* That’s it.
