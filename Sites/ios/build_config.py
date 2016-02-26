# -*- coding: utf-8 -*-

UNITY_PATH = '/Applications/Unity/Unity.app/Contents/MacOS/Unity'

PROJECT_PATH = '/Users/thomasmeng/Desktop/HotCode/trunk_auto/client/game/client'
#PROJECT_PATH = '/Users/thomasmeng/Desktop/TestProject'

CODE_SIGN_IDENTITY_NAME = 'iPhone Distribution: Shanghai Jiang You Information Technology Company Limited'

PROVISIONING_PROFILE_NAME = 'JianSheng_20160128_AppStore'

BUILD_VERSION = '1.0'

BUNDLE_ID = 'com.jiansheng.gdxy.test'

### relative path is ralate to build_ios.py path ###

LOG_FILE_PATH = '/../msg_build.txt'

RES_FILE_PATH = '/../msg_res.txt'

LOG_PATH = '/build_log.py'

PLIST_TMP = '/hotgame.plist.template'

UPLOAD_TOOL = '/UploadTool.sh'

QR_TOOL = '/qr.py'

MAIL_TOOL = '/send_mail.py'

MAIL_TO_NAME_LIST = 'jerrylai@123u.com' # more use ; split

MAIL_SUBJECT_SUCCESS = 'Build Success'

MAIL_CONTENT_SUCCESS = 'App build in Mac Pro success'

MAIL_SUBJECT_FAIL = 'Build Fail'

MAIL_CONTENT_FAIL = 'App build in Mac Pro fail'                                       
