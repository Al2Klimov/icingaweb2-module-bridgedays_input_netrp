<?php

namespace Icinga\Module\Bridgedays_input_netrp;

use DateInterval;
use DateTime;
use Icinga\Module\Bridgedays\Forms\ImportForm;
use Icinga\Module\Bridgedays\Intrface\Input;

class NetRpInput implements Input
{
    protected $name;
    protected $url;

    public function __construct($name, $url)
    {
        $this->name = $name;
        $this->url = $url;
    }

    public function getId()
    {
        return md5($this->name);
    }

    public function getName()
    {
        return sprintf(mt('bridgedays_input_netrp', 'NETRP: %s'), $this->name);
    }

    public function getFields()
    {
        return [
            [
                'text',
                'username',
                [
                    'label'       => mt('bridgedays_input_netrp', 'Username'),
                    'description' => mt('bridgedays_input_netrp', 'NETRP login username'),
                    'required'    => true,
                ]
            ],
            [
                'password',
                'password',
                [
                    'label'       => mt('bridgedays_input_netrp', 'Password'),
                    'description' => mt('bridgedays_input_netrp', 'NETRP login password (will disappear from memory as soon as possible)'),
                    'required'    => true,
                ]
            ]
        ];
    }

    public function import(ImportForm $form)
    {
        /** @var DateTime $start */
        /** @var DateTime $end */

        $baseUrl = rtrim($this->url, '/');
        $start = $form->getValue('start');
        $end = $form->getValue('end');
        $maxdays = (int)$form->getValue('maxdays');
        $curl = new Curl;

        $curl->request(
            'POST',
            "$baseUrl/login",
            [
                'User-Agent: Mozilla/4.0',
                'X-Requested-With: XMLHttpRequest',
                'Content-Type: application/x-www-form-urlencoded'
            ],
            sprintf(
                'user=%s&pw=%s',
                rawurlencode($form->getValue('username')),
                rawurlencode($form->getValue('password'))
            )
        );

        $tasks = json_decode($curl->request(
            'GET',
            sprintf(
                '%s/api/tasks?from=%d&to=%d',
                $baseUrl,
                ($start->getTimestamp() - (31 * 24 * 60 * 60)) * 1000,
                ($end->getTimestamp() + (31 * 24 * 60 * 60)) * 1000
            ),
            ['User-Agent: Mozilla/4.0', 'X-Requested-With: XMLHttpRequest']
        ));

        $oneDay = new DateInterval('P1D');
        $oneMonth = new DateInterval('P1M');
        $weekend = [0 => null, 6 => null];
        $limit = (clone $end)->add($oneMonth);
        $workdays = [];
        $i = 0;

        /** @var DateTime $date */
        for ($date = (clone $start)->sub($oneMonth); $date <= $limit; $date->add($oneDay)) {
            if (!array_key_exists($date->format('w'), $weekend)) {
                $workdays[$date->format('Y-m-d')] = $i;
            }

            ++$i;
        }

        foreach ((array)$tasks->holidays as $year => $holidays) {
            foreach ((array)$holidays as $holiday => $_) {
                list($day, $month) = explode('-', $holiday);
                unset($workdays[sprintf('%04d-%02d-%02d', (int)$year, (int)$month, (int)$day)]);
            }
        }

        $workPeriods = [];
        $firstDate = $prevDate = '0000-00-00';
        $firstNum = $prevNum = -23;

        $workdays['9999-99-99'] = 2147483647;

        $start = $start->format('Y-m-d');
        $end = $end->format('Y-m-d');

        foreach ($workdays as $workday => $dayNum) {
            if ($dayNum - 1 === $prevNum) {
                $prevDate = $workday;
                $prevNum = $dayNum;
            } else {
                if ($prevNum - $firstNum + 1 <= $maxdays && $prevDate >= $start && $prevDate <= $end) {
                    $workPeriods[$firstDate] = $prevDate;
                }

                $firstDate = $prevDate = $workday;
                $firstNum = $prevNum = $dayNum;
            }
        }

        unset($workPeriods['0000-00-00']);

        return $workPeriods;
    }
}
