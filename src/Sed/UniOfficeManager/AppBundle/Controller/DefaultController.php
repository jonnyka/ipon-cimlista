<?php

namespace Sed\UniOfficeManager\AppBundle\Controller;

use Sed\UniOfficeManager\AppBundle\Service\DeployJob;
use Sed\UniOfficeManager\AppBundle\Service\DeployManager;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use BCC\ResqueBundle\Resque;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Process\Process;

class DefaultController extends Controller
{
    /**
     * @Route("/test/", name="test")
     * @Template()
     */
    public function testAction()
    {
        /** @var DeployManager $manager */
        $manager = $this->get('deploy_manager');
        //$manager->cloneRepository('unioffice-central', '/opt/dev/unioffice-central/');
        $manager->gitPull('unioffice-central', '/opt/dev/unioffice-central/');
        $manager->npmAndBower('unioffice-central', '/opt/dev/unioffice-central/');
        //$manager->gruntBuild('unioffice-central', '/opt/dev/unioffice-central/');

        return array();
    }

    /**
     * @Route("/", name="welcome")
     * @Template()
     */
    public function welcomeAction()
    {
        return array();
    }

    /**
     * @Route("/redis/", name="redis")
     * @Template()
     */
    public function redisCommanderAction()
    {
        return array(
            'menu' => 'redis'
        );
    }

    /**
     * @Route("/queue-index/", name="queue_index")
     * @Template()
     */
    public function queueIndexAction()
    {
        return array(
            'resque' => $this->getResque(),
            'menu' => 'queue_index'
        );
    }

    /**
     * @Route("/queue-show/{queue}/", name="queue_show")
     * @Template()
     */
    public function queueShowAction(Request $request, $queue)
    {
        list($start, $count, $showingAll) = $this->getShowParameters($request);

        $queue = $this->getResque()->getQueue($queue);
        $jobs = $queue->getJobs($start, $count);

        if (!$showingAll) {
            $jobs = array_reverse($jobs);
        }

        return array(
            'queue' => $queue,
            'jobs' => $jobs,
            'showingAll' => $showingAll,
        );
    }

    /**
     * @Route("/queue-list-failed/", name="queue_list_failed")
     * @Template()
     */
    public function queueListFailedAction(Request $request)
    {
        list($start, $count, $showingAll) = $this->getShowParameters($request);

        $jobs = $this->getResque()->getFailedJobs($start, $count);

        if (!$showingAll) {
            $jobs = array_reverse($jobs);
        }

        return array(
            'jobs' => $jobs,
            'showingAll' => $showingAll,
            'menu' => 'queue_list_failed',
        );
    }

    /**
     * @Route("/queue-list-scheduled/", name="queue_list_scheduled")
     * @Template()
     */
    public function queueListScheduledAction()
    {
        return array(
            'timestamps' => $this->getResque()->getDelayedJobTimestamps(),
            'menu' => 'queue_list_scheduled',
        );
    }

    /**
     * @Route("/queue-show-timestamp/{timestamp}/", name="queue_show_timestamp")
     * @Template()
     */
    public function queueShowTimestampAction($timestamp)
    {
        $jobs = array();

        // we don't want to enable the twig debug extension for this...
        foreach ($this->getResque()->getJobsForTimestamp($timestamp) as $job) {
            $jobs[] = print_r($job, true);
        }

        return array(
            'timestamp' => $timestamp,
            'jobs' => $jobs,
        );
    }

    /**
     * @return \BCC\ResqueBundle\Resque
     */
    protected function getResque()
    {
        return $this->get('bcc_resque.resque');
    }

    /**
     * decide which parts of a job queue to show
     *
     * @return array
     */
    private function getShowParameters(Request $request)
    {
        $showingAll = false;
        $start = -100;
        $count = -1;

        if ($request->query->has('all')) {
            $start = 0;
            $count = -1;
            $showingAll = true;
        }

        return array($start, $count, $showingAll);
    }
}

