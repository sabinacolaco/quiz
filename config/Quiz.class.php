<?php
session_start();
include('Constants.php');
include('Database.php');

class Quiz {
    
    private $db;
    private $totalQuestions = NUMBER_OF_QUESTIONS;

    public function __construct()
    {
        if (!isset($this->db)) {
            try {
                $this->db = new mysqli($GLOBALS['host'], $GLOBALS['username'], $GLOBALS['password'], $GLOBALS['db_name']);
            } catch (Exception $e) {
                $error = $e->getMessage();
                echo $error;
            }
        }
    }


    /**
    * showQuestions function.
    * List random questions on load of the page
    * Called from index.php
    * Parameters: array $questsArr - answered questions 
    * @return $resultArr
    */
    public function showQuestions(array $questsArr) : array
    {
        $resultArr = [];
        if (!empty($questsArr)) {
            $questionIDs = implode(',', array_keys($questsArr));
            $sql = "SELECT * FROM questions WHERE quest_id IN (" . $questionIDs . ") order by FIELD(quest_id, " . $questionIDs . ")";
        }
        else
            $sql = "SELECT * FROM questions ORDER BY rand() LIMIT " . $this->totalQuestions;
            
        try {
            $result = $this->db->query($sql);
            if ($result === FALSE) {
                throw new Exception($this->db->error);
            }

            if ($result->num_rows > 0) {
                while($row = $result->fetch_assoc()) {
                    $resultArr[] = $row;
                }
            }
            $result->close();
            return $resultArr;

        } catch(Exception $e) {
            echo 'Problem with: '.$sql;
            echo $e->getMessage();
        }
    }


    /**
    * showOptions function.
    * List the options for a particular question - called from index.php
    * Parameters: int $qid - quest_id to get the options for that particular question
    * @return $resultArr
    */
    public function showOptions($qid)
    {
        $resultArr = [];
        
        try
        {
            $sql = "SELECT * FROM answers WHERE quest_id=?";
            $stmt = $this->db->prepare($sql); 
            $stmt->bind_param("i", $qid);
            $stmt->execute();
            $result = $stmt->get_result(); 
    
            if ($result->num_rows > 0) {
                while($row = $result->fetch_assoc()) {
                    $resultArr[] = $row;
                }
            }
        }
        catch( mysqli_sql_exception $e ) {
            echo $e->getMessage();
            die;
        }
       
        return $resultArr;
    }


    /**
    * checkAnswers function.
    * List the options for particular question - called from index.php
    * @return $result_array
    */
    public function checkAnswers($selected)
    {
        $result_array = [];
        $score = 0;
        $questionids = implode(',', array_keys($selected));
        $sql = "SELECT quest_id, correct_answer FROM questions WHERE quest_id IN (" . $questionids . ") order by FIELD(quest_id,".$questionids.")";
        $result = $this->db->query($sql);
        if ($result->num_rows > 0) {
            while($row = $result->fetch_assoc()) {
            
                if ($row['correct_answer'] == $selected[$row['quest_id']]) {
                    $result_array[$row['quest_id']] = $row['correct_answer'];
                }
                else {
                    $result_array[$row['quest_id']] = $row['correct_answer'];
                }
            }
        }

        return $result_array;
    }


    /**
    * getQuestionsOptions function.
    * List random questions
    * Called from index.php
    * @return $resultArray
    */
    public function getQuestionsOptions()
    {
        $resultArray = [];
        $sql = "SELECT * FROM questions ORDER BY rand() LIMIT " . $this->totalQuestions;
            
        try {
            $result = $this->db->query($sql);
            if ($result === FALSE) {
                throw new Exception($this->db->error);
            }

            if ($result->num_rows > 0) {
                $i = 0;
                while($row = $result->fetch_assoc()) {
                    $resultArray[$i]['questions'] = $row;
                    $sqlans = "SELECT * FROM answers WHERE quest_id = ".$row['quest_id'];
                    $resultans = $this->db->query($sqlans);
                    $resultArray[$i]['options'] = [];
                    if ($resultans->num_rows > 0) {
                        while($rowans = $resultans->fetch_assoc()) {                    
                            extract($rowans);
                            $opt_record=array(
                            "ans_id" => $ans_id,
                            "answer" => $answer
                            );
                            array_push($resultArray[$i]['options'], $opt_record);
                        }
                    }                    
                    $i++;
                }
            }
            $result->close();

            return $resultArray;

        } catch(Exception $e) {
            echo 'Problem with: '.$sql;
            echo $e->getMessage();
        }
    }


