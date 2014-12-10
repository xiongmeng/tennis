<?php
function rest_success($data)
{
    return array('code' => 1000, 'data' => $data);
}

function rest_fail($msg, $code = 500, $data = null)
{
    return array('code' => $code, 'msg' => $msg, 'data' => $data);
}

function weekday_option()
{
    return array('周日', '周一', '周二', '周三', '周四', '周五', '周六');
}

function option_published_halls()
{
    return Hall::whereStat(HALL_STAT_PUBlISH)->remember(CACHE_HOUR)->get(array('id', 'name'));
}

function option_seeking_state()
{
    return array(
        SEEKING_STATE_DRAFT => '草稿',
        SEEKING_STATE_CLOSED => '已关门',
        SEEKING_STATE_OPENED => '已开门',
        SEEKING_STATE_FULLED => '已满员',
        SEEKING_STATE_COMPLETED => '已结束',
        SEEKING_STATE_CANCELED => '已取消',
        SEEKING_STATE_FULL_CHECKING => '满员检测中'
    );
}

function option_seeking_order_state()
{
    return array(
        SEEKING_ORDER_STATE_DISPOSING => '处理中',
        SEEKING_ORDER_STATE_PAYING => '支付中',
        SEEKING_ORDER_STATE_PAYED => '已支付',
        SEEKING_ORDER_STATE_PAY_FAILED => '支付失败',
        SEEKING_ORDER_STATE_CANCELED => '已取消',
        SEEKING_ORDER_STATE_COMPLETED => '已结束',
    );
}

function option_tennis_level()
{
    return array(
        1 => '1.0',
        2 => '2.0',
        3 => '3.0',
        4 => '3.5',
        5 => '4.0',
        6 => '4.5',
        7 => '5.0',
        8 => '更高',
    );
}

function option_finance_relation_type()
{
    return array(
        FINANCE_RELATION_BOOKING => "预订场地：",
        FINANCE_RELATION_MEMBERFEE => "充值",
        FINANCE_RELATION_SUBOUT => "参与分账：",
        FINANCE_RELATION_SUBIN => "发起分账：",
        FINANCE_RELATION_RECHARGE => '',
        FINANCE_RELATION_CANCEL_BOOKING => "取消场地：",
        FINANCE_RELATION_PARTNER_COACH => "提供陪练：",
        FINANCE_RELATION_PARTNER_STUDENT => "参加陪练：",
        FINANCE_RELATION_TRAIN_STUDENT => "参加培训：",
        FINANCE_RELATION_CUSTOM_IN => "费用增加：",
        FINANCE_RELATION_CUSTOM_OUT => "费用扣除：",
        FINANCE_RELATION_BUY_INSTANT_ORDER => '购买场地：',
        FINANCE_RELATION_CANCEL_INSTANT_ORDER => '取消场地：',
        FINANCE_RELATION_TERMINATE_INSTANT_ORDER => '中止打球：',
        FINANCE_RELATION_SELL_INSTANT_ORDER => '售出场地：',
        FINANCE_RELATION_BUY_SEEKING_ORDER => '参加约球：',
        FINANCE_RELATION_CANCEL_SEEKING_ORDER => '退出约球：',
    );
}

