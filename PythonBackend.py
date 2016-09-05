import sqlite3
import urllib2

conn = sqlite3.connect('VACUpdate.sqlite')
c = conn.cursor()

def vacthingy( input ):
   url = 'http://steamcommunity.com/profiles/'+str(input)+'?xml=1'
   webFile = urllib2.urlopen(url).read()
   if "<vacBanned>0</vacBanned>" not in webFile: 
           c.execute("UPDATE trackedusers SET banned = 1 WHERE steamid=?",str(input))
           print str(input) + " is banned";
   return

for row in c.execute('SELECT * FROM trackedusers WHERE banned = 0'):
        vacthingy(row[0])

conn.commit()
conn.close()
