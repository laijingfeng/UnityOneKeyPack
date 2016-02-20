#! /usr/bin/python
# -*- coding: utf-8 -*-

#-------------------------------------------------------------------------------
# Module Name: send_mail
# 
# Purpose: 
# 
# Author: 张杰(JerryZhang)
# Date: 2014/05/28
#-------------------------------------------------------------------------------


import os, sys
import time
import datetime
import smtplib

from email.mime.multipart import MIMEMultipart
from email.utils import formatdate
from email.mime.base import MIMEBase
from email.mime.text import MIMEText
from email.mime.image import MIMEImage
from email import encoders

class Mailer:
    """ A mail setter
    """
    
    subject = ''
    content = ''
    fro = ''
    to_list = []
    attach_files = []
    images = []
    server = {}
    is_html = True

    def __init__(self, subject, fro, to_list, content, \
                 attach_files=[], images=[], is_html = True):
        self.subject = subject
        self.fro = fro
        self.content = content
        self.to_list = to_list
        self.attach_files = attach_files
        self.images = images
        self.is_html = is_html
        
    def set_server_info(self, server_name, server_user, server_password):
        self.server['name'] = server_name
        self.server['user'] = server_user
        self.server['passwd'] = server_password
            
    def send_mail(self):
        msg3 = MIMEMultipart()
        msg3['From'] = self.fro
        msg3['Subject'] = self.subject
        msg3['To'] = ";".join(self.to_list)
        msg3['Date'] = formatdate(localtime=True)
            
        for file in self.attach_files:
            part = MIMEBase('application', 'octet-stream')
            part.set_payload(open(file, 'rb').read())
            encoders.encode_base64(part)
            part.add_header('Content-Disposition', 'attachment; filename="%s"' \
                            % os.path.basename(file))
            msg3.attach(part)
                
        i = 0;
        text = self.content
        for image in self.images:
            text += '<br><img src="cid:image%d">'%i
                
            part = MIMEImage(open(image, 'rb').read())
            part.add_header('Content-ID', '<image%d>'%i)
            part.add_header('Content-Disposition', 'attachment; filename="%s"' \
                            % os.path.basename(image))

            msg3.attach(part)
            i += 1
                
        if self.is_html:
            msg3.attach(MIMEText(text, 'html', 'utf-8'))
        else:
            msg3.attach(MIMEText(text))

        smtp = smtplib.SMTP(self.server['name'])
        smtp.login(self.server['user'], self.server['passwd'])
        msg_content = msg3.as_string()
        smtp.sendmail(self.fro, self.to_list, msg_content)
        smtp.close()

if __name__ == '__main__':
    if len(sys.argv) != 4:
        exit()

    par_name_list = sys.argv[1].split('#')
    par_subject = sys.argv[2]
    par_content = sys.argv[3]

    mailer = Mailer(par_subject, 'sender_mail', \
                    par_name_list, '<h1>' + par_content + '<h1>')
    mailer.set_server_info('smtp.exmail.qq.com', 'send_mail', 'password')
    mailer.send_mail()
                                        
