# -*- coding: utf-8 -*-

from build_config import *
import os, sys, datetime, subprocess, shutil

PACK_NAME = ''
HOME_PATH = ''

par_upload_family = ''
par_upload_ftp = ''

def usage():
    print '------build_ios.py help------'
    print 'need 2 params'
    print '    wether upload family: T or F'
    print '    wether upload ftp: T or F'
    print '------build_ios.py help------'

def add_log(msg):
    args = ['python', HOME_PATH + LOG_PATH, msg]
    execute_shell_command(args, 'F')

def upload():
    if par_upload_family == 'T':
        upload_file('T', PROJECT_PATH + '/' + PACK_NAME + '/' + 'hotblood_ios_test_' + PACK_NAME + '.ipa')
        upload_file('T', HOME_PATH + '/' + 'hotblood_ios_test_' + PACK_NAME + '.plist')
        upload_file('T', HOME_PATH + '/' + 'qr.png')

    if par_upload_ftp == 'T':
        upload_file('F', PROJECT_PATH + '/' + PACK_NAME + '/' + 'hotblood_ios_test_' + PACK_NAME + '.ipa')
        upload_file('F', HOME_PATH + '/' + 'hotblood_ios_test_' + PACK_NAME + '.plist')
        upload_file('F', HOME_PATH + '/' + 'qr.png')

def upload_file(family, file_name):
    upload_des = 'family'
    if family != 'family':
        upload_des = 'fileServer'
    args = [HOME_PATH + UPLOAD_TOOL, upload_des, file_name]
    execute_shell_command(args, 'F')

def execute_shell_command(args, wait='T'):
    p = subprocess.Popen(args)
    if wait == 'T':
        ret = p.wait()
        return ret
    else:
        return 0

def build_unity2xcode():
    add_log('build_unity2xcode start')

    args = [UNITY_PATH, '-projectPath', PROJECT_PATH, '-executeMethod', 'AutoBuild.BuildIOS', 'build_out_path-' + PACK_NAME, '-quit', '-batchmode']
    ret = execute_shell_command(args)
    if ret != 0:
        add_log('build_unity2xcode fail')
        build_finish('F')
        exit()
    
    add_log('build_unity2xcode finish')

def build_init(home_path):
    global HOME_PATH
    HOME_PATH = os.path.dirname(os.path.abspath(home_path))
    global PACK_NAME
    PACK_NAME = datetime.datetime.now().strftime('%Y.%m.%d.%H.%M')

    add_log('CLEAN_LOG')
    definite_file_clear()

def definite_file_clear():
    args = ['rm', HOME_PATH + '/*.png']
    execute_shell_command(args, 'F')

    args = ['rm', HOME_PATH + '/*.plist']
    execute_shell_command(args, 'F')

def build_finish(success):
    if success == 'T':
        add_log('finish success')
    else:
        add_log('finish fail')

    name_list = 'jerrylai@123u.com'
    subject = 'Build ' + success 
    content = 'App build in Mac Pro is build ' + success
    send_mail(name_list, subject, content)

    xcode_path = PROJECT_PATH + '/' + PACK_NAME
    if os.path.exists(xcode_path) and os.path.isdir(xcode_path):
        shutil.rmtree(xcode_path)

    definite_file_clear()

def generate_qrcode():
    args = ['python', HOME_PATH + QR_TOOL, PACK_NAME]
    execute_shell_command(args)

def send_mail(name_list, subject, content):
    args = ['python', HOME_PATH + MAIL_TOOL, name_list, subject, content]
    execute_shell_command(args)

def generate_plist():
    data = ''
    with open(HOME_PATH + PLIST_TMP, 'r') as f:
            data = f.read()

    data = data.replace('{VERSION}', BUILD_VERSION)
    data = data.replace('{BUNDLE_ID}', BUNDLE_ID)
    data = data.replace('{PACK_NAME}', PACK_NAME)

    with open(HOME_PATH + '/' + 'hotblood_ios_test_' + PACK_NAME + '.plist', 'w') as f:
            f.write(data)

def build_xcode2ipa():
    add_log('build_xcode2ipa start')

    xcode_path = PROJECT_PATH + '/' + PACK_NAME + '/'

    args = ['xcodebuild', 'clean', '-project', xcode_path + 'Unity-iPhone.xcodeproj', '-configuration', 'Release', '-alltargets']
    ret = execute_shell_command(args)
    if ret != 0:
        add_log('xcodebuild clean fail')
        build_finish('F')
        exit()

    args = ['xcodebuild', 'archive', '-project', xcode_path + 'Unity-iPhone.xcodeproj', '-scheme', 'Unity-iPhone', '-archivePath', xcode_path + 'Unity-iPhone.xcarchive']
    ret = execute_shell_command(args)
    if ret != 0:
        add_log('xcodebuild archive fail')
        build_finish('F')
        exit()
        
    args = ['xcodebuild', '-exportArchive', '-archivePath', xcode_path + 'Unity-iPhone.xcarchive', '-exportPath', xcode_path + 'hotblood_ios_test_' + PACK_NAME + '.ipa', '-exportFormat', 'ipa', '-exportProvisioningProfile', PROVISIONING_PROFILE_NAME]
    ret = execute_shell_command(args)
    if ret != 0:
        add_log('xcodebuild export fail')
        build_finish('F')
        exit()    

    add_log('build_xcode2ipa finish')

if __name__ == '__main__':
    if len(sys.argv) != 3:
        usage()
        exit()

    global par_upload_family
    par_upload_family = sys.argv[1]
    global par_upload_ftp
    par_upload_ftp = sys.argv[2]

    build_init(sys.argv[0])

    #build_unity2xcode()
    #build_xcode2ipa()
    
    generate_plist()
    generate_qrcode()

    #upload()

    #build_finish('T')
                                        
