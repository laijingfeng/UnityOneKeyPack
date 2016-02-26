#!/bin/bash

start()
{
    /usr/bin/nohup php gearman_worker.php &> ./gearman_worker.log &
}

stop()
{
    pid=`ps -ef | grep gearman_worker | sed 's/  */ /g' | awk '{if($8=="php") print $2}'`
    for id in $pid; do
        kill $id
    done
}

if [ $# -lt 1 ]; then
    echo "./gearman_worker.sh start|stop|restart"
fi

for arg in $*; do
    if [ $arg = "start" ]; then
        start
    elif [ $arg = "stop" ]; then
        stop
    elif [ $arg = "restart" ]; then
        stop
        start
    else
        echo "./gearman_worker.sh start|stop|restart"
    fi
done
