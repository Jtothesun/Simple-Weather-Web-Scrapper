import time
import mysql.connector
import sys
from xml.dom import minidom

db = mysql.connector.connect(
host="localhost",
user="root",
password="Jimmy#1234",
database="testing"
)

cursor = db.cursor()

now = sys.argv[1]
with open(f'{now}', 'r') as file:
	xhtml = file.read()


#we parse the document
dom = minidom.parseString(xhtml)

#searching div tags - getting state name + city name
next_divs = dom.getElementsByTagName('div')
for div in next_divs:
	if div.getAttribute('class') == ('panel panel-default') and div.getAttribute('id') == ('seven-day-forecast'):
		#print(f'Found div with class="next": {div.toxml()}')
		heading = div.getElementsByTagName('h2')
		for h in heading:
			h_txt = h.firstChild.nodeValue.strip()
			#save state name + city name
			state_city = h_txt
#print(state_city)

#get the date (Last Update) which is last element in tds tags
#tds = dom.getElementsByTagName('td')
#for td in tds:
#	last_updated = td.firstChild.toxml().strip()
#print(last_updated)

#get the CURRENT temperature (in farenheit and celcius)
#for div in next_divs:
#	if div.getAttribute('id') == 'current_conditions-summary':
#		p_f = div.getElementsByTagName('p')[1]
#		p_txt_f = p_f.firstChild.nodeValue.strip()
#		farenheit_now = p_txt_f

#		p_c = div.getElementsByTagName('p')[2]
#		p_txt_c = p_c.firstChild.nodeValue.strip()
#		celcius_now = p_txt_c

#get days and nights for 7-day forecast (must be from the current moment onward)
days = []
lis = dom.getElementsByTagName('li')
for li in lis:
	if li.getAttribute('class') == 'forecast-tombstone':
		periods = li.getElementsByTagName('p')
		for p in periods:
			if p.getAttribute('class') == ('period-name') and '<br' in p.toxml():
				cur_day = ''.join(node.nodeValue.strip() + ' ' for node in p.childNodes if node.nodeType == minidom.Node.TEXT_NODE)
				days += [cur_day]
			elif p.getAttribute('class') == ('period-name'):
				current_day = p.firstChild.nodeValue
				str_current = str(current_day)
				if str_current not in days:
				  days += [current_day]
				else:
				  days += [current_day + ' Night']

#print(days)

#get temperatures for days and nights (NOTE: Alternating lows and highs listed)
#also gets short descriptions
#store in list
shorts = []
temperatures = []
for li in lis:
	if li.getAttribute('class') == 'forecast-tombstone':
		temps =  li.getElementsByTagName('p')
		short_desc = li.getElementsByTagName('p')
		for t in temps:
			if t.getAttribute('class') in ('temp temp-low', 'temp temp-high'):
				current_temp = t.firstChild.nodeValue
				#str_temp = str(current_temp)
				temperatures += [current_temp]
		for s in short_desc:
			if s.getAttribute('class') == ('short-desc') and '<br' in s.toxml():
				text = ''.join(node.nodeValue.strip() + ' ' for node in s.childNodes if node.nodeType == minidom.Node.TEXT_NODE)
				shorts += [text]
			elif s.getAttribute('class') == ('short-desc'):
				current_s = s.firstChild.nodeValue
				shorts += [current_s]
#print(temperatures)
#print(shorts)


#get long descriptions - store in list
longs = []
for div in next_divs:
	if div.getAttribute('class') == ('col-sm-10 forecast-text'):
		long_desc = div.firstChild.nodeValue
		longs += [long_desc]
#print(longs)

#checks to see the number of rows that we have in our database
cursor.execute("SELECT COUNT(*) FROM weather_data")
result = cursor.fetchone()
num_rows = result[0]

if num_rows == 63:
    # Switch to updating the data
    for i in range(9):
        sql = "UPDATE weather_data SET temperatures = %s, short_descriptions = %s, long_descriptions = %s WHERE state_city = %s AND days = %s"
        val = (temperatures[i], shorts[i], longs[i], state_city, days[i])
        cursor.execute(sql, val)
    db.commit()
    print("Data has been updated in the database.")
else:
    # Insert new data into the table
    for i in range(9):
        sql = "INSERT INTO weather_data(state_city, temperatures, days, short_descriptions, long_descriptions)VALUES(%s,%s,%s,%s,%s)"
        val = (state_city, temperatures[i], days[i], shorts[i], longs[i] )
        cursor.execute(sql,val)
    db.commit()
    print("Data has been inserted into the database.")
