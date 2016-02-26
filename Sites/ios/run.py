# -*- coding: utf-8 -*-

# key functions use parameters for easy to be used alone 

from build_config import *
import os, sys, datetime, subprocess, shutil

PACK_NAME = 'test'
HOME_PATH = ''

# argv_begin
par_upload_family = 'T'
par_upload_ftp = 'T'
par_send_mail = 'F'
par_do_clean = 'F'
# argv_end

def usage():
    print '------build_ios.py help------'
    print 'need 4 params'
    print '    wether upload family: T or F'
    print '    wether upload ftp: T or F'
    print '    wether pack info send_mail: T or F'
    print '    wether clean project after pack: T or F'
    print '------build_ios.py help------'

def build_init(home_path):
    global HOME_PATH
    HOME_PATH = os.path.dirname(os.path.abspath(home_path))
    global PACK_NAME
    PACK_NAME = datetime.datetime.now().strftime('%Y.%m.%d.%H.%M')
    
    add_log('CLEAN_LOG')
    
    definite_file_clear()

def add_log(msg, log_file = None):
    if log_file is None:
        log_file = HOME_PATH + LOG_PATH
    
    args = ['python', log_file, msg]
    execute_shell_command(args, 'F')

def upload(is_send_mail = 'T'):
    if par_upload_family == 'T':
        upload_file('T', PROJECT_PATH + '/' + PACK_NAME + '/' + 'hotblood_ios_test_' + PACK_NAME + '.ipa', is_send_mail)
        upload_file('T', HOME_PATH + '/' + 'hotblood_ios_test_' + PACK_NAME + '.plist', is_send_mail)
        upload_file('T', HOME_PATH + '/' + 'qr.png', is_send_mail)

    if par_upload_ftp == 'T':
        upload_file('F', PROJECT_PATH + '/' + PACK_NAME + '/' + 'hotblood_ios_test_' + PACK_NAME + '.ipa', is_send_mail)
        upload_file('F', HOME_PATH + '/' + 'hotblood_ios_test_' + PACK_NAME + '.plist', is_send_mail)
        upload_file('F', HOME_PATH + '/' + 'qr.png', is_send_mail)

def upload_file(is_family, file_name, is_wait = 'T', upload_tool_path = None):
    if upload_tool_path is None:
        upload_tool_path = HOME_PATH + UPLOAD_TOOL

    args = [upload_tool_path, is_family, file_name]
    execute_shell_command(args, is_wait)

def execute_shell_command(args, is_wait = 'T'):
    p = subprocess.Popen(args)
    if is_wait == 'T':
        ret = p.wait()
        return ret
    else:
        return 0

def build_unity2xcode(unity_path = UNITY_PATH, project_path = PROJECT_PATH, execute_method = 'AutoBuild.BuildIOS', method_parameter = None):
    
    if method_parameter is None:
        method_parameter = 'build_out_path-' + PACK_NAME

    add_log('build_unity2xcode start')

    args = [unity_path, '-projectPath', project_path, '-executeMethod', execute_method, method_parameter, '-quit', '-batchmode']
    ret = execute_shell_command(args)
    if ret != 0:
        add_log('build_unity2xcode fail')
        build_finish('F')
        exit()
    
    add_log('build_unity2xcode finish')

def definite_file_clear():
    args = ['rm', HOME_PATH + '/*.png']
    execute_shell_command(args, 'F')

    args = ['rm', HOME_PATH + '/*.plist']
    execute_shell_command(args, 'F')

def build_finish(success):
    
    subject = ''
    content = ''

    if success == 'T':
        add_log('finish success')
        subject = MAIL_SUBJECT_SUCCESS 
        content = MAIL_CONTENT_SUCCESS
    else:
        add_log('finish fail')
        subject = MAIL_SUBJECT_FAIL 
        content = MAIL_CONTENT_FAIL

    if par_send_mail == 'T':
        name_list = MAIL_TO_NAME_LIST
        send_mail(name_list, subject, content)

    if par_do_clean == 'T':
        xcode_path = PROJECT_PATH + '/' + PACK_NAME
        if os.path.exists(xcode_path) and os.path.isdir(xcode_path):
            shutil.rmtree(xcode_path)
        definite_file_clear()

    with open(HOME_PATH + RES_FILE_PATH, 'w') as f:
            f.write(success + '=' + datetime.datetime.now().strftime('%Y-%m-%d %H:%M:%S'))

