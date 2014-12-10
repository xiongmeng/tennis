<?php

class Hall extends Eloquent {
    protected $table = 'gt_hall_tiny';
    protected $primaryKey = 'id';
    protected $fillable = array('name', 'code', 'stat');

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
        return $this->hasOne('HallImage', 'id', 'image');
    }

    public function Map(){
        return $this->hasOne('HallMap', 'hall_id');
    }

    public function search($aQuery, $iPageSize = 20, $relations = array(), $remember = null)
    {
        is_string($relations) && ($relations = (strlen($relations) > 0) ? explode(',', $relations) : array());

        $query = Hall::with($relations)->leftJoin('gt_hall_court', 'gt_hall_tiny.id', '=', 'gt_hall_court.hall_id');
        if(!empty($aQuery['id'])){
            $query->where('gt_hall_tiny.id', '=', $aQuery['id']);
        }
        if(!empty($aQuery['ids'])){
            $query->whereIn('gt_hall_tiny.id', $aQuery['ids']);
        }
        if(!empty($aQuery['name'])){
            $query->where('gt_hall_tiny.name', 'like', '%' . $aQuery['name'] . '%');
        }
        if(!empty($aQuery['court_name'])){
            $query->where('gt_hall_court.name', 'like', '%' . $aQuery['court_name'] . '%');
        }
        if(!empty($aQuery['court_num_lower_bound'])){
            $query->where('gt_hall_court.count', '>=', $aQuery['court_num_lower_bound']);
        }
        if(!empty($aQuery['court_num_upper_bound'])){
            $query->where('gt_hall_court.count', '<=', $aQuery['court_num_upper_bound']);
        }
        if(!empty($aQuery['stat'])){
            $query->where('gt_hall_tiny.stat', '=', $aQuery['stat']);
        }

        $remember !== null && $query->remember($remember);

        $halls = $query->orderBy('gt_hall_tiny.sort', 'desc')
            ->paginate($iPageSize, array('gt_hall_tiny.*',
                'gt_hall_court.name as court_name', 'gt_hall_court.count as court_num'));

        foreach($halls as $hall){
            $hall->area = Area::area($hall->area_text, $hall->county, $hall->city, $hall->province);
        }

        return $halls;
    }

    public function generateUser($hallId, $username, $initPassword){
        Hall::findOrFail($hallId);

        if(RelationUserHall::whereHallId($hallId)->exists()){
            throw new Exception(sprintf('the user of hall (%s) is generated!', $hallId));
        }

        DB::beginTransaction();
        $createdUser = User::create(
            array('nickname' => $username, 'password' => Hash::make($initPassword), 'init_password' => $initPassword)
        );

        if($createdUser instanceof User){
            $createdUser->roles()->save(new Role(array('role_id' => ROLE_HALL)));
            $createdUser->Halls()->attach($hallId);
        }
        DB::commit();

        return $createdUser;
    }
}