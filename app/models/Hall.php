<?php

class Hall extends Eloquent {
    protected $table = 'gt_hall_tiny';
    protected $primaryKey = 'id';
    public function CourtGroup(){
        return $this->hasOne('CourtGroup', 'hall_id');
    }

    public function HallMarkets(){
        return $this->hasMany('HallMarket', 'hall_id');
    }

    public function HallPrices(){
        return $this->hasMany('HallPrice', 'hall_id');
    }

    public function Users(){
        return $this->belongsToMany('User', 'gt_relation_user_hall', 'hall_id', 'user_id');
    }

    public function Courts(){
        return $this->hasMany('Court', 'hall_id');
    }

    public function InstantOrders(){
        return $this->hasMany('InstantOrder', 'hall_id');
    }

    public function HallImages(){
        return $this->hasMany('HallImage', 'hall_id');
    }

    /**
     * 封皮头像
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function Envelope(){
        return $this->hasOne('HallImage', 'hall_id', 'image');
    }
}