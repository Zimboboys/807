<?php

class event
{
    private $id;
    private $title;
    private $datetime;
    private $memberGroups = [];

    function __construct($conn, $id)
    {
        $this->id = $id;
        $getEventInfoSQL = "SELECT * FROM events WHERE eventID = $id";
        $getEventInfoQ = mysqli_query($conn, $getEventInfoSQL);
        while ($event = $getEventInfoQ->fetch_assoc()) {
            $this->title = $event['title'];
            $this->datetime = $event['datetime'];

            $this->memberGroups = explode('.', $event['memberGroups']);
            foreach ($this->memberGroups as $i => $group) {
                $this->memberGroups[$i] = substr($group, 2);
            }
            array_pop($this->memberGroups); // todo fix the DB formatting for this
        }
    }

    function getID()
    {
        return $this->id;
    }

    function getTitle()
    {
        return $this->title;
    }

    function getTime()
    {
        return $this->datetime;
    }

    function getGroups()
    {
        return $this->memberGroups;
    }

    function containsGroup($group)
    {
        return in_array($group, $this->memberGroups);
    }

    function hasBand($band_array)
    {
        foreach ($band_array as $i => $band) {
            if (in_array($band, $this->memberGroups)) return true;
        }
        return false;
    }

    function openAttendance($position)
    {
        $tz_offset = -420; // accounts for pacific time. ranges are in minutes
        $lower_range = 0;
        $upper_range = 0;

        $event_difference = (time() - strtotime($this->getTime())) / 60; // i do not understand

        if ($position == "sectionleader") {
            $lower_range = -15;
            $upper_range = 10;
        } else if ($position == "personnel") {
            $lower_range = 10;
            $upper_range = 20;
        } else if ($position == "drummajor") {
            $lower_range = 20;
            $upper_range = 35;
        }

        return (($lower_range + $tz_offset) < $event_difference) && ($event_difference < ($upper_range + $tz_offset));
    }
}