def generate_qrcode(qr_script_path = None, pack_name = None):
    if qr_script_path is None:
        qr_script_path = HOME_PATH + QR_TOOL
    if pack_name is None:
        pack_name = PACK_NAME

    args = ['python', qr_script_path, pack_name]
    execute_shell_command(args)

def send_mail(name_list, subject, content):
    args = ['python', HOME_PATH + MAIL_TOOL, name_list, subject, content]
    execute_shell_command(args)

def generate_plist(plist_temp_file = None, build_version = BUILD_VERSION, bundle_id = BUNDLE_ID, pack_name = None, save_file_name = None):
    if plist_temp_file is None:
        plist_temp_file = HOME_PATH + PLIST_TMP
    if pack_name is None:
        pack_name = PACK_NAME
    if save_file_name is None:
        save_file_name = HOME_PATH + '/' + 'hotblood_ios_test_' + PACK_NAME + '.plist'

    data = ''
    with open(plist_temp_file, 'r') as f:
            data = f.read()

    data = data.replace('{VERSION}', build_version)
    data = data.replace('{BUNDLE_ID}', bundle_id)
    data = data.replace('{PACK_NAME}', pack_name)

    with open(save_file_name, 'w') as f:
            f.write(data)
     
def build_xcode2ipa(xcode_project_path = None, code_sign_identity_name = CODE_SIGN_IDENTITY_NAME, provisioning_profile_name = PROVISIONING_PROFILE_NAME, export_ipa_name = None):
    if xcode_project_path is None:
        xcode_project_path = PROJECT_PATH + '/' + PACK_NAME + '/'
    if export_ipa_name is None:
        export_ipa_name = 'hotblood_ios_test_' + PACK_NAME

    add_log('build_xcode2ipa start')

    # 'CODE_SIGN_IDENTITY=' + code_sign_identity_name

    args = ['xcodebuild', 'clean', '-project', xcode_project_path + 'Unity-iPhone.xcodeproj', '-configuration', 'Release', '-alltargets']
    ret = execute_shell_command(args)
    if ret != 0:
        add_log('xcodebuild clean fail')
        build_finish('F')
        exit()

    args = ['xcodebuild', 'archive', '-project', xcode_project_path + 'Unity-iPhone.xcodeproj', '-scheme', 'Unity-iPhone', '-archivePath', xcode_project_path + 'Unity-iPhone.xcarchive']
    ret = execute_shell_command(args)
    if ret != 0:
        add_log('xcodebuild archive fail')
        build_finish('F')
        exit()
        
    args = ['xcodebuild', '-exportArchive', '-archivePath', xcode_project_path + 'Unity-iPhone.xcarchive', '-exportPath', xcode_project_path + export_ipa_name + '.ipa', '-exportFormat', 'ipa', '-exportProvisioningProfile', provisioning_profile_name]
    ret = execute_shell_command(args)
    if ret != 0:
        add_log('xcodebuild export fail')
        build_finish('F')
        exit()    

    add_log('build_xcode2ipa finish')

def analysis_par(par_arr):
    if len(par_arr) > 1:
        global par_upload_family
        par_upload_family = par_arr[1]

    if len(par_arr) > 2:
        global par_upload_ftp
        par_upload_ftp = par_arr[2]

    if len(par_arr) > 3:
        global par_send_mail
        par_send_mail = par_arr[3]

    if len(par_arr) > 4:
        global par_do_clean
        par_do_clean = par_arr[4]

if __name__ == '__main__':

    # argv_begin
    par_home_path = sys.argv[0]
    analysis_par(sys.argv)
    # argv_end

    build_init(sys.argv[0])

    build_unity2xcode()
    build_xcode2ipa()
    
    generate_plist()
    generate_qrcode()

    upload(par_send_mail)

    build_finish('T')
