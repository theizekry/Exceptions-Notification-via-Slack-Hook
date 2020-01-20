<?php

namespace App\Listeners\Exceptions;

use App\Events\Exceptions\ExceptionEvent;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Arr;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Exception;
use GuzzleHttp;

class PushNotificationToSlackListener
{
    /*
   * @var Slack Api Accept Message Format Sorted in fields key as json so we will append our Message in this Var.
   * */
    private $slack_message_fields = [];

    /**
     * A LIST OF THE EXCEPTIONS THAT ARE WE DO NOT NEED TO NOTIFY WITH.
     *
     * @var array
     */
    private $dontNotify = [
        NotFoundHttpException::class, // EXAMPLE FOR DO NOT REPORT ANY NOT FOUND EXCEPTION.
    ];

    /**
     * Handle the event.
     *
     * @return void
     * @throws GuzzleHttp\Exception\
     */
    public function handle(ExceptionEvent $event)
    {
        /* START NOTIFICATION IN CASE EXCEPTION IS EXISTS IN DON'T NOTIFY LIST ( THIS MEANS IF BELOW CONDITION GOT TRUE WE WILL STOP SCRIPT AND DON'T NOTIFY ) */

        if ($this->shouldNotNotify($event->exception)) {
            return;
        }

        /* STOP NOTIFICATION IN CASE EXCEPTION IS EXISTS IN DON'T NOTIFY LIST ( THIS MEANS IF BELOW CONDITION GOT TRUE WE WILL STOP SCRIPT AND DON'T NOTIFY ) */

        /* START CHECK IF WEB-HOOK EXCEPTION URL EXISTS OTHERWISE RETURN */

        if (! config('slack.slack-exception-hook-url') ) {
            return;
        }

        /* END CHECK IF WEB-HOOK EXCEPTION URL EXISTS OTHERWISE RETURN */

        /* START TRYING TO CONNECT TO SLACK API VIA POST GUZZLE REQUEST */

        try {

            (new GuzzleHttp\Client())->post(config('slack.slack-exception-hook-url'), [
                'json' => $this->formatTextMessageToSlack($event)
            ]);

        } catch (Exception $exception) {
            return;
        }

        /* END TRYING TO CONNECT TO SLACK API VIA POST GUZZLE REQUEST */
    }

    /**
     ** THIS METHOD JUST TO CHECK IF WE DO NOT NEED TO NOTIFY THAT EXCEPTION TYPE ACCORDING TO OUR DON'T NOTIFY ARRAY LIST.
     ** IF WE HAVE ANY EXCEPTION IN ABOVE LIST THIS METHOD SHOULD BE RETURN FALSE ( DO NOT NOTIFY US ), OTHERWISE NOTIFY.
     *
     * @param Exception $exception
     *
     * @return bool
     */
    private function shouldNotNotify(Exception $exception)
    {
        return ! is_null(Arr::first($this->dontNotify, function ($type) use ($exception) {
            return $exception instanceof $type;
        }));
    }

    /**
     * In Slack Application You can format your message before send and here you can do that! .
     *
     * @param $event
     *
     * @return array
     */
    private function formatTextMessageToSlack($event)
    {
        return [
            "text" => 'New Exception Occurred in Your Project',
            "attachments" => [
                [
                    "text"  => "",
                    "color" => "#F00",
                    "fields" => $this->getMessageFields($event)
                ]
            ]
        ];
    }

    /* START SECTION GET MESSAGE FIELDS */

    /**
     * Generate Message Fields array according Slack Configuration ( Get active options only ).
     *
     * @param $event
     * @return array
     */
    private function getMessageFields($event)
    {
        $messageConfiguration = config('slack.push-notification-message');

        if(isset($messageConfiguration['getCode']) && $messageConfiguration['getCode']) {
            $this->slack_message_fields [] = [
                "title" => "Status Code",
                "value" => $this->detectStatusCode($event->exception)
            ];
        }

        if(isset($messageConfiguration['getMessage']) && $messageConfiguration['getMessage']) {
            $this->slack_message_fields [] = [
                "title" => "Exception",
                "value" => $event->exception->getMessage(),
            ];
        }

        if(isset($messageConfiguration['getFile']) && $messageConfiguration['getFile']) {
            $this->slack_message_fields [] = [
                "title" => "File",
                "value" => $event->exception->getFile()
            ];
        }

        if(isset($messageConfiguration['getLine']) && $messageConfiguration['getLine']) {
            $this->slack_message_fields [] = [
                "title" => "Line",
                "value" => $event->exception->getLine()
            ];
        }

        if(isset($messageConfiguration['getTraceAsString']) && $messageConfiguration['getTraceAsString']) {
            $this->slack_message_fields [] = [
                "title" => "Trace As String",
                "value" => $event->exception->getTraceAsString()
            ];
        }

        if(isset($messageConfiguration['getDateWithTime']) && $messageConfiguration['getDateWithTime']) {
            $this->slack_message_fields [] = [
                "title" => "Date & Time",
                "value" => now()->toDateTimeString()
            ];
        }

        if(isset($messageConfiguration['getTrace']) && $messageConfiguration['getTrace']) {
            $this->slack_message_fields [] = [
                "title" => "Trace As String",
                "value" => $event->exception->getTrace()
            ];
        }

        if(isset($messageConfiguration['getRequestData']) && $messageConfiguration['getRequestData']) {
            $this->slack_message_fields [] = [
                "title" => "Request Data",
                "value" => GuzzleHttp\json_encode($event->requestData)
            ];
        }

        if(isset($messageConfiguration['getPrevious']) && $messageConfiguration['getPrevious']) {
            $this->slack_message_fields [] = [
                "title" => "Get Previous",
                "value" =>  $event->exception->getPrevious()
            ];
        }

        if(isset($messageConfiguration['full_url']) && $messageConfiguration['full_url']) {
            $this->slack_message_fields [] = [
                "title" => "Full URL",
                "value" =>  request()->fullUrl() ?? ' Cannot detect Request URL'
            ];
        }

        if(isset($messageConfiguration['request_headers']) && $messageConfiguration['request_headers']) {
            $this->slack_message_fields [] = [
                "title" => "Request Headers",
                "value" => GuzzleHttp\json_encode(request()->headers->all()) ?? ' Cannot detect Request Headers'
            ];
        }

        if(isset($messageConfiguration['ip_address']) && $messageConfiguration['ip_address']) {
            $this->slack_message_fields [] = [
                "title" => "IP Address",
                "value" => request()->ip()
            ];
        }

        return $this->slack_message_fields;
    }

    /* END SECTION GET MESSAGE FIELDS */


    /**
     * It just get Status Code if available otherwise return an Exception Code.
     *
     * @param Exception $exception
     *
     * @return int|string
     */
    private function detectStatusCode(Exception $exception)
    {
        if ($exception instanceof  \Symfony\Component\HttpKernel\Exception\HttpException) {
            return 'Http Status Code : ' . $exception->getStatusCode() ;
        }

        return 'Exception Code : ' . $exception->getCode();
    }
}
