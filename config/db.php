<?php
    require_once '../config/config.php';

    class DB extends Config {
        /**
         * Function use Procedure style to the DB connect and return result
         * @param $query
         * @return bool|mysqli_result
         */
        function sql2($query) {
            $link = mysqli_connect($this->DB_HOST, $this->DB_USER, $this->DB_PASS, $this->DB_NAME);             // Connect to the DB
            if (!$link) {
                printf("Can't connect to the Data Base. Error code: %s\n", mysqli_connect_error());     // Output the Error if can't connect
                exit;
            }

            mysqli_query($link, "set names utf8") or die("set name utf8 failed");                        // Set DB codding UTF8
            $result = mysqli_query($link, $query);                                                             // Send the Request and Get it to the variable
            if($result) {
                return $result;                                                                                // If Result is success return it
            }
            else {
                die('Database query filed: '.mysqli_error($link));                                             // Else return the Error
            }

            mysqli_free_result($result);                                                                       // Dispose the resources
            mysqli_close($link);                                                                               // Close the DB connection
        }

        /**
         * Function use object model to the DB connect and return Query result
         * @param $query
         * @return bool|mysqli_result
         */
        public function sql($query) {
            // create connect to DB
            $conn = new mysqli($this->DB_HOST, $this->DB_USER, $this->DB_PASS, $this->DB_NAME);
            if($conn->connect_error) {
                die('Can\'t connect to the Data Base. Error code: ' . $conn->connect_error);
            }

            // send query and recive to the $result
            $result = $conn->query($query);
            if($result) {
                return $result;
            }
            else {
                die('Database query filed: '. $conn->error);
            }

            // close $result and $connect
            $result->close();
            $conn->close();
        }
    }

    $db = new DB();
?>