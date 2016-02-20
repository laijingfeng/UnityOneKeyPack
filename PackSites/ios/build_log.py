# -*- coding: utf-8 -*-

from build_config import *

import sys, os, datetime

def usage():
    print '------build_log.py help------'
    print 'need 1 params'
    print '    the msg'
    print '------build_log.py help------'

if __name__ == '__main__':
	if len(sys.argv) != 2:
		usage()
		exit()

	HOME_PATH = os.path.dirname(os.path.abspath(sys.argv[0]))
	print_path = HOME_PATH + LOG_FILE_PATH
	msg = sys.argv[1]
	str_time = datetime.datetime.now().strftime('%Y-%m-%d %H:%M:%S')

	if msg == 'CLEAN_LOG':
		with open(print_path, 'w') as f:
			f.write('')
	else:
		with open(print_path, 'a') as f:
			f.write(str_time + ' ' + msg + '\n')


