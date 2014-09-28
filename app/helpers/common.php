<?php
function rest_success($data)
{
    return array('code' => 1000, 'data' => $data);
}

function rest_fail($data, $code = 500)
{
    return array('code' => $code, 'data' => $data);
}

function weekday_option()
{
    return array('周日', '周一', '周二', '周三', '周四', '周五', '周六');
}

function instant_order_state_option()
{
    return array(
        'draft' => '草稿',
        'waste' => '过时',
        'on_sale' => '待售',
        'paying' => '等待支付',
        'payed' => '已购买',
        'playing' => '打球中',
        'confirming' => '等待确认',
        'canceled' => '已取消',
        'expired' => '过期未售',
        'terminated' => '终止',
        'finish' => '完成',
    );
}

function weekday($timestamp)
{
    $weekday = weekday_option();
    return $weekday[date('w', $timestamp)];
}

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
            $noAnchorUrl = $noAnchorUrl . '&app_user_id=' . $AppUserID . '&app_id=' . $AppID;
        } else {
            $noAnchorUrl = $noAnchorUrl . '?app_user_id=' . $AppUserID . '&app_id=' . $AppID;
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


/**
 * option列表 - 用户权限
 */
function option_user_privilege($sLanguage = 'cn')
{
    if ($sLanguage == 'en')
        return array(1 => "member", 2 => "vip");
    else
        return array(1 => "普通会员", 2 => "vip会员");
}

function user_roles(User $user = null)
{
    static $roles = null;
    if ($roles === null) {
        empty($user) && $user = Auth::getUser();
        if (!empty($user)) {
            $roles = $user->roles;
            if (count($roles) <= 0) {
                $role = new Role();
                $role->role_id = ROLE_USER;
                $roles[] = $role;
            }
        }
    }
    return $roles;
}

/**
 * @param Hall $hall
 * @return HallImage|mixed|null
 */
function hall_head(Hall $hall)
{
    $hallImage = null;
    if ($hall instanceof Hall) {
        if ($hall->Envelope) {
            $hallImage = $hall->Envelope;
        } else if ($hall->HallImages->count() > 0) {
            $hallImage = $hall->HallImages->first();
        }
    }
    return $hallImage;
}

function no_money_array()
{
    return array(
        'needPay' => 0, 'balance' => 0, 'needRecharge' => 0, 'adviseForwardUrl' => '', 'weChatPayUrl' => ''
    );
}

function no_money_generate_url(&$no_money_array, Recharge $recharge)
{
    $no_money_array['adviseForwardUrl'] = url_wrapper(sprintf('/recharge/alipay?recharge_id=%s', $recharge->id));
    $no_money_array['weChatPayUrl'] = sprintf('/recharge/wechatpay?recharge_id=%s', $recharge->id);
}

function adjustTimeStamp($models)
{
    foreach ($models as $model) {
        empty($model->updated_at) && $model->updated_at = \Carbon\Carbon::now();
        empty($model->created_at) && $model->created_at = \Carbon\Carbon::now();
    }
}

function user_id(User $user = null)
{
    empty($user) && $user = Auth::getUser();
    return empty($user) ? null : $user->user_id;
}

function debug()
{
    return Config::get('app.debug');
}

function app_id()
{
    $sHost = Request::getHost();
    if (isset($_ENV['DOMAIN_WE_CHAT']) && ($sHost == $_ENV['DOMAIN_WE_CHAT'])) {
        return APP_WE_CHAT;
    } else {
        return APP_WEB_PC;
    }
}

/**
 * @return \Illuminate\Database\Eloquent\Model|mixed|null|static
 */
function user_app()
{
    static $app = null;
    $appId = app_id();
    if (($app === null) && !Auth::guest() && ($appId != APP_WEB_PC)) {
        $user = Auth::getUser();
        $app = RelationUserApp::whereAppId($appId)->whereUserId($user->user_id)->first();
    }
    return $app;
}

function app_user_id()
{
    $app = user_app();
    return $app ? $app->app_user_id : '';
}