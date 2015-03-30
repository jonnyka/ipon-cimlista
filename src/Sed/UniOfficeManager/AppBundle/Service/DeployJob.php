<?php

namespace Sed\UniOfficeManager\AppBundle\Service;

use Symfony\Component\Process\Process;
use BCC\ResqueBundle\ContainerAwareJob;

class DeployJob extends ContainerAwareJob
{
    public function __construct() {
        $this->queue = 'deploy';
    }

    public function run($args) {
        $type = $args['type'];
        $command = $args['command'];

        $this->runProcess($type, $command);
        //$output = $process->getOutput();

        //return $output;
    }

    private function runProcess($commandType, $command) {
        $address = $this->getContainer()->getParameter('socket_url');
        $port = $this->getContainer()->getParameter('socket_port_local');
        $socket = socket_create(AF_INET, SOCK_STREAM, SOL_TCP);

        socket_connect($socket, $address, $port);

        $this->sWrite($socket, $commandType, 'begin');
        $this->sWrite($socket, $command, 'command');
        $output = false;

        $process = new Process($command);
        $process->setTimeout(3600);
        $process->run(
            function ($type, $buffer) use ($socket, $commandType) {
                $this->sWrite($socket, $buffer);
                $output = true;
            }
        );

        if (!$output) {
            $this->sWrite($socket, 'Done.');
        }

        $this->sWrite($socket, $commandType, 'end');

        socket_close($socket);

        //return $process;
    }

    private function sWrite($socket, $text, $type = 'normal') {
        $divider = '<hr />';
        $nl = "<br />";

        switch ($type) {
            case 'begin':
                $text = $nl . $divider . '<span class="succ">Begin: ' . $text . '</span>' . $divider;
                break;
            case 'command':
                $text = '<span class="comm">Command: ' . $text . '</span>' . $divider;
                break;
            case 'err':
                $text = $divider . '<span class="err">Error: ' . $text . '</span>' . $divider . $nl;
                break;
            case 'end':
                $text = $divider . '<span class="succ">End: ' . $text . '</span>' . $divider . $nl;
                break;
        }

        $text = str_replace('fatal', '<span class="err">fatal</span>', $text);
        socket_write($socket, $text, strlen($text));

        usleep(100000);
    }
}