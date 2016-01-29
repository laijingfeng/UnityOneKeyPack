#!/usr/bin/python
# -*- coding: utf-8 -*-
import sys, os, time, atexit, platform
import json
import logging
from lib.daemon import Daemon
import lib.logger as logger
from gearman import GearmanWorker

PROGRAM_NAME = 'mac_gearman'
LOGGER_DIR = '/tmp/gearman/'
GEARMAN_HOST = '10.0.128.219'

logger.level=logger.INFO
logger.outs=[LOGGER_DIR+PROGRAM_NAME+'.log']
logger.maxsize=100*1000*1000
log=logger.Logger(PROGRAM_NAME)

# 编译打包ios和android
def pack_ios_android_callback(gearman_worker, job):
    log.info('pack_ios_android req: '+job.data)
    try:
        param = json.loads(job.data)
    except:
        log.info('error: Parse pack data failed.')
        return

    os.system("cd /Users/raojm/Desktop/GitFile/tools/cocos2d-console/bin; bash actgame -c "+param["c"]+" -p "+param["p"]+" -v "+param["v"]+" -m "+param["m"]+" -o "+param["o"]+" -l "+param["l"]+" -t "+param["task_id"]+" > "+LOGGER_DIR+"pack.log 2>&1")
    # os.system("cd /Users/raojm/Desktop/GitFile/tools/cocos2d-console/bin; bash actgame -c jr -p ios -v 00.01.00 -m intra -o stable_version_1.0.7 -l stable_version_1.0.7 -a true > "+LOGGER_DIR+"pack.log")
    return "finished."

class CustomGearmanWorker(GearmanWorker):
    def on_job_execute(self, current_job):
        print "Job started"
        return super(CustomGearmanWorker, self).on_job_execute(current_job)

    def on_job_exception(self, current_job, exc_info):
        print "Job failed, CAN stop last gasp GEARMAN_COMMAND_WORK_FAIL"
        return super(CustomGearmanWorker, self).on_job_exception(current_job, exc_info)

    def on_job_complete(self, current_job, job_result):
        print "Job completed."
        return super(CustomGearmanWorker, self).send_job_complete(current_job, job_result)

    def after_poll(self, any_activity):
        # Return True if you want to continue polling, replaces callback_fxn
        return True

class GearmanDaemon(Daemon):
    def __init__(self, pidfile, stdout='/dev/null', stderr='/dev/null', stdin='/dev/null', cwd='/'):
        Daemon.__init__(self, pidfile, stdout, stderr, stdin)
        self.cwd=cwd

    def run(self):
        worker = CustomGearmanWorker([GEARMAN_HOST])
        worker.register_task("pack_ios_android", pack_ios_android_callback)
        while True:
            try:
                worker.work()
            except:
                log.info('Gearman error occured, try it again in 30 seconds.')
                time.sleep(30)

if __name__ == "__main__":
    if platform.system() != "Darwin":
        print "Mac OS X needed."
        sys.exit(2)

    daemon = GearmanDaemon(LOGGER_DIR+PROGRAM_NAME+'.pid', LOGGER_DIR+PROGRAM_NAME+'.log', LOGGER_DIR+PROGRAM_NAME+'.log', '/dev/null', os.getcwd())

    if len(sys.argv) == 2:
        if 'start' == sys.argv[1]:
            log.info('start')
            daemon.start()
        elif 'stop' == sys.argv[1]:
            daemon.stop()
            log.info('stop')
        elif 'restart' == sys.argv[1]:
            daemon.restart()
            log.info('restart')
        else:
            print "Unknown command"
            sys.exit(2)
        sys.exit(0)
    else:
        print "usage: %s start|stop|restart" % sys.argv[0]
        sys.exit(2)
