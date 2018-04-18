<?php
require_once '../models/mcreateedit.php';
class Citem extends Mcreateedit
{
    public $select_data = [];
    public $join_data = [];
    public $sql_select = [];
    public $sql_join = '';
    protected $Tags = [];    // reset this array data in child class. Will be set data from child class
    protected $formElements = [];

    public function setSQLitem($tbl, $id) {
        // Create SELECT string
        foreach ($this->Tags as $key => $value) {
            foreach ($value as $col) {
                $this->select_data[] = "$key.$col";
            }
        }
        $this->sql_select = implode(', ', $this->select_data);

        // Create JOIN
        foreach ($this->Tags as $key => $value) {
            if(count($value) > 1) {
                $this->join_data[] = [
                    'type' => 'LEFT',
                    'base_table' => $tbl,
                    'join_table' => $tbl.'_'.$key.'s',
                    'base_table_key' => 'id',
                    'join_table_key' => 'product_id',
                ];
                $this->join_data[] = [
                    'type' => 'INNER',
                    'base_table' => $tbl.'_'.$key.'s',
                    'join_table' => $key,
                    'base_table_key' => $key.'_id',
                    'join_table_key' => 'id',
                ];
            }
            else {
                $this->join_data[] = [
                    'type' => 'LEFT',
                    'base_table' => $tbl,
                    'join_table' => $key,
                    'base_table_key' => 'id',
                    'join_table_key' => 'product_id',
                ];
            }
        }

        foreach ($this->join_data as $join) {
            $joins[] = $join['type'].' JOIN '.$join['join_table']
                .' ON '.$join['base_table'].'.'.$join['base_table_key']
                .' = '.$join['join_table'].'.'.$join['join_table_key'];
        }
        $this->sql_join = implode(' ', $joins);

        $rez = $this->db($tbl, $this->sql_select, $this->sql_join, $id);
        return $rez;
    }

    protected function db($tbl, $sql_select, $sql_join, $id) {
        $rez = $this->select_item($tbl, $sql_select, $sql_join, $id);
        $out = mysqli_fetch_assoc($rez);
        return $out;
    }

    public function getItemImg($tbl, $id) {
        $rez = $this->selectItemImg($tbl, $id);
        while ($row = mysqli_fetch_assoc($rez)) {
            $out[$row['id']] = $row;
        }
        return $out;
    }

    public function getTablesDel($id) {
        foreach ($this->formElements as $key => $value) {
            $this->delKeyRow($value['table'], 'product_id', $id);
        }
    }

    public function addItem($tbl, $post) {
        // Insert Data to the main table and get it ID
        $post = $this->mysql_validate($post);
        $this->insertItem($tbl, $post);
        $id = mysqli_fetch_assoc($this->selectLasdID($tbl, 'id'));

        // Upload images
        if($_FILES['img']) {
            // check or create Directory path
            $dirPath = '../images/product_'.$id['id'];
            if(!(is_dir($dirPath))) {
                mkdir($dirPath);
            }

            // Rename all files and upload to the Directory Path and put data into table
            foreach ($_FILES['img']['name'] as $key => $value) {
                // data for upload
                $tmpFile = $_FILES['img']['tmp_name'][$key];
                $usrFile  =$dirPath.'/'.$id['id'].'_'.$key.'.'.pathinfo($value)['extension'];
                move_uploaded_file($tmpFile, $usrFile);

                // data fpr table 'images'
                $dataImg = [
                    'product_id' => $id['id'],
                    'delta' => $key,
                    'size' => $_FILES['img']['size'][$key] / 1000,
                    'path' => $usrFile,
                ];

                // Check if exist the file put data into table
                if($_FILES['img']['name'][$key]) {
                    $this->insertImg('images', $dataImg);
                }

            }
        }

        // Create Array SQL query for Tags add
        $insert = [];
        foreach ($post as $key => $value) {
            if($key != 'fileitem' && $key != 'classname' && $key != 'model' && $key != 'type' && $key != 'description' && $key != 'price' && $key != 'create') {
                $insert[] = "INSERT INTO `{$this->formElements[$key]['table']}` (`product_id`, `{$this->formElements[$key]['field']}`) 
                            VALUES ({$id['id']}, '{$value}'); ";
            }
        }

        // Insert tags in tables for the new Item
        foreach ($insert as $sql) {
            $this->sql($sql);
        }
    }

    public function updateSQL($tbl, $post, $id) {
        //validate post
        $post = $this->mysql_validate($post);

        // update data tables
        $arr = $this->formElements;
        $sql = ["UPDATE `{$tbl}` SET name = '{$post['model']}', description = '{$post['description']}', price = '{$post['price']}' WHERE id = '{$id}'"];
        foreach ($arr as $key => $value) {
            $sql[] = "UPDATE `{$value['table']}` SET {$value['field']} = {$post[$key]} WHERE product_id = '{$id}'";
        }

        // update images and images table
        if($_FILES) {                          // $_FILES always exist if form contain input type file
            // check or create Directory path
            $dirPath = '../images/product_'.$id;
            if(!(is_dir($dirPath))) {
                mkdir($dirPath);
            }

            // Rename all files and upload to the Directory Path and put data into table
            foreach ($_FILES['img']['name'] as $key => $value) {
                // data for upload
                $delta = $key;
                $tmpFile = $_FILES['img']['tmp_name'][$key];
                $usrFile = $dirPath.'/'.$id.'_'.$delta.'.'.pathinfo($value)['extension'];
                if(file_exists($usrFile)) {
                    $result = glob($dirPath.'/'.$id.'_*'.'.'.pathinfo($value)['extension']);
                    $fcount = count($result);
                    $delta = $fcount;
                    $usrFile = $dirPath.'/'.$id.'_'.$delta.'.'.pathinfo($value)['extension'];
                }

                move_uploaded_file($tmpFile, $usrFile);

                // data fpr table 'images'
                $dataImg = [
                    'product_id' => $id,
                    'delta' => $delta,
                    'size' => $_FILES['img']['size'][$key] / 1000,
                    'path' => $usrFile,
                ];

                if($_FILES['img']['name'][$key]) {                  // Check exist file
                    $this->insertImg('images', $dataImg);
                }

            }
        }

        // Insert tags in tables for the new Item
        foreach ($sql as $s) {
            $this->sql($s);
        }
    }

    function mysql_validate($input) {
        foreach($input as $key => $value) {
            $row[$key] = addslashes($value);
        }
        return $row;
    }
}