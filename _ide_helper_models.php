<?php
/**
 * An helper file for your Eloquent Models
 * Copy the phpDocs from this file to the correct Model,
 * And remove them from this file, to prevent double declarations.
 *
 * @author Barry vd. Heuvel <barryvdh@gmail.com>
 */


namespace {
/**
 * Court
 *
 * @property integer $id
 * @property string $number
 * @property integer $hall_id
 * @property integer $group_id
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @method static \Illuminate\Database\Query\Builder|\Court whereId($value) 
 * @method static \Illuminate\Database\Query\Builder|\Court whereNumber($value) 
 * @method static \Illuminate\Database\Query\Builder|\Court whereHallId($value) 
 * @method static \Illuminate\Database\Query\Builder|\Court whereGroupId($value) 
 * @method static \Illuminate\Database\Query\Builder|\Court whereCreatedAt($value) 
 * @method static \Illuminate\Database\Query\Builder|\Court whereUpdatedAt($value) 
 */
	class Court {}
}

namespace {
/**
 * Class CourtTemplate
 *
 * @property integer $id
 * @property integer $hall_id
 * @property string $name
 * @property boolean $count
 * @method static \Illuminate\Database\Query\Builder|\CourtGroup whereId($value) 
 * @method static \Illuminate\Database\Query\Builder|\CourtGroup whereHallId($value) 
 * @method static \Illuminate\Database\Query\Builder|\CourtGroup whereName($value) 
 * @method static \Illuminate\Database\Query\Builder|\CourtGroup whereCount($value) 
 */
	class CourtGroup {}
}

namespace {
/**
 * Hall
 *
 * @property integer $id
 * @property integer $partner_id
 * @property string $name
 * @property string $code
 * @property string $telephone
 * @property integer $image
 * @property integer $province
 * @property integer $city
 * @property integer $county
 * @property string $area_text
 * @property string $linkman
 * @property string $business
 * @property string $air
 * @property string $bath
 * @property string $park
 * @property string $thread
 * @property string $good
 * @property string $comment
 * @property boolean $stat
 * @property integer $createtime
 * @property integer $edittime
 * @property integer $createuser
 * @property integer $sort
 * @property-read \CourtGroup $CourtGroup
 * @property-read \Illuminate\Database\Eloquent\Collection|\HallMarket[] $HallMarkets
 * @property-read \Illuminate\Database\Eloquent\Collection|\HallPrice[] $HallPrices
 * @property-read \Illuminate\Database\Eloquent\Collection|\User[] $Users
 * @method static \Illuminate\Database\Query\Builder|\Hall whereId($value) 
 * @method static \Illuminate\Database\Query\Builder|\Hall wherePartnerId($value) 
 * @method static \Illuminate\Database\Query\Builder|\Hall whereName($value) 
 * @method static \Illuminate\Database\Query\Builder|\Hall whereCode($value) 
 * @method static \Illuminate\Database\Query\Builder|\Hall whereTelephone($value) 
 * @method static \Illuminate\Database\Query\Builder|\Hall whereImage($value) 
 * @method static \Illuminate\Database\Query\Builder|\Hall whereProvince($value) 
 * @method static \Illuminate\Database\Query\Builder|\Hall whereCity($value) 
 * @method static \Illuminate\Database\Query\Builder|\Hall whereCounty($value) 
 * @method static \Illuminate\Database\Query\Builder|\Hall whereAreaText($value) 
 * @method static \Illuminate\Database\Query\Builder|\Hall whereLinkman($value) 
 * @method static \Illuminate\Database\Query\Builder|\Hall whereBusiness($value) 
 * @method static \Illuminate\Database\Query\Builder|\Hall whereAir($value) 
 * @method static \Illuminate\Database\Query\Builder|\Hall whereBath($value) 
 * @method static \Illuminate\Database\Query\Builder|\Hall wherePark($value) 
 * @method static \Illuminate\Database\Query\Builder|\Hall whereThread($value) 
 * @method static \Illuminate\Database\Query\Builder|\Hall whereGood($value) 
 * @method static \Illuminate\Database\Query\Builder|\Hall whereComment($value) 
 * @method static \Illuminate\Database\Query\Builder|\Hall whereStat($value) 
 * @method static \Illuminate\Database\Query\Builder|\Hall whereCreatetime($value) 
 * @method static \Illuminate\Database\Query\Builder|\Hall whereEdittime($value) 
 * @method static \Illuminate\Database\Query\Builder|\Hall whereCreateuser($value) 
 * @method static \Illuminate\Database\Query\Builder|\Hall whereSort($value) 
 */
	class Hall {}
}

