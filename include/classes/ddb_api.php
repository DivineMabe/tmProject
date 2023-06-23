<?php
class ddb_api {
  
    // Update the records of the table $table_name matching an $id_field / $id_value, changing the value of fields defined in $fields_to_update_ar to $fields_to_update_values_ar
    // Example: ddb_api::update_record('customers', 'id_customer', 10, ['address_customer', 'city_customer'], ['2299 Piedmont Ave.', 'Berkeley']
    static public function update_record($table_name, $id_field, $id_value, $fields_to_update_ar, $fields_to_update_values_ar)
    {
        global $conn, $quote, $enable_revision_table, $current_user;
    
        $in_transaction = $conn->inTransaction();
    
        if ($in_transaction === false){
            begin_trans_db();
        }
    
        $sql = "UPDATE ".$quote.$table_name.$quote." SET ";
    
        $values_to_bind = array();
        foreach ($fields_to_update_ar as $key => $value){
            $values_to_bind[$value] = $fields_to_update_values_ar[$key];
            $sql .= $quote.$value.$quote." = :".$value.", ";
        }
    
        $sql = substr($sql, 0, -2);
    
        if (count($values_to_bind) === 0){
            die('ddb_update_record: wrong parameters, no fields to update');
        }
    
        $sql .= " WHERE ".$quote.$id_field.$quote." = :id_value";
        $res_prepare = prepare_db($conn, $sql);
    
        $bind_values = '';
        foreach ($values_to_bind as $key => $value){
            $res_bind = bind_param_db($res_prepare, ':'.$key, $value);
            $bind_values .= ':'.$key.'->'.$value.'//';
        }
        $res_bind = bind_param_db($res_prepare, ':id_value', $id_value);
        $bind_values .= ':id_value->'.$id_value.'//';
    
        log_operation('update', $sql.$bind_values);
    
        if ($enable_revision_table == 1){
            register_revision($table_name, $id_field, $id_value, $current_user, 'update');
        }
    
        $res = execute_prepared_db($res_prepare,0);
    
        if ($in_transaction === false){
            complete_trans_db();
        }
    }

    // Returns the number of records matching a specified field name / field value pair
    // Example: ddb_api::count_records('customers','city_customer','Bologna')
    static public function count_records($table_name, $field_name, $field_value)
    {
        global $conn, $quote;
    
        $sql = "SELECT COUNT(*) FROM ".$quote.$table_name.$quote." WHERE ".$quote.$field_name.$quote." = :field_value";
    
        $res_prepare = prepare_db($conn, $sql);
    
        $res_bind = bind_param_db($res_prepare, ':field_value', $field_value);
    
        $res = execute_prepared_db($res_prepare);
    
        $row = fetch_row_db($res_prepare);
    
        return $row[0];
    }
    
    // Returns the details of the (unique) record matching an id_field / id_value pair 
    // Example: ddb_api::get_record_details('customers','id','10')
    public static function get_record_details($table_name, $id_field, $id_value)
    {
        global $conn, $quote;
    
        $sql = "SELECT * FROM ".$quote.$table_name.$quote." WHERE ".$quote.$id_field.$quote." = :id_value";
        $res_prepare = prepare_db($conn, $sql);
    
        $res_bind = bind_param_db($res_prepare, ':id_value', $id_value);
    
        $res = execute_prepared_db($res_prepare,0);
    
        $num_rows = 0;
        while($row = fetch_row_db($res_prepare,1)){
            $record_details = $row;
            $num_rows++;
        }
    
        if ($num_rows !== 1){
            echo 'Unexpected error, more than one records having same ID';
            exit;
        }
    
        return $record_details;
    }
    
}