    /**
    * showResultQuestions function.
    * List random questions on load of the page
    * Called from index.php
    * Parameters: array $questsArr - answered questions 
    * @return $resultArr
    */
    public function showResultQuestions(array $questsArr) : array
    {
        $resultArr = [];
        if (!empty($questsArr)) {
            $questionIDs = implode(',', ($questsArr));
            $sql = "SELECT * FROM questions WHERE quest_id IN (" . $questionIDs . ") order by FIELD(quest_id, " . $questionIDs . ")";
        }
            
        try {
            $result = $this->db->query($sql);
            if ($result === FALSE) {
                throw new Exception($this->db->error);
            }

            if ($result->num_rows > 0) {
                while($row = $result->fetch_assoc()) {
                    $resultArr[] = $row;
                }
            }
            $result->close();
            
            return $resultArr;

        } catch(Exception $e) {
            echo 'Problem with: '.$sql;
            echo $e->getMessage();
        }
    }
    
    
    /**
    * ajaxSendResult function.
    * Checks for corresponding questions id
    * Parameters: $selOptionsArr (selected answer array)
    * @return $result_array
    */
    public function ajaxSendResult($selOptionsArr)
    {
        $resultQuestArr = [];
        $correctCount = 0;
        foreach($selOptionsArr['resultArray'] as $optid) {
            
            $sql = "SELECT quest_id FROM answers WHERE ans_id = " . $optid . " LIMIT 1";
            $result = $this->db->query($sql);
            if ($result->num_rows > 0) {
                
                while($row = $result->fetch_assoc()) {

                    array_push($resultQuestArr, $row['quest_id']);                    
                    $chksql = "SELECT quest_id FROM questions WHERE correct_answer = ".$optid . " AND quest_id = ".$row['quest_id'] . " LIMIT 1";
                    $chkresult = $this->db->query($chksql);
                    if($chkresult->num_rows == 1) {
                        $correctCount++;
                    }
                }
            }
        }
        $_SESSION['questions'] = $resultQuestArr;
        $_SESSION['answers'] = $selOptionsArr;
        $_SESSION['correctCount'] = $correctCount;
        
        return 1;
    }

    
    /**
    * validateData function.
    * Valdates the data to be inserted
    * @return $data - validated data
    */
    private function validateData($data)
    {
        $data = trim($data);
        $data = stripslashes($data);
        $data = strip_tags($data);
        $data = htmlspecialchars($data);
        
        return $data;    
    }
    

    /**
    * getPercentage function.
    * Called from result.php
    * Calculates the percentage and returns the answer
    * Parameters: $number
    * @return $percentage - interger
    */
    public function getPercentage($number = 0)
    {
        $percentage = 0;
        if (!empty($number)) {
            $percentage = ($number / NUMBER_OF_QUESTIONS) * 100;
        }
        
        return $percentage;
    }
    

    /**
    * showPerformance function.
    * Called from result.php
    * Check the percentage and returns the performance
    * Parameters: $percentage
    * @return $performance - text
    */
    public function showPerformance($percentage = 0)
    {
        $performance = '-';
        if (!empty($percentage)) {
            if($percentage == 100)
                $performance = 'Excellent';
            elseif($percentage >=80 && $percentage <100)
                $performance = 'Good';
            elseif($percentage >=60 && $percentage <80)
                $performance = 'Average';
            elseif($percentage >=40 && $percentage <60)
                $performance = 'Fair';
            elseif($percentage >=20 && $percentage <40)
                $performance = 'Poor';
            elseif($percentage <20)
                $performance = 'Fail';
        }
        
        return $performance;
    }
    

    /**
    * setTimer function.
    * Called from multipage.php
    * Returns time
    * @return $time1 - text
    */
    public function setTimer()
    {
        date_default_timezone_set('Europe/London');
        
        if(!isset($_SESSION["end_time"])){
            return "00:00:00";
        }
        else {
            $time1=gmdate("H:i:s", strtotime($_SESSION["end_time"]) - strtotime(date("Y-m-d H:i:s")));
            if (strtotime($_SESSION["end_time"])<strtotime(date("Y-m-d H:i:s"))) {
                return "00:00:00";
            }
            else {
                return $time1;
            }
        }
    }
    
    
}
?>