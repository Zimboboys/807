<?php

class station
{
    private $conn;

    private $id;
    private $name;
    private $maxFailed;

    private $infoID;
    private $infoText;

    // content[role][purpose] = ["contentID" => "commandText]
    private $content;

    // station tasks will be stored as follows
    // {checklist_group_id: [checklist_id, checklist_id, ...], ...}
    private $checklist = [];
    private $requiredItems = [];

    function __construct($conn, $stationID)
    {
        $this->conn = $conn;

        // get basic info about the station
        $this->id = $stationID;
        $basicInfoSQL = "SELECT * FROM stations WHERE stationID=$this->id";
        $basicInfoQ = mysqli_query($this->conn, $basicInfoSQL);
        while ($info = $basicInfoQ->fetch_assoc()) {
            $this->name = $info['name'];
            $this->maxFailed = $info['maxFailed'];
        }

        // fills up the checklist array
        $getGroupsSQL = "SELECT * FROM station_checklist_group WHERE stationID=$this->id AND hidden = 0";
        $getGroupsQ = mysqli_query($this->conn, $getGroupsSQL);
        while ($groups = $getGroupsQ->fetch_assoc()) {
            $checklistIDs = array();
            $getItemsSQL = "SELECT * FROM station_checklist_item WHERE stationGroupID=" . $groups['groupID'] . " AND hidden = 0";
            $getItemsQ = mysqli_query($this->conn, $getItemsSQL);
            while ($items = $getItemsQ->fetch_assoc()) {
                array_push($checklistIDs, $items['checklistID']);

                if ($items['required'] == 1) {
                    array_push($this->requiredItems, $items['checklistID']);
                }
            }
            $this->checklist[$groups['groupID']] = $checklistIDs;
        }

        // info getters
        $getStationInfoSQL = "SELECT * FROM station_info WHERE stationID = " . $this->id;
        $getStationInfoQ = mysqli_query($conn, $getStationInfoSQL);
        while ($stationInfo = $getStationInfoQ->fetch_assoc()) {
            $this->infoID = $stationInfo['infoID'];
            $this->infoText = $stationInfo['infoText'];
        }

        $this->content = [];
        $getPacketSQL = "SELECT contentID, role, purpose, commandText FROM station_packet_content INNER JOIN station_packet ON station_packet.instructionID = station_packet_content.instructionID  WHERE station_packet.stationID=$this->id";
        $getPacketQ = mysqli_query($conn, $getPacketSQL);
        while ($p = $getPacketQ->fetch_assoc()) {
            $this->content[$p['role']][$p['purpose']] = [$p['contentID'] => $p['commandText']];

        }
    }

    function getID()
    {
        return $this->id;
    }

    function getStationName()
    {
        return $this->name;
    }

    function getChecklistGroups()
    {
        $groupList = array();
        foreach ($this->checklist as $group => $items) {
            array_push($groupList, $group);
        }
        return $groupList;
    }

    function getChecklistItems($groupID)
    {
        return $this->checklist[$groupID];
    }

    function getGroupName($groupID)
    {
        $groupName = "";
        $getNameSQL = "SELECT title FROM station_checklist_group WHERE groupID=$groupID";
        $getNameQ = mysqli_query($this->conn, $getNameSQL);
        while ($name = $getNameQ->fetch_assoc()) {
            $groupName = $name['title'];
        }
        return $groupName;
    }

    function getItemName($itemID)
    {
        $itemName = "";
        $getNameSQL = "SELECT item FROM station_checklist_item WHERE checklistID=$itemID";
        $getNameQ = mysqli_query($this->conn, $getNameSQL);
        while ($name = $getNameQ->fetch_assoc()) {
            $itemName = $name['item'];
        }
        return $itemName;
    }

    function getMaxFailed()
    {
        return $this->maxFailed;
    }

    function getRequiredItems()
    {
        return $this->requiredItems;
    }

    /* info getters */
    function getInfoID()
    {
        return $this->infoID;
    }

    function getInfoText()
    {
        return $this->infoText;
    }

    function getPacketContent($role, $purpose)
    {
        return $this->content[$role][$purpose];
    }

    function getPacketID($role, $purpose) {

    }

}