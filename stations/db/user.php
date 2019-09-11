<?php

class user
{
    private $conn;
    private $id;
    private $username;
    private $name;
    private $groups;
    private $bands = [];

    // attempts are all attempt IDs
    private $attempts = array();

    // progress is the most up to date attempts
    private $progress = array();
    private $supervisor = [];

    function __construct($conn)
    {
        $this->conn = $conn;
        $this->username = $_SERVER['PHP_AUTH_USER'];
        $this->groups = $this->setGroups();
        $this->setUserInfo($conn);
        $this->setStationInfo($conn);
        $this->setBands($conn);
    }

    function setUserInfo($conn)
    {
        $getInfoSQL = "SELECT * FROM users WHERE username = '$this->username'";
        $getInfoQ = mysqli_query($conn, $getInfoSQL);
        while ($info = $getInfoQ->fetch_assoc()) {
            $this->id = $info['userID'];
            $this->name = $info['name'];
        }
    }

    function setStationInfo($conn)
    {
        $this->attempts = [];
        $getAttemptSQL = "SELECT * FROM station_attempts WHERE memberID = $this->id";
        $getAttemptQ = mysqli_query($conn, $getAttemptSQL);
        while ($getAttempt = $getAttemptQ->fetch_assoc()) {
            // array_push($this->attempts, $getAttempt['evaluationID']);
            $this->attempts[$getAttempt['stationID']] .= $getAttempt['evaluationID'] . ',';
        }

        $this->progress = []; // must do this or the evaluators progress is used
        $this->supervisor = [];
        $getProgressSQL = "SELECT * FROM station_progress WHERE userID = $this->id";
        $getProgressQ = mysqli_query($conn, $getProgressSQL);
        while ($getProgress = $getProgressQ->fetch_assoc()) {
            $this->progress[$getProgress['stationID']] = $getProgress['progress'];
            $this->supervisor[$getProgress['stationID']] = $getProgress['evaluationID'];
        }
    }

    function setID($conn, $newID)
    {
        $setUsernameSQL = "SELECT username FROM users WHERE userID = $newID";
        $setUsernameQ = mysqli_query($conn, $setUsernameSQL);
        while ($u = $setUsernameQ->fetch_assoc()) {
            $this->setUsername($conn, $u['username']);
        }
    }

    function setUsername($conn, $newUsername)
    {
        $this->username = $newUsername;
        $this->groups = $this->setGroups();
        $this->setUserInfo($conn);
        $this->setStationInfo($conn);
        $this->setBands($conn);
    }

    function setGroups()
    {
        $groups = [];

        // group file path has to be hard coded because this file is included in different directories
        $AuthGroupFile = file("/var/www/html/mband.calpoly.edu/.htgroup");
        foreach ($AuthGroupFile as $line_num => $line) {
            if (preg_match("/(.*):(.*)$this->username/", $line, $matches)) {
                array_push($groups, $matches[1]);
            }
        }
        return $groups;
    }

    function setBands($conn)
    {
        $this->bands = [];
        $getBandsSQL = "SELECT * FROM member_groups WHERE userID = $this->id";
        $getBands = mysqli_query($conn, $getBandsSQL);
        while ($bands = $getBands->fetch_assoc()) {
            array_push($this->bands, $bands['groupID']);
        }
    }


    /*
     * GENERAL GETTERS
     */

    function getID()
    {
        return $this->id;
    }

    function getUsername()
    {
        return $this->username;
    }

    // return username if name is empty
    function getName()
    {
        return $this->name == "" ? $this->getUsername() : $this->name;
    }

    function getGroups()
    {
        return $this->groups;
    }

    function getAttempts()
    {
        return $this->attempts;
    }

    function getAttemptCount($stationID)
    {
        $arr = explode(',', $this->attempts[$stationID]);
        return sizeof($arr) - 1;
    }

    // returns best attempts for all stations
    function getProgress()
    {
        return $this->progress;
    }

    function getStationProgress($stationID)
    {
        // 0 = no attempt
        // 1 = attempted (failed)
        // 2 = success
        return $this->progress[$stationID] == null ? 0 : $this->progress[$stationID];
    }

    function checkGroup($group)
    {
        return in_array($group, $this->groups);
    }

    function getBands()
    {
        return $this->bands;
    }

    function checkBand($band)
    {
        // $band is the bandID
        return in_array($band, $this->bands);
    }

    function setEventAttendance($eid)
    {
        $checkSQL = "SELECT * FROM attendance WHERE eventID = $eid AND leaderID = $this->id";
        $res = mysqli_query($this->conn, $checkSQL);
        return mysqli_num_rows($res) > 0;
    }

    function changePassword($password)
    {
        // https://stackoverflow.com/questions/2994637/how-to-edit-htpasswd-using-php

        $new_password = password_hash($password, PASSWORD_DEFAULT);
        $htpasswd_file = '/var/www/html/mband.calpoly.edu/.htpasswd';

        //read the file into an array
        $lines = explode("\n", file_get_contents($htpasswd_file));

        //read the array and change the data if found
        $new_file = "";
        foreach ($lines as $line) {
            $line = preg_replace('/\s+/', '', $line); // remove spaces
            if ($line) {
                list($user, $pass) = explode(":", $line, 2);
                if ($user == $this->username) {
                    $new_file .= $user . ':' . $new_password . "\n";
                } else {
                    $new_file .= $user . ':' . $pass . "\n";
                }
            }
        }

        //save the information
        $f = fopen($htpasswd_file, "w") or die("couldn't open the file");
        fwrite($f, $new_file);
        fclose($f);
    }
}