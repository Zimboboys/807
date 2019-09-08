<?php

class checklist
{
    private $conn;

    private $id;
    private $name;
    private $req;

    function __construct($conn, $checklistID)
    {
        $this->conn = $conn;

        // get basic checklist data
        $this->id = $checklistID;
        $basicInfoSQL = "SELECT * FROM station_checklist_item WHERE checklistID = $this->id";
        $basicInfoQ = mysqli_query($conn, $basicInfoSQL);
        while ($info = $basicInfoQ->fetch_assoc()) {
            $this->name = $info['item'];
            $this->req = $info['required'];
        }
    }

    function getID()
    {
        return $this->id;
    }

    function getName()
    {
        return $this->name;
    }

    function getRequired()
    {
        return $this->req;
    }

    function translateReq() {
        return $this->req ? 'star' : 'star_border';
    }

}