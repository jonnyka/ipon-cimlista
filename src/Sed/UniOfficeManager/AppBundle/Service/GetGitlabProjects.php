<?php

namespace Sed\UniOfficeManager\AppBundle\Service;

class GetGitlabProjects {
    public function getProjects() {
        $arr = array();

        $curl = curl_init();
        $url = 'http://gitlab.sed.hu/api/v3/projects/search/unioffice-?private_token=w3PaXTjrWdcWMc-t9G2y';
        curl_setopt_array($curl, array(
            CURLOPT_RETURNTRANSFER => 1,
            CURLOPT_URL => $url
        ));

        $resp = curl_exec($curl);
        if ($resp) {
            $respArray = json_decode($resp, true);

            foreach ($respArray as $project) {
                $name = $project['name'];
                $arr[$name] = $name;
            }

            asort($arr);
        }

        curl_close($curl);

        return $arr;
    }
}