function instant_order_state_option()
{
    return array(
        '' => '请选择',
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

function reserve_order_status_option()
{
    return array(
        '' => '请选择',
        0 => "待处理",
        1 => "代支付",
        2 => "已支付",
        3 => "待分账",
        4 => "已结束",
        5 => "已取消",
        6 => "预订失败"
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

function cache_account($user_id = null, $purpose = \Sports\Constant\Finance::PURPOSE_ACCOUNT)
{
    static $accounts = array();

    $balance = 0;
    if ($user_id === null) {
        $user = Auth::getUser();
        if (!empty($user)) {
            $user_id = $user->user_id;
        }
    }

    if (!empty($user_id)) {
        $key = $user_id . '-' . $purpose;
        if (!isset($accounts[$key])) {
            $accounts[$key] = Finance::ensureAccountExisted($user_id, $purpose);
        }
        $balance = intval($accounts[$key]->getBalance());
    }
    return $balance;
}

function cache_balance($userId = null)
{
    return cache_account($userId);
}

function cache_points($userId = null)
{
    return cache_account($userId, \Sports\Constant\Finance::PURPOSE_POINTS);
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

function option_sexy()
{
    return array(-1 => '不限', 1 => '女', 2 => '男');
}

function option_yes_no()
{
    return array(1 => '是', 2 => '否');
}

function option_account_type()
{
    return array(
        \Sports\Constant\Finance::PURPOSE_POINTS => '积分',
        \Sports\Constant\Finance::PURPOSE_ACCOUNT => '余额',
    );
}

function option_app_type()
{
    return array(
        APP_WE_CHAT => '微信'
    );
}

function option_hall_stat()
{
    return array(
        HALL_STAT_DRAFT => '草稿',
        HALL_STAT_PUBlISH => '已发布',
        HALL_STAT_DELETE => '已删除',
    );
}

function option_roles()
{
    $roles = Config::get('acl.roles');

    $options = array();
    foreach($roles as $key => $role){
        $options[$key] = $role['label'];
    }
    return $options;
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

function hall_head_wechat($hallId)
{
    return 'http://wangqiuer.com/Images/weixinImage/CourtPic/' . $hallId . '.jpg';
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

function adjustTimestampForOneModel($model)
{
    if (empty($model)) {
        return;
    }
    empty($model->updated_at) && $model->updated_at = \Carbon\Carbon::now();
    empty($model->created_at) && $model->created_at = \Carbon\Carbon::now();
}

function user_id(User $user = null)
{
    empty($user) && $user = Auth::getUser();
    return empty($user) ? null : $user->user_id;
}

function debug()
{
    return Config::get('app.debug') || Input::get('debug');
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

/**
 * @param $user_id
 * @return User
 */
function cache_user($user_id)
{
    static $users = array();
    if (!isset($users[$user_id])) {
        $users[$user_id] = User::findOrFail($user_id);
    }
    return $users[$user_id];
}

/**
 * @param $order_id
 * @return ReserveOrder
 */
function cache_reserve_order($order_id)
{
    static $orders = array();
    if (!isset($orders[$order_id])) {
        $orders[$order_id] = ReserveOrder::findOrFail($order_id);
    }
    return $orders[$order_id];
}

/**
 * @param $order_id
 * @return InstantOrder
 */
function cache_instant_order($order_id)
{
    static $orders = array();
    if (!isset($orders[$order_id])) {
        $orders[$order_id] = InstantOrder::findOrFail($order_id);
    }
    return $orders[$order_id];
}

/**
 * @param $hall_id
 * @return Hall
 */
function cache_hall($hall_id)
{
    static $halls = array();
    if (!isset($halls[$hall_id])) {
        $halls[$hall_id] = Hall::findOrFail($hall_id);
    }
    return $halls[$hall_id];
}

/**
 * @param $user_id
 * @return weChatUserProfile
 */
function cache_weChat_profile($user_id)
{
    static $profiles = array();
    if (!isset($profiles[$user_id])) {
        $app = RelationUserApp::whereUserId($user_id)->whereAppId(APP_WE_CHAT)->first();
        $profiles[$user_id] = empty($app) ? null : weChatUserProfile::whereOpenid($app->app_user_id)->first();
    }
    return $profiles[$user_id];
}

/**
 * @param $recharge_id
 * @return Recharge
 */
function cache_recharge($recharge_id)
{
    static $recharges = array();
    if (!isset($recharges[$recharge_id])) {
        $recharges[$recharge_id] = Recharge::findOrFail($recharge_id);
    }
    return $recharges[$recharge_id];
}

/**
 * @param $finance_custom_id
 * @return FinanceCustom
 */
function cache_finance_custom($finance_custom_id)
{
    static $financeCustoms = array();
    if (!isset($financeCustoms[$finance_custom_id])) {
        $financeCustoms[$finance_custom_id] = FinanceCustom::findOrFail($finance_custom_id);
    }
    return $financeCustoms[$finance_custom_id];
}

function array_extract_one_key($arrays, $key)
{
    $result = array();
    foreach ($arrays as $id => $array) {
        $result[$id] = $array[$key];
    }
    return $result;
}

function option_notify_event()
{
    return array_extract_one_key(Config::get('notify.events'), 'title');
}

function option_notify_channel()
{
    return array_extract_one_key(Config::get('notify.channels'), 'title');
}

function option_recharge_type()
{
    return array(
        PAY_TYPE_ALI => '支付宝',
        PAY_TYPE_MGR => '后台手工充值',
        PAY_TYPE_WE_CHAT => '微信支付',
    );
}

function option_recharge_status()
{
    return array(
        RECHARGE_INIT => '未充值',
        RECHARGE_SUCCESS => '充值成功',
        RECHARGE_FAIL => '充值失败',
    );
}

function exception_to_array(Exception $exception)
{
    return array('code' => $exception->getCode(),
        'msg' => $exception->getMessage(), 'line' => $exception->getLine(), 'file' => $exception->getFile());
}

function db_result_ids($dbResults, $idColumn)
{
    $ids = array();
    foreach ($dbResults as $dbResult) {
        $ids[] = $dbResult->$idColumn;
    }
    return $ids;
}

function array_regroup_key_value(&$arrays, $newKey, $newValue)
{
    $res = array();
    foreach ($arrays as $array) {
        $res[$array[$newKey]] = $array[$newValue];
    }
    return $res;
}

function array_regroup_by_key(&$arrays, $key)
{
    $res = array();
    foreach ($arrays as $array) {
        $newKey = $array[$key];
        if(!isset($res[$newKey])){
            $res[$newKey] = array();
        }
        $res[$newKey][] = $array;
    }
    return $res;
}

/**
 * @param $hall Hall | array
 * @return string
 */
function hall_area($hall)
{
    return Area::area($hall['area_text'], $hall['county'], $hall['city'], $hall['province']);
}

function seeking_brief(Seeking $seeking, $title = '约球：')
{
    $levels = option_tennis_level();
    return sprintf('%s%s日%s-%s时，%s，级别%s，人均%s元，%s片',
        $title,
        substr($seeking->event_date, 5, 5),
        $seeking->start_hour,
        $seeking->end_hour,

        $seeking->Hall->name,

        $levels[$seeking->tennis_level],

        $seeking->personal_cost,
        $seeking->court_num
    );
}

function current_role($roleInput = null)
{
    $role = ROLE_VISITOR;
    if(Auth::check()){
        $user = Auth::getUser();
        $roles = array_regroup_by_key($user->roles, 'role_id');

        if($roleInput !== null){
            if(!isset($roleInput, $roles)){
                throw new Exception(sprintf('%s is not support!', $roleInput));
            }
            Session::put(SESSION_KEY_CURRENT_ROLE, $roleInput);
        }

        $recordRole = Session::get(SESSION_KEY_CURRENT_ROLE);
        $role = isset($roles[$recordRole]) ? $recordRole : ROLE_VISITOR;
    }

    return $role;
}