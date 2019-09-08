<?php

class evaluation
{
    private $id;
    private $user;

    private $progEvalID; // evalID in station progress
    private $evals = [];
    private $leaders = [];

    function __construct($conn, $id, $user)
    {
        $this->id = $id;
        $this->user = $user;
        $getEvaluationSQL = "SELECT * FROM station_attempts INNER JOIN users ON station_attempts.leaderID = users.userID "
            . "WHERE stationID = $this->id AND memberID = $this->user ORDER BY attemptTime DESC"; // most recent first
        $getEvaluations = mysqli_query($conn, $getEvaluationSQL);
        while ($eval = $getEvaluations->fetch_assoc()) {
            if ($eval['hidden'] == 0) { // make sure this attempt was not hidden todo make this work better
                $this->leaders[$eval['leaderID']] = array('username' => $eval['username'], 'name' => $eval['name']);
                $this->evals[$eval['evaluationID']] = array('passed' => $eval['passedChecks'],
                    'failed' => $eval['failedChecks'], 'time' => $eval['attemptTime'], 'evaluator' => $eval['leaderID']);
            }
        }

        $getProgressSQL = "SELECT * FROM station_progress WHERE userID = $this->user AND stationID = $this->id";
        $getProgressQ = mysqli_query($conn, $getProgressSQL);
        while ($progress = $getProgressQ->fetch_assoc()) {
            $this->progEvalID = $progress['evaluationID'];
        }
    }

    function getProgID()
    {
        return $this->progEvalID;
    }

    function getEvalCount()
    {
        return sizeof($this->evals);
    }

    function getProgressEvaluator()
    {
        $leaderID = $this->evals[$this->progEvalID]['evaluator'];
        $leader = $this->leaders[$leaderID];
        return $leader['name'] == null ? $leader['username'] : $leader['name'];
    }

    function itemAttempts($item)
    {
        $attempt = null;
        $attempt->passed = [];
        $attempt->failed = [];
        foreach ($this->evals as $eid => $eval) {
            if (in_array($item, $eval['passed'])) {
                array_push($attempt->passed, $item);
            } else if (in_array($item, $eval['failed'])) {
                array_push($attempt->failed, $item);
            }
        }
        return $attempt;
    }
}