namespace {
/**
 * HallMarket
 *
 * @property integer $id
 * @property integer $hall_id
 * @property boolean $type
 * @property integer $start_week
 * @property integer $end_week
 * @property boolean $start
 * @property boolean $end
 * @property integer $price
 * @property-read \Hall $Hall
 * @property-read \HallPrice $HallPrice
 * @property-read \Illuminate\Database\Eloquent\Collection|\Court[] $Courts
 * @property-read \CourtGroup $CourtGroup
 * @method static \Illuminate\Database\Query\Builder|\HallMarket whereId($value) 
 * @method static \Illuminate\Database\Query\Builder|\HallMarket whereHallId($value) 
 * @method static \Illuminate\Database\Query\Builder|\HallMarket whereType($value) 
 * @method static \Illuminate\Database\Query\Builder|\HallMarket whereStartWeek($value) 
 * @method static \Illuminate\Database\Query\Builder|\HallMarket whereEndWeek($value) 
 * @method static \Illuminate\Database\Query\Builder|\HallMarket whereStart($value) 
 * @method static \Illuminate\Database\Query\Builder|\HallMarket whereEnd($value) 
 * @method static \Illuminate\Database\Query\Builder|\HallMarket wherePrice($value) 
 */
	class HallMarket {}
}

namespace {
/**
 * HallPrice
 *
 * @property integer $id
 * @property integer $hall_id
 * @property integer $court_type
 * @property string $name
 * @property integer $market
 * @property integer $member
 * @property integer $vip
 * @property integer $purchase
 * @method static \Illuminate\Database\Query\Builder|\HallPrice whereId($value) 
 * @method static \Illuminate\Database\Query\Builder|\HallPrice whereHallId($value) 
 * @method static \Illuminate\Database\Query\Builder|\HallPrice whereCourtType($value) 
 * @method static \Illuminate\Database\Query\Builder|\HallPrice whereName($value) 
 * @method static \Illuminate\Database\Query\Builder|\HallPrice whereMarket($value) 
 * @method static \Illuminate\Database\Query\Builder|\HallPrice whereMember($value) 
 * @method static \Illuminate\Database\Query\Builder|\HallPrice whereVip($value) 
 * @method static \Illuminate\Database\Query\Builder|\HallPrice wherePurchase($value) 
 */
	class HallPrice {}
}

namespace {
/**
 * Header
 *
 * @property integer $id
 * @property string $header_id
 * @property string $p_id
 * @property string $label
 * @property string $name
 * @property string $url
 * @property-read \Illuminate\Database\Eloquent\Collection|\Header[] $children
 * @method static \Illuminate\Database\Query\Builder|\Header whereId($value) 
 * @method static \Illuminate\Database\Query\Builder|\Header whereHeaderId($value) 
 * @method static \Illuminate\Database\Query\Builder|\Header wherePId($value) 
 * @method static \Illuminate\Database\Query\Builder|\Header whereLabel($value) 
 * @method static \Illuminate\Database\Query\Builder|\Header whereName($value) 
 * @method static \Illuminate\Database\Query\Builder|\Header whereUrl($value) 
 */
	class Header {}
}

