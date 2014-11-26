<?php

//支持的最大天数-目前是一周内的
const WORKTABLE_SUPPORT_DAYS_LENGTH = 7;
const MGR_WORKTABLE_SUPPORT_DAYS_LENGTH = 8;


const RECHARGE_CALLBACK_PAY_INSTANT_ORDER = 1;
const RECHARGE_CALLBACK_PAY_RESERVE_ORDER = 2;
const RECHARGE_CALLBACK_PAY_SEEKING_ORDER = 3;

const APP_WEB_PC = 1;
const APP_WE_CHAT = 2;


const PAY_TYPE_ALI = 1; //支付宝
const PAY_TYPE_TEN=2; //财富通
const PAY_TYPE_999=3;//块钱
const PAY_TYPE_MGR=4;//后台手工充值
const PAY_TYPE_WE_CHAT = 8; //微信支付

const RECHARGE_INIT = 1;//充值记录初始化
const RECHARGE_SUCCESS = 2;//充值成功
const RECHARGE_FAIL = 3;//充值失败
const RECHARGE_ASSIGN = 4;//充值已充到用户账户上 - 不知道有没有用上

const CACHE_PREFIX_VALID_CODE = 'valid_code_';

const ROLE_USER = 1;//用户会员角色
const ROLE_MGR = 2;//管理员
const ROLE_HALL = 3;//场馆角色

const RESERVE_STAT_INIT =  0;  //初始化 - 草稿 - 待处理
const RESERVE_STAT_UNPAY = 1; //待支付
const RESERVE_STAT_PAYED =  2; //已支付
const RESERVE_STAT_SUBED =  3;  //已执行分账
const RESERVE_STAT_CLOSED =  4;  //已结束
const RESERVE_STAT_CANCELED =  5;  //已取消
const RESERVE_STAT_FAILED =  6;  //失败订单 - 草稿 -重新处理

const RESERVE_EXPIRE_TIME = 15 ;    //预约过期间隔，以分为单位

const PRIVILEGE_NORMAL = 1;//普通会员
const PRIVILEGE_GOLD = 2; //金卡会员

const UPGRADE_TO_GOLD_MONEY = 500;  //升级成为金卡会员的钱数
const NO_MONEY_LOWER_BOUND = 200;   //余额不足的短信提示

//通知渠道
const NOTIFY_CHANNEL_SMS_ASYNC = 'sms-async';   //通过短信的方式通知 - 异步
const NOTIFY_CHANNEL_SMS_SYNC = 'sms-sync';   //通过短信的方式通知 - 同步
const NOTIFY_CHANNEL_WX_SYNC = 'wx-sync';     //通过微信的方式通知用户 - 同步
//通知类型
const NOTIFY_TYPE_BASE = 8888;
const NOTIFY_TYPE_CUSTOM_ONE = 7777;//发送自定义短信内容
const NOTIFY_TYPE_INIT_WJ = 8887;//给望京望京联合会成员发送短信
const NOTIFY_TYPE_ORDER_UNPAY = 8886;//未支付订单用户发送短信
const NOTIFY_TYPE_ORDER_PAYED = 8885;//已支付订单用户发送短信
const NOTIFY_TYPE_RECHARGE = 8884;//给充值用户发送到账短信
const NOTIFY_TYPE_NOMONEY = 8883;//提醒用户余额不足短信
const NOTIFY_TYPE_TRADE_UNPAY_DEBTOR = 8882;//对于失败的分账，为被分账用户发送短信提醒
const NOTIFY_TYPE_TRADE_UNPAY_CREDITOR = 8881;//对于失败的分账，为发起分账用户发送短信提醒
const NOTIFY_TYPE_TRADE_PAYED_DEBTOR = 8880;//对于成功的分账，为被分账用户发送短信提醒
const NOTIFY_TYPE_ORDER_CANCEL = 8879;//订单取消，为用户发送的短信
const NOTIFY_TYPE_ORDER_NOTICE = 8700;//前台用户提交的短信，用于提醒管理员
const NOTIFY_TYPE_ORDER_FAILED = 8889;//没有场地，给用户发送微信提示
const NOTIFY_TYPE_PARTNER_COACH = 8878;//陪练中教练费用支付，提醒教练
const NOTIFY_TYPE_PARTNER_STUDENT = 8877;//陪练中用户费用扣除，提醒用户
const NOTIFY_TYPE_TRAIN_COACH = 8876;//培训中教练费用支付，提醒教练
const NOTIFY_TYPE_TRAIN_STUDENT = 8875;//培训中用户费用扣除，提醒用户
const NOTIFY_TYPE_TELEPHONE_VALID_CODE = 8874;//为用户发送手机验证码
const NOTIFY_TYPE_PASSWORD_RESET = 8873;//为用户发送密码重置短信
const NOTIFY_TYPE_FINANCE_CUSTOM_DEBTOR = 8872;//自定义扣款中，为扣款人发送短信
const NOTIFY_TYPE_USER_INSTANT_ORDER_PAYED = 9000;    //即时订单购买成功后用户的消息
const NOTIFY_TYPE_HALL_INSTANT_ORDER_SOLD = 9001;     //即时订单购买成功后场馆侧的消息提醒
const NOTIFY_TYPE_HALL_INSTANT_ORDER_CANCELED = 9002;     //即时订单取消后场馆侧的消息提醒


