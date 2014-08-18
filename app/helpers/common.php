<?php
function rest_success($data){
    return array('code' => 1000 , 'data' => $data);
}