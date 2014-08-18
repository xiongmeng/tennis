<?php
function rest_success($data){
    return array('code' => 1000 , 'data' => $data);
}

function url_wrapper($url)
{
    // 获取到微信请求里包含的几项内容
    $AppUserID = Input::get('app_user_id');
    if($AppUserID)
    {
        $param = strstr($url,"?");
        if($param){
            $url = $url.'&app_user_id='.$AppUserID;

        }
        else{
            $url = $url.'?app_user_id='.$AppUserID;
        }

    }
    return $url;

}