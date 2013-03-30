 - Put all the files in this archive directly in the root folder of your web server
    htdocs/
    or
    www/
    
 - Enable the mod_rewrite in your httpd.conf
    LoadModule rewrite_module modules/mod_rewrite.so  (uncomment)
    
 - Open your hosts file in Administrator mode
    C:\Windows\System32\drivers\etc\hosts  (on Windows)
    or
    /etc/hosts  (on Linux)
    
 - Add a website address in your hosts file
    127.0.0.1 subdomainofyourchoice.domainofyourchoice.tldofyourchoice
    
 - Open your web navigator & go at the chosen adress
 
 - Enjoy