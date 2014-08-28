<?php
function rest_success($data){
    return array('code' => 1000 , 'data' => $data);
}

function weekday_option(){
    return array('周日', '周一', '周二', '周三', '周四', '周五', '周六');
};


function url_wrapper($url)
{
    // 获取到微信请求里包含的几项内容
    $AppUserID = Input::get('app_user_id');
    $AppID = Input::get('app_id');
    if($AppUserID)
    {
        $param = strstr($url,"?");
        if($param){

            $url = $url.'&app_user_id='.$AppUserID.'&app_id='.$AppID;

        }
        else{
            $url = $url.'?app_user_id='.$AppUserID.'&app_id='.$AppID;
        }

    }
    return $url;

}