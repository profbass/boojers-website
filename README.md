Boojers.com Repo

Setup git repo on the live server...
$ git --bare init
$ git init
$ git remote add origin git://github.com/ActiveWebsite/boojers-website.git
$ git pull origin master


To update this repo SSH onto the live server and run $ git pull origin master.