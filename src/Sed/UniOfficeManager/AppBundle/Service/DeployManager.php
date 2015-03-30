<?php

namespace Sed\UniOfficeManager\AppBundle\Service;

use Doctrine\ORM\EntityManager;
use Symfony\Component\Form\Exception\InvalidArgumentException;
use Symfony\Component\DependencyInjection\ContainerInterface as Container;
use BCC\ResqueBundle\Resque;
use Symfony\Component\Process\Process;

class DeployManager {

    protected $em;
    protected $container;
    protected $resque;

    public function __construct(EntityManager $em, Container $container, Resque $resque, $rootDir) {
        $this->em = $em;
        $this->container = $container;
        $this->resque = $resque;
        $this->key = realpath($rootDir . '/../..') . '/key/uniofficeuser';
        $this->dumpDir = realpath($rootDir . '/../../') . '/dump/';
        $this->dbUser = $container->getParameter('database_user');
        $this->dbPass = $container->getParameter('database_password');
        $this->dbHost = $container->getParameter('database_host');
        $this->dbPort = $container->getParameter('database_port');
    }

    /**
     * git clone repository
     */
    public function cloneRepository($gitrepo, $directory) {
        $d = $directory;
        $command = $this->initDir($d) . "ssh-agent bash -c 'ssh-add " . $this->key . "; git clone http://gitlab.sed.hu/unioffice/" . $gitrepo . ".git;'";

        $resque = $this->resque;

        $job = new DeployJob();
        $job->args = array(
            'command' => $command,
            'type' => 'Clone repository',
        );

        $resque->enqueue($job);
    }

    /**
     * git pull
     */
    public function gitPull($name, $directory) {
        $d = $directory . "/" . $name;
        $command = $this->initDir($d) . "ssh-agent bash -c 'ssh-add " . $this->key . "; git pull --no-edit;'";

        $resque = $this->resque;

        $job = new DeployJob();
        $job->args = array(
            'command' => $command,
            'type' => 'Git pull',
        );

        $resque->enqueue($job);
    }

    /**
     * npm install & bower install
     */
    public function npmAndBower($name, $directory) {
        $d = $directory . "/" . $name;
        $command = $this->initDir($d) . "npm install; bower install -F;";

        $resque = $this->resque;

        $job = new DeployJob();
        $job->args = array(
            'command' => $command,
            'type' => 'npm & bower install',
        );

        $resque->enqueue($job);
    }

    /**
     * grunt build
     */
    public function gruntBuild($name, $directory) {
        $d = $directory . "/" . $name;
        $command = $this->initDir($d) . "grunt build --no-color;";

        $resque = $this->resque;

        $job = new DeployJob();
        $job->args = array(
            'command' => $command,
            'type' => 'grunt build',
        );

        $resque->enqueue($job);
    }

    /**
     * create nginx log directory
     */
    public function createLogDirectory($name) {
        $command = $this->initVars() . "mkdir -p /var/log/nginx/" . $name;

        $resque = $this->resque;

        $job = new DeployJob();
        $job->args = array(
            'command' => $command,
            'type' => 'Create nginx log directory',
        );

        $resque->enqueue($job);
    }


    /**
     * create database
     */
    public function createDb($name) {
        $command = $this->initVars() . "mkdir -p " . $this->dumpDir . "; PGPASSWORD='" . $this->dbPass . "' psql -U " . $this->dbUser . " -h " . $this->dbHost . " " . $this->dbPort . " -c 'CREATE DATABASE \"" . $name . "\" WITH OWNER " . $this->dbUser . ";'";

        $resque = $this->resque;

        $job = new DeployJob();
        $job->args = array(
            'command' => $command,
            'type' => 'Create database',
        );

        $resque->enqueue($job);
    }

    /**
     * dump database
     */
    public function dumpDb($name) {
        $command = $this->initVars() . "mkdir -p " . $this->dumpDir . "; PGPASSWORD='" . $this->dbPass . "' pg_dump -U " . $this->dbUser . " -h " . $this->dbHost . " " . $this->dbPort . " " . $name . " > " . $this->dumpDir . $name . ".sql;";

        $resque = $this->resque;

        $job = new DeployJob();
        $job->args = array(
            'command' => $command,
            'type' => 'Dump database',
        );

        $resque->enqueue($job);
    }


    /**
     * delete site directory
     */
    public function deleteSiteDir($directory) {
        $command = "rm -Rf " . $directory . ";";

        $resque = $this->resque;

        $job = new DeployJob();
        $job->args = array(
            'command' => $command,
            'type' => 'Delete site directory',
        );

        $resque->enqueue($job);
    }

    private function initVars() {
        return 'export HOME=/Users/jonny/; export PATH=$PATH:/usr/local/sbin:/Library/PostgreSQL/9.3/bin/:/usr/local/php5/bin/:/Applications/MAMP/Library/bin/:/Applications/MAMP/bin/php/php5.5.10/bin; ';
    }

    private function initDir($d) {
        return $this->initVars() . 'mkdir -p ' . $d . "; cd " . $d . "; ";
    }

    private function dbg($command) {
        echo '<pre>' . $command . '</pre>';
    }
}
