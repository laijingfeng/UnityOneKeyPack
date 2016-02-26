# -*- coding: utf-8 -*-

import os, sys, subprocess, math

HOME_PATH = ''

# argv_begin
par_upload_family = 'F'
par_upload_ftp = 'F'
par_send_mail = 'F'
par_do_clean = 'F'
# argv_end

def execute_shell_command(args, is_wait = 'T'):
    p = subprocess.Popen(args)
    if is_wait == 'T':
        ret = p.wait()
        return ret
    else:
        return 0

def add_log(msg, log_file = None):
    if log_file is None:
        log_file = HOME_PATH + '/ios/build_log.py'
    
    args = ['python', log_file, msg]
    execute_shell_command(args, 'F')

if __name__ == '__main__':
	if len(sys.argv) != 2:
		print 'argv error'
		exit()

	HOME_PATH = os.path.dirname(os.path.abspath(sys.argv[0]))
	pack_par = sys.argv[1];

	add_log('pack_argv=' + pack_par);

	pack_par_arr = pack_par.split("#")

	for i in range(len(pack_par_arr)):
		one = pack_par_arr[i].split('-')
		if one[0] == '_jy_par_upload_family':
			par_upload_family = one[1]
		elif one[0] == '_jy_par_upload_ftp':
			par_upload_ftp = one[1]
		elif one[0] == '_jy_par_send_mail':
			par_send_mail = one[1]
		elif one[0] == '_jy_par_do_clean':
			par_do_clean = one[1]

	args = ['python', HOME_PATH + '/ios/run.py', par_upload_family, par_upload_ftp, par_send_mail, par_do_clean]
	execute_shell_command(args, 'F')



