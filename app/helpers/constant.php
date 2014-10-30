<?php

//支持的最大天数-目前是一周内的
const WORKTABLE_SUPPORT_DAYS_LENGTH = 7;
const MGR_WORKTABLE_SUPPORT_DAYS_LENGTH = 8;


const RECHARGE_CALLBACK_PAY_INSTANT_ORDER = 1;
const RECHARGE_CALLBACK_PAY_RESERVE_ORDER = 2;

const APP_WEB_PC = 1;
const APP_WE_CHAT = 2;


const PAY_TYPE_ALI = 1; //支付宝
const PAY_TYPE_TEN=2; //财富通
const PAY_TYPE_999=3;//块钱
const PAY_TYPE_MGR=4;//后台手工充值
const PAY_TYPE_WE_CHAT = 8; //微信支付

const CACHE_PREFIX_VALID_CODE = 'valid_code_';

const ROLE_USER = 1;//用户会员角色

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
const NOTIFY_TYPE_PARTNER_COACH = 8878;//陪练中教练费用支付，提醒教练
const NOTIFY_TYPE_PARTNER_STUDENT = 8877;//陪练中用户费用扣除，提醒用户
const NOTIFY_TYPE_TRAIN_COACH = 8876;//培训中教练费用支付，提醒教练
const NOTIFY_TYPE_TRAIN_STUDENT = 8875;//培训中用户费用扣除，提醒用户
const NOTIFY_TYPE_TELEPHONE_VALID_CODE = 8874;//为用户发送手机验证码
const NOTIFY_TYPE_PASSWORD_RESET = 8873;//为用户发送密码重置短信
const NOTIFY_TYPE_FINANCE_CUSTOM_DEBTOR = 8872;//自定义扣款中，为扣款人发送短信
const NOTIFY_TYPE_USER_INSTANT_ORDER_PAYED = 9000;    //即时订单购买成功后用户的消息
const NOTIFY_TYPE_HALL_INSTANT_ORDER_SOLD = 9001;     //即时订单购买成功后场馆侧的消息提醒

const USER_PRIVILEGE_NORMAL = 1;    //普通会员
const USER_PRIVILEGE_VIP = 2;   //VIP会员

const YES = 1; const NO = 2;

//自定义扣款的相关常量
const FINANCE_CUSTOM_INIT = 1;
const FINANCE_CUSTOM_SUCC = 2;
const FINANCE_CUSTOM_FAIL = 3;