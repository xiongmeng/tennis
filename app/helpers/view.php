<?php

function href_hall_detail($hall_id, $hall_name){
    return sprintf("<a target='_blank' href='/hall/detail/%s'>%s</a>", $hall_id, $hall_name);
}

function href_user_detail($user_id, $user_name){
    return sprintf("<a target='_blank' href='/user/detail/%s'>%s</a>", $user_id, $user_name);
}

function href_notify_create($events, $object_id, $name){
    $events = is_array($events) ? implode(',', $events) : $events;
    return sprintf("<a target='_blank' href='/notify/create?events=%s&object=%s'>%s</a>", $events, $object_id, $name);
}

function display_time_interval($start, $end){
    return sprintf('%02d-%02d', $start, $end);
}