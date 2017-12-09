# -*- coding: utf-8 -*-
"""
Created on Wed Dec 06 11:02:36 2017

@author: sofiahuang
"""

import datetime
import time
import MySQLdb
from email import encoders
from email.header import Header
from email.mime.text import MIMEText
from email.utils import parseaddr, formataddr
import smtplib

#address format definition
def _format_addr(s):
    name, addr = parseaddr(s)
    return formataddr(( \
        Header(name, 'utf-8').encode(), \
        addr.encode('utf-8') if isinstance(addr, unicode) else addr))

flag = 0;
schedtime = datetime.datetime.now()
while True:
    now = datetime.datetime.now()  
    t=3
    if schedtime < now < schedtime + datetime.timedelta(seconds = 1): 
        #execute task
        #connect to database and set cursor
        db = MySQLdb.connect("localhost", "group3", "group3", "group3")
        cursor = db.cursor()
        
        #select from selling table all gid whose date is now - 7days
        sql = "SELECT uid \
        FROM selling\
        WHERE now() > date_add(begindate, interval 6 day) \
        and now() < date_add(begindate, interval 8 day)"
        cursor.execute(sql)
        result = cursor.fetchall()
        
        #close database
        db.close()
        
        #send mails
        for user in result:
            to_addr = user
            
            #mail details
            from_addr = 'feifeiilei@163.com'
            password = 'helloworldgroup3'
            smtp_server = 'smtp.163.com'
            to_addr = '1400012739@pku.edu.cn'
            msg = MIMEText('您好，您在二货网上卖出的商品还未确认，请尽快登录您的账号进行交易确认。\n谢谢！', \
               'plain', 'utf-8')
            msg['From'] = _format_addr(u'二货 <%s>' % from_addr)
            msg['Subject'] = Header(u'【通知】请尽快确认交易', 'utf-8').encode()
            msg['To'] = _format_addr(u'<%s>' % to_addr)
            
            #set mail server
            server = smtplib.SMTP(smtp_server, 25)
            server.set_debuglevel(1)
            server.login(from_addr, password)
            server.sendmail(from_addr, [to_addr], msg.as_string())
            server.quit()

        print("hello my friend!");
        time.sleep(1);
        flag = 1;
    elif flag:
        schedtime = schedtime + datetime.timedelta(days = 1) #days = 1
        flag = 0;
        

        