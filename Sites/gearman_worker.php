<?php
date_default_timezone_set('Asia/Shanghai');

echo "starting\n";

$gmworker = new GearmanWorker();
$gmworker->addServer("10.0.128.219");
$gmworker->addFunction("hotblood_pack_task", "hotblood_pack_task_callback");

print "Waiting for job...\n";

while($gmworker->work())
{
	if ($gmworker->returnCode() != GEARMAN_SUCCESS)
	{
		echo "return_code: ".$gmworker->returnCode()."\n";
		break;
	}	
}

function hotblood_pack_task_callback($job)
{
	echo "get tast at ".date("Y-m-d H:i:s", time())." ".$job->workload()."\n";
	popen("python ios_run.py ".$job->workload(), 'r');
	return "task";
}
?>