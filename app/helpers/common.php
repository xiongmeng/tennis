<?php
function rest_success($data)
{
    return array('code' => 1000, 'data' => $data);
}

function weekday_option()
{
    return array('周日', '周一', '周二', '周三', '周四', '周五', '周六');
}

;


function url_wrapper($url)
{
    // 获取到微信请求里包含的几项内容
    $AppUserID = Input::get('app_user_id');
    $AppID = Input::get('app_id');
    if ($AppUserID) {
        $anchor = strstr($url, "#");
        $noAnchorUrl = str_replace($anchor, '', $url);

        $param = strstr($noAnchorUrl, "?");
        if ($param) {
            $noAnchorUrl = $noAnchorUrl. '&app_user_id=' . $AppUserID . '&app_id=' . $AppID;
        } else {
            $noAnchorUrl = $noAnchorUrl. '?app_user_id=' . $AppUserID . '&app_id=' . $AppID;
        }

        $anchor && $noAnchorUrl .= $anchor;
        return $noAnchorUrl;
    }
    return $url;

}

function balance($userId = null, $refresh = false)
{
    static $balance = null;
    if ($balance === null || $refresh) {
        if ($userId === null) {
            $user = Auth::getUser();
            if (!empty($user)) {
                $userId = $user->user_id;
            }
        }

        if (!empty($userId)) {
            $account = Finance::ensureAccountExisted($userId, \Sports\Constant\Finance::PURPOSE_ACCOUNT);
            $balance = intval($account->getBalance());
        } else {
            $balance = 0;
        }
    }
    return $balance;
}

function points($userId = null, $refresh = false)
{
    static $balance = null;
    if ($balance === null || $refresh) {
        if ($userId === null) {
            $user = Auth::getUser();
            if (!empty($user)) {
                $userId = $user->user_id;
            }
        }

        if (!empty($userId)) {
            $account = Finance::ensureAccountExisted($userId, \Sports\Constant\Finance::PURPOSE_POINTS);
            $balance = intval($account->getBalance());
        } else {
            $balance = 0;
        }
    }
    return $balance;
}

