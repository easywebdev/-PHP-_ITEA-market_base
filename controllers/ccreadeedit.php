<?php
require_once '../models/mcreateedit.php';

class CCreateEdit extends MCreateEdit {
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

$ccreateedit = new CCreateEdit();