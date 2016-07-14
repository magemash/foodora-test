<?php

namespace Scottpringle\Console\Model;

class Vendor extends Db
{
    protected $specialDays;
    protected $normalDays;
    protected $newNormalDays;
    protected $id;

    public function findOne($id)
    {
        $this->id = $id;
        $this->fetchNormalDays();
        $this->fetchSpecialDays();
    }

    public function fetchAll()
    {
        return $this->query("select * FROM vendor");
    }

    public function fetchSpecialDays()
    {
        $this->specialDays = $this->query("select * FROM vendor_special_day where vendor_id = {$this->id}"
        );
    }

    public function fetchNormalDays()
    {
        $this->normalDays = $this->query("select * FROM vendor_schedule_tmp where vendor_id = {$this->id}"
        );
    }

    /**
     * @param $date
     * @return mixed
     */
    public function convertDateToNumber($date)
    {
        $date = \DateTime::createFromFormat('Y-m-d', $date);

        $weekday = $date->format('D');

        $days = array(
            'Mon'=>1,
            'Tue'=>2,
            'Wed'=>3,
            'Thu'=>4,
            'Fri'=>5,
            'Sat'=>6,
            'Sun'=>7,
        );

        return $days[$weekday];
    }

    /**
     *
     */
    public function convertAllSpecialToNormal()
    {
        foreach ($this->specialDays as $specialDay) {
            $this->convertSpecialToNormal($specialDay);
        }

        $this->addOriginalDaysNotOverwritten();
    }

    /**
     * @param $row
     */
    public function convertSpecialToNormal($row)
    {
        $day = $this->convertDateToNumber($row['special_date']);

        $times = $this->convertTimes($row);

        $this->newNormalDays[$day] = $times;
    }

    /*
     * This appends original scheduled days which do not have a special day entry
     */
    public function addOriginalDaysNotOverwritten()
    {
        $missingWeekday = array();
        foreach ($this->normalDays as $normalDay) {

            // check if the special days have an entry for each original day
            // if not the original day is added to the new special days
            if (!array_key_exists($normalDay['weekday'], $this->newNormalDays) || array_key_exists($normalDay['weekday'], $missingWeekday)) {
                $missingWeekday[$normalDay['weekday']] = true;
                $this->newNormalDays[$normalDay['weekday']][] = array(
                    'all_day' => $normalDay['all_day'],
                    'start_hour' => $normalDay['start_hour'],
                    'stop_hour' => $normalDay['stop_hour'],
                );
            }
        }
    }

    /**
     * Saves the new days into the database
     */
    public function saveNewDays()
    {
        $stmt = $this->db->prepare('INSERT INTO vendor_schedule (vendor_id, weekday, all_day, start_hour, stop_hour) VALUES (:vendor_id, :weekday, :all_day, :start_hour, :stop_hour)');

        foreach ($this->newNormalDays as $key => $day) {
            foreach ($day as $single) {
                $stmt->bindParam(':vendor_id', $this->id);
                $stmt->bindParam(':weekday', $key);
                $stmt->bindParam(':all_day', $single['all_day']);
                $stmt->bindParam(':start_hour', $single['start_hour']);
                $stmt->bindParam(':stop_hour', $single['stop_hour']);
                $stmt->execute();
            }
        }
    }

    /**
     * Converts the old days to new days
     *
     * @param $row
     * @return array
     */
    public function convertTimes($row)
    {
        $times = array();

        if ($row['event_type'] === "opened") {
            if ($row['all_day']) {
                $times[] = array(
                    'all_day' => 1,
                    'start_hour' => null,
                    'stop_hour' => null
                );
            } else {
                $times[] = array(
                    'all_day' => 0,
                    'start_hour' => $row['start_hour'],
                    'stop_hour' => $row['stop_hour']
                );
            }

        } else if ($row['event_type'] === "closed") {
            if ($row['all_day']) {
                $times[] = array();
            } else {

                if ($row['start_hour'] != '00:00:00') {
                    $times[] = array(
                        'all_day' => 0,
                        'start_hour' => '00:00:00',
                        'stop_hour' => $row['start_hour']
                    );
                }

                if ($row['stop_hour'] != '23:59:59') {
                    $times[] = array(
                        'all_day' => 0,
                        'start_hour' => $row['stop_hour'],
                        'stop_hour' => "23:59:59"
                    );
                }
            }
        }

        return $times;
    }
}