<?php
require_once '../models/mcreateedit.php';

class Ccreateedit extends MCreateEdit {
    function mysql_validate($input) {
        foreach($input as $key => $value) {                                // Escape all SQL tags
            $row[$key] = mysqli_escape_string($value);
        }
        return $row;
    }

    function get_row($tbl1, $tbl2, $tbl3, $modelfunc,$key) {// Return SQL row from '$tbl' by '$key' Use model function name '$modelfunc'
        $rez = $this->$modelfunc($tbl1, $tbl2, $tbl3, $key);
        $out = mysqli_fetch_assoc($rez);
        return $out;
    }

    function get_rows($tbl1, $tbl2, $modelfunc, $filter, $counts, $popular) {// Return Assoc SQL array from '$tbl' Use model function '$modelfunc'
        $rez = $this->$modelfunc($tbl1, $tbl2, $filter, $counts, $popular);
        while ($row = mysqli_fetch_assoc($rez)) {
            $out[$row['id']] = $row;
        }
        return $out;
    }

    function get_count_rows($tbl1, $tbl2, $filter) {                                         // Return count rows
        $rez = $this->select_count_rows($tbl1, $tbl2, $filter);
        $out = mysqli_fetch_array($rez);
        return $out[0];
    }

    public function getExist ($tbl, $post, $col) {
        $rez = $this->selectExist($tbl, $post, $col);
        $out = mysqli_fetch_array($rez);
        return $out[0];
    }

    public function getUser($tbl, $id) {
        $rez = $this->selectUser($tbl, $id);
        $out = mysqli_fetch_assoc($rez);
        return $out;
    }

    public function findUser($tbl, $post) {
        $rez = $this->selectFindUser($tbl, $post);
        $out = mysqli_fetch_assoc($rez);
        return $out;
    }

    function get_unique($tbl, $col) {
        $rez = $this->select_unique($tbl, $col);
        while ($row = mysqli_fetch_assoc($rez)) {
            $out[$row[$col]] = $row[$col];
        }
        return $out;
    }

    function get_item_type($tbl, $id) {
        $rez = $this->select_item_type($tbl, $id);
        $out = mysqli_fetch_assoc($rez);
        return $out['type'];
    }

    public  function getCol($tbl, $col, $nameid) {
        $rez = $this->selectCol($tbl, $col, $nameid);
        while ($row = mysqli_fetch_assoc($rez)) {
            $out[$row[$nameid]] = $row[$col];
        }
        return $out;
    }

    public function getImg($tbl, $id) {
        $rez = $this->selectImg($tbl, $id);
        $out = mysqli_fetch_assoc($rez);
        return $out;
    }

    public function getChangedImgs($tbl, $id, $delta) {
        $rez = $this->selectChangedImgs($tbl, $id, $delta);
        while ($row = mysqli_fetch_assoc($rez)) {
            $out[$row['id']] = $row;
        }
        return $out;
    }

    public function addUser($tbl, $post) {
        // Create Error List
        $errList = [];
        $message = null;

        // Escape backspaces before and after
        foreach ($post as $key => $value) {
            $post[$key] = trim($value);
        }

        // Validate password
        if($post['pass'] != $post['confirm_pass']) {
            $errList[] = 'Passwords not equal';
        }

        // Validate email
        if(!filter_var($post['email'], FILTER_VALIDATE_EMAIL)) {
            $errList[] = 'Wrong e-mail address';
        }

        // Validate exist login
        $loginCount = $this->getExist($tbl, $post, 'login');
        if($loginCount > 0) {
            $errList[] = 'Login already exist';
        }

        // Validate exist pass
        $emailCount = $this->getExist($tbl, $post, 'email');
        if($emailCount > 0) {
            $errList[] = 'Email already exist';
        }

        // Password to Hash
        $post['pass'] = md5($post['pass']);

        // Send data
        if(count($errList) == 0) {
            // insert into users table
            $this->insertUser($tbl, $post);

            // Send the activation letter
            $userID = mysqli_fetch_assoc($this->selectLasdID($tbl, 'id'));
            $userHash = md5($userID['id'].$post['login'].$post['pass']);
            $userLink = $_SERVER['HTTP_REFERER'].'?id='.$userID['id'].'&link='.$userHash;
            $this->sendEmail($post['email'], 'Activation', $userLink);
            $message = 'You are succesfuly registered<br>Please go to your email for activate account'.'<br>';
        }
        else {
            $message = 'Registration failed:<br>';
            $ierr = 0;
            foreach ($errList as $err) {
                $ierr++;
                $message .= $ierr.'. '.$err.'<br>';
            }
        }

        return $message;
    }

    public function sendEmail($To, $Subject, $Message ) {
        $to      = $To;
        $subject = $Subject;
        $message = $Message;
        $headers = 'From: webmaster@example.com' . "\r\n" .
            'Reply-To: webmaster@example.com' . "\r\n" .
            'X-Mailer: PHP/' . phpversion();

        mail($to, $subject, $message, $headers);
    }

    public function delAllFiles($dir) {
        if(file_exists($dir)) {
            if ($objs = glob($dir."/*")) {
                foreach($objs as $obj) {
                    is_dir($obj) ? removeDirectory($obj) : unlink($obj);
                }
            }
            rmdir($dir);
        }
    }

    function get_item($tbl, array $TypeList, array $TblCols, $ItemType, $id) {
        // Create SELECT string
        $select_data = [];
        foreach ($TypeList[$ItemType] as $tag_tbl) {
            foreach ($TblCols[$tag_tbl] as $col) {
                $select_data[] = "$tag_tbl.$col";
            }
        }
        $sql_select = implode(', ', $select_data);

        // Create JOIN
        $join_data = [];
        foreach ($TypeList[$ItemType] as $tag_tbl) {
            if(count($TblCols[$tag_tbl]) > 1) {
                $join_data[] = [
                    'type' => 'LEFT',
                    'base_table' => $tbl,
                    'join_table' => $tbl.'_'.$tag_tbl.'s',
                    'base_table_key' => 'id',
                    'join_table_key' => 'product_id',
                ];
                $join_data[] = [
                    'type' => 'INNER',
                    'base_table' => $tbl.'_'.$tag_tbl.'s',
                    'join_table' => $tag_tbl,
                    'base_table_key' => $tag_tbl.'_id',
                    'join_table_key' => 'id',
                ];
            }
            else {
                $join_data[] = [
                    'type' => 'LEFT',
                    'base_table' => $tbl,
                    'join_table' => $tag_tbl,
                    'base_table_key' => 'id',
                    'join_table_key' => 'product_id',
                ];
            }
        }

        foreach ($join_data as $join) {
            $joins[] = $join['type'].' JOIN '.$join['join_table']
                .' ON '.$join['base_table'].'.'.$join['base_table_key']
                .' = '.$join['join_table'].'.'.$join['join_table_key'];
        }
        $sql_join = implode(' ', $joins);

        $rez = $this->select_item($tbl, $sql_select, $sql_join, $id);
        $out = mysqli_fetch_assoc($rez);
        return $out;
    }
}

$ccreateedit = new CcreateEdit();