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

const PRIVILEGE_NORMAL = 1;//普通会员
const PRIVILEGE_GOLD = 2; //金卡会员

const UPGRADE_TO_GOLD_MONEY = 500;  //升级成为金卡会员的钱数