<?php


namespace lygav\slackbot\Handlers;

use lygav\slackbot\SlackRequest;

class CurlHandler implements RequestHandler
{
    public function __invoke(SlackRequest $request)
    {
        $ch = curl_init();
        curl_setopt_array(
            $ch,
            array(
                CURLOPT_URL => $request->url(),
                CURLOPT_POSTFIELDS => $request->body(),
                CURLOPT_SSL_VERIFYPEER => FALSE,
                CURLOPT_SSL_VERIFYHOST => FALSE,
                CURLOPT_POST => TRUE,
                CURLOPT_RETURNTRANSFER => TRUE,
            )
        );
        $result = curl_exec($ch);
        curl_close($ch);
        return $result;
    }
}