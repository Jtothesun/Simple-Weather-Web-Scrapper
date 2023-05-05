# Simple-Weather-Web-Scrapper
A small weather web scrapping app that refreshes weather info automatically every 6 hours for the 7 major cities of: 
  New York, NY
  East L.A, CA
  Chicago, IL
  Houston, TX
  Phoenix, AZ
  Philadelphia, PA
  Jacksonville, FL
  
Created using the LAMP stack (Linux, Apache, MySQL, PHP) , Bash, Python, and TagSoup

To Run:
1) Download ALL files - the parser.py file, sources.txt, weather.sh shell script, my_data.sql, my_tables.sql, tagsoup-1.21.jar and all .php files.
2) Download Apache
3) Place all the downloaded .php files into your directory: /var/www/html
(you can do this by running command: cd /var/www/html)
4) From Linux terminal, run the weather.sh shell script (i.e: ./weather.sh)
5) Visit localhost/NY.php (or any other .php file of choice)
6) You can select a preferred city to show up as default every time you visit ANY localhost link regardless of .php file in the future
7) ENJOY!
