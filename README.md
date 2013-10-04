Kakalika Issue Tracker
======================

![Screenshot](http://ekowabaka.me/kakalika/screen.png)

The goal behind kakalika is to provide an issue managment tool that is easy to setup, easy to
administer and easy to use. It is meant to be that kind of tool that anyone can
figure out in just a few clicks. 

Although kakalika is currently in its infancy and is obviously quite far from 
what it is actually supposed to be, it still packs a little punch. For now you
can: 
- Log your issues (that's why its an issue tracker)
- Give your issues statuses with our simple built in workflow
- Categorize all your issues with our built in categories
- Comment on issues and keep track of all the history of comments

Installing Kakalika
-------------------

### Requirements

- Apache web server
- PHP 5.3+
- MySQL server 5+
- mysqli extension for PHP
- `mod_rewrite` and `.htaccess` enabled

### Setting up

1. Download and extract release to your document root. If you however clone the git repository 
   instead of installing then initialize and update all submodules before proceeding 
   with the installation.
2. Ensure that the following directories exist and are writable by the web server:
   - `cache`
   - `public/js`
   - `public/css`
   - `public/images`
   - `config`
3. Create a blank database on your mysql server to hold the data

4. Point browser to `/install` and follow the steps on the browser. 
   You can also execute `install/run` on the command line. If you choose
   the command line option ensure you run as the web server's user so that
   permissions are properly checked.

After installation completes successfuly:

1. Delete the `install` directory or make it inaccesible to the web server
2. Protect the `config` directory and make it read only
3. Point your browser to the document root and enjoy Kakalika


Contributing
------------
If you wish to help on this project feel free to fork the code and
send in pull requests.