const USER_PRIVILEGE_NORMAL = 1;    //普通会员
const USER_PRIVILEGE_VIP = 2;   //VIP会员

const YES = 1; const NO = 2;

//自定义扣款的相关常量
const FINANCE_CUSTOM_INIT = 1;
const FINANCE_CUSTOM_SUCC = 2;
const FINANCE_CUSTOM_FAIL = 3;

const HALL_STAT_NULL = 0;
const HALL_STAT_DRAFT = 1;   //未发布
const HALL_STAT_PUBlISH = 2; //已发布
const HALL_STAT_DELETE = 88; //已删除

const HALL_ACTIVE_RECOMMEND = 1; //推荐
const HALL_ACTIVE_LATEST = 3; //最新

const CACHE_DAY = 720;
const CACHE_HOUR = 60;

const INTERVAL_SEEKING_PAY_EXPIRE = 15;//约球报名过期时间

const SEEKING_STATE_DRAFT = 'draft'; //草稿状态
const SEEKING_STATE_CLOSED = 'closed'; //已关门
const SEEKING_STATE_OPENED = 'opened'; //已开门
const SEEKING_STATE_FULLED = 'fulled'; //已满员
const SEEKING_STATE_COMPLETED = 'completed'; //已结束
const SEEKING_STATE_CANCELED = 'canceled'; //已结束
const SEEKING_STATE_FULL_CHECKING = 'full_checking'; //检测满员状态，这是一个中间状态，如果满则去已满员状态，否则去opened状态

const SEEKING_ORDER_STATE_DISPOSING = 'disposing'; //待处理
const SEEKING_ORDER_STATE_PAYING = 'paying'; //支付中
const SEEKING_ORDER_STATE_PAYED = 'payed'; //已支付
const SEEKING_ORDER_STATE_PAY_FAILED = 'pay_failed'; //支付失败
const SEEKING_ORDER_STATE_COMPLETED = 'completed'; //已结束
const SEEKING_ORDER_STATE_CANCELED = 'canceled'; //已取消


const FINANCE_RELATION_REVERSAL = -1; //撤销
const FINANCE_RELATION_BOOKING = 1; //预定
const FINANCE_RELATION_MEMBERFEE = 2; //缴纳会籍费
const FINANCE_RELATION_SUBOUT = 3; //分账支出方
const FINANCE_RELATION_SUBIN = 4; //分账支入方
const FINANCE_RELATION_RECHARGE = 5;//充值
const FINANCE_RELATION_CANCEL_BOOKING = 6;//取消预定
const FINANCE_RELATION_PARTNER_COACH = 7;//陪练教练收入
const FINANCE_RELATION_PARTNER_STUDENT = 8;//陪练学员支出
const FINANCE_RELATION_TRAIN_STUDENT = 9;//培训费学员支出
const FINANCE_RELATION_CUSTOM_IN = 10;//自定义费用得钱方
const FINANCE_RELATION_CUSTOM_OUT = 11;//自定义费用出钱方
const FINANCE_RELATION_BUY_INSTANT_ORDER = 12;//购买即时订单
const FINANCE_RELATION_CANCEL_INSTANT_ORDER = 13;//取消购买即时订单
const FINANCE_RELATION_TERMINATE_INSTANT_ORDER = 14;//执行中止即时订单
const FINANCE_RELATION_SELL_INSTANT_ORDER = 15;//售出即时订单

const FINANCE_RELATION_BUY_SEEKING_ORDER = 16;//售出约球订单
const FINANCE_RELATION_CANCEL_SEEKING_ORDER = 17;//取消约球订单

/**
 * 财务支持的操作枚举
 */
const FINANCE_OPERATE_RECHARGE = 1; //充值
const FINANCE_OPERATE_CONSUME = 2;  //消费
const FINANCE_OPERATE_FREEZE = 3;   //冻结
const FINANCE_OPERATE_UNFREEZE = 4;   //解冻

/**
 * 使用原因
 */
const FINANCE_PURPOSE_ACCOUNT = 1;//默认账户
const FINANCE_PURPOSE_POINTS = 2;//积分

const SESSION_KEY_LOGIN_CALLBACK = 'login_callback';