namespace {
/**
 * InstantOrder
 *
 * @property integer $id
 * @property string $state
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property integer $hall_id
 * @property integer $court_id
 * @property string $event_date
 * @property integer $start_hour
 * @property integer $end_hour
 * @property integer $buyer
 * @property integer $seller
 * @property float $generated_price
 * @property float $quote_price
 * @property integer $seller_service_fee
 * @property string $hall_name
 * @property string $buyer_name
 * @property string $court_tags
 * @method static \Illuminate\Database\Query\Builder|\InstantOrder whereId($value) 
 * @method static \Illuminate\Database\Query\Builder|\InstantOrder whereState($value) 
 * @method static \Illuminate\Database\Query\Builder|\InstantOrder whereCreatedAt($value) 
 * @method static \Illuminate\Database\Query\Builder|\InstantOrder whereUpdatedAt($value) 
 * @method static \Illuminate\Database\Query\Builder|\InstantOrder whereHallId($value) 
 * @method static \Illuminate\Database\Query\Builder|\InstantOrder whereCourtId($value) 
 * @method static \Illuminate\Database\Query\Builder|\InstantOrder whereEventDate($value) 
 * @method static \Illuminate\Database\Query\Builder|\InstantOrder whereStartHour($value) 
 * @method static \Illuminate\Database\Query\Builder|\InstantOrder whereEndHour($value) 
 * @method static \Illuminate\Database\Query\Builder|\InstantOrder whereBuyer($value) 
 * @method static \Illuminate\Database\Query\Builder|\InstantOrder whereSeller($value) 
 * @method static \Illuminate\Database\Query\Builder|\InstantOrder whereGeneratedPrice($value) 
 * @method static \Illuminate\Database\Query\Builder|\InstantOrder whereQuotePrice($value) 
 * @method static \Illuminate\Database\Query\Builder|\InstantOrder whereSellerServiceFee($value) 
 * @method static \Illuminate\Database\Query\Builder|\InstantOrder whereHallName($value) 
 * @method static \Illuminate\Database\Query\Builder|\InstantOrder whereBuyerName($value) 
 * @method static \Illuminate\Database\Query\Builder|\InstantOrder whereCourtTags($value) 
 */
	class InstantOrder {}
}

namespace {
/**
 * LegalHolidays
 *
 * @property integer $id
 * @property integer $date
 * @property integer $week
 * @property boolean $type
 * @method static \Illuminate\Database\Query\Builder|\LegalHolidays whereId($value) 
 * @method static \Illuminate\Database\Query\Builder|\LegalHolidays whereDate($value) 
 * @method static \Illuminate\Database\Query\Builder|\LegalHolidays whereWeek($value) 
 * @method static \Illuminate\Database\Query\Builder|\LegalHolidays whereType($value) 
 */
	class LegalHolidays {}
}

namespace {
/**
 * RelationUserHall
 *
 * @property integer $id
 * @property integer $user_id
 * @property integer $hall_id
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @method static \Illuminate\Database\Query\Builder|\RelationUserHall whereId($value) 
 * @method static \Illuminate\Database\Query\Builder|\RelationUserHall whereUserId($value) 
 * @method static \Illuminate\Database\Query\Builder|\RelationUserHall whereHallId($value) 
 * @method static \Illuminate\Database\Query\Builder|\RelationUserHall whereCreatedAt($value) 
 * @method static \Illuminate\Database\Query\Builder|\RelationUserHall whereUpdatedAt($value) 
 */
	class RelationUserHall {}
}

namespace {
/**
 * Role
 *
 * @property integer $id
 * @property string $role_id
 * @property string $label
 * @property string $name
 * @property string $url
 * @property-read \Illuminate\Database\Eloquent\Collection|\Header[] $headers
 * @method static \Illuminate\Database\Query\Builder|\Role whereId($value) 
 * @method static \Illuminate\Database\Query\Builder|\Role whereRoleId($value) 
 * @method static \Illuminate\Database\Query\Builder|\Role whereLabel($value) 
 * @method static \Illuminate\Database\Query\Builder|\Role whereName($value) 
 * @method static \Illuminate\Database\Query\Builder|\Role whereUrl($value) 
 */
	class Role {}
}

namespace {
/**
 * SmsQueue
 *
 * @property integer $id
 * @property integer $user_id
 * @property integer $channel_id
 * @property string $phone
 * @property string $message
 * @property integer $status
 * @property integer $created_time
 * @property integer $send_time
 * @property integer $completed_time
 * @property integer $order
 * @method static \Illuminate\Database\Query\Builder|\SmsQueue whereId($value) 
 * @method static \Illuminate\Database\Query\Builder|\SmsQueue whereUserId($value) 
 * @method static \Illuminate\Database\Query\Builder|\SmsQueue whereChannelId($value) 
 * @method static \Illuminate\Database\Query\Builder|\SmsQueue wherePhone($value) 
 * @method static \Illuminate\Database\Query\Builder|\SmsQueue whereMessage($value) 
 * @method static \Illuminate\Database\Query\Builder|\SmsQueue whereStatus($value) 
 * @method static \Illuminate\Database\Query\Builder|\SmsQueue whereCreatedTime($value) 
 * @method static \Illuminate\Database\Query\Builder|\SmsQueue whereSendTime($value) 
 * @method static \Illuminate\Database\Query\Builder|\SmsQueue whereCompletedTime($value) 
 * @method static \Illuminate\Database\Query\Builder|\SmsQueue whereOrder($value) 
 */
	class SmsQueue {}
}

