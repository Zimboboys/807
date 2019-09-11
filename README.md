# 807
A web app for Mustang Band that will hopefully help make stations, attendance, and other bookkeeping a lot easier for everyone

## Set up
1. clone this git repository into the directory you want it to be assessible from
2. Log into your MySQL server and run the following command:    source 807_app.sql;
3. Visit the home directory from the web browser to ensure everything looks similar to the sandbox site
    1. http://807.band/mband.calpoly.edu/htdocs/members/stations/
    2. use the same username from the band server, with password 'testing'
        1. if styles do not load on your server, check out 'header.php', where $homeDir is specified. Modify the substring starting # until working
        2. text me if this is not working. I will take a look.
4. Go to the following URL to generate user info from the .htgroup file: [URL]/stations/db/scripts/generate_user_table.php
    1. run this just once.
5. I think that should be enough to get it running


## Notes
This is very much in development. Most user and section leader portions should be working, but director and DM sided views may be buggy (lot more scripts to write). Please message me and I'll fix these as soon as possible (or tell you how to work around it until it can be fixed).