namespace {
/**
 * User
 *
 * @property integer $user_id
 * @property integer $partner_id
 * @property string $nickname
 * @property string $mail
 * @property string $telephone
 * @property string $password
 * @property string $realname
 * @property string $head
 * @property string $identity
 * @property integer $birthday
 * @property boolean $sexy
 * @property integer $province
 * @property integer $city
 * @property integer $county
 * @property string $area_text
 * @property integer $createtime
 * @property integer $logontime
 * @property integer $logonnum
 * @property boolean $stat
 * @property integer $createuser
 * @property boolean $total_cost
 * @property boolean $privilege
 * @property float $member_fee
 * @property float $balance_un_support
 * @property integer $uc_id
 * @property string $register_ip
 * @property boolean $source
 * @property boolean $original
 * @property string $weixin_openid
 * @property string $remember_token
 * @property \Carbon\Carbon $updated_at
 * @property \Carbon\Carbon $created_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\Role[] $roles
 * @property-read \Illuminate\Database\Eloquent\Collection|\Hall[] $Halls
 * @method static \Illuminate\Database\Query\Builder|\User whereUserId($value) 
 * @method static \Illuminate\Database\Query\Builder|\User wherePartnerId($value) 
 * @method static \Illuminate\Database\Query\Builder|\User whereNickname($value) 
 * @method static \Illuminate\Database\Query\Builder|\User whereMail($value) 
 * @method static \Illuminate\Database\Query\Builder|\User whereTelephone($value) 
 * @method static \Illuminate\Database\Query\Builder|\User wherePassword($value) 
 * @method static \Illuminate\Database\Query\Builder|\User whereRealname($value) 
 * @method static \Illuminate\Database\Query\Builder|\User whereHead($value) 
 * @method static \Illuminate\Database\Query\Builder|\User whereIdentity($value) 
 * @method static \Illuminate\Database\Query\Builder|\User whereBirthday($value) 
 * @method static \Illuminate\Database\Query\Builder|\User whereSexy($value) 
 * @method static \Illuminate\Database\Query\Builder|\User whereProvince($value) 
 * @method static \Illuminate\Database\Query\Builder|\User whereCity($value) 
 * @method static \Illuminate\Database\Query\Builder|\User whereCounty($value) 
 * @method static \Illuminate\Database\Query\Builder|\User whereAreaText($value) 
 * @method static \Illuminate\Database\Query\Builder|\User whereCreatetime($value) 
 * @method static \Illuminate\Database\Query\Builder|\User whereLogontime($value) 
 * @method static \Illuminate\Database\Query\Builder|\User whereLogonnum($value) 
 * @method static \Illuminate\Database\Query\Builder|\User whereStat($value) 
 * @method static \Illuminate\Database\Query\Builder|\User whereCreateuser($value) 
 * @method static \Illuminate\Database\Query\Builder|\User whereTotalCost($value) 
 * @method static \Illuminate\Database\Query\Builder|\User wherePrivilege($value) 
 * @method static \Illuminate\Database\Query\Builder|\User whereMemberFee($value) 
 * @method static \Illuminate\Database\Query\Builder|\User whereBalanceUnSupport($value) 
 * @method static \Illuminate\Database\Query\Builder|\User whereUcId($value) 
 * @method static \Illuminate\Database\Query\Builder|\User whereRegisterIp($value) 
 * @method static \Illuminate\Database\Query\Builder|\User whereSource($value) 
 * @method static \Illuminate\Database\Query\Builder|\User whereOriginal($value) 
 * @method static \Illuminate\Database\Query\Builder|\User whereWeixinOpenid($value) 
 * @method static \Illuminate\Database\Query\Builder|\User whereRememberToken($value) 
 * @method static \Illuminate\Database\Query\Builder|\User whereUpdatedAt($value) 
 * @method static \Illuminate\Database\Query\Builder|\User whereCreatedAt($value) 
 */
	class User {}
}

