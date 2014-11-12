<?php
Route::get('/hall/detail/{id}', array('before' => 'auth',function($id){
    Layout::setHighlightHeader('nav_场馆列表（管理员）');
    $hall = Hall::with(array('CourtGroup', 'HallMarkets', 'HallPrices', 'Users', 'HallImages', 'Map'))->findOrFail($id);
    Layout::appendBreadCrumbs($hall->name);

    $hall->cities = Area::cities($hall->province);
    $hall->counties = Area::counties($hall->city);

    return View::make('layout')->nest('content', 'hall.detail_mgr',
        array('hall' => $hall));
}));

Route::get('/hall/list/{curTab}', array("before"=>'auth' ,function($curTab){
    Layout::setHighlightHeader('nav_场馆列表（管理员）');

    $tabs = array(
        'published' => array(
            'label' => '已发布场馆',
            'url' => '/hall/list/published'
        ),
        'all' => array(
            'label' => '所有场馆',
            'url' => '/hall/list/all',
        ),
        'latest' => array(
            'label' => '最新场馆',
            'url' => '/hall/list/latest',
        ),
        'recommend' => array(
            'label' => '推荐场馆',
            'url' => '/hall/list/recommend',
        ),
    );

    $latestIds = db_result_ids(HallActive::whereType(HALL_ACTIVE_LATEST)->remember(CACHE_DAY)->get(array('hall_id')), 'hall_id');
    $recommendIds = db_result_ids(HallActive::whereType(HALL_ACTIVE_RECOMMEND)->remember(CACHE_DAY)->get(array('hall_id')), 'hall_id');

    $queries = Input::all();
    if($curTab == 'published'){
        $queries['stat'] = HALL_STAT_PUBlISH;
    }else if($curTab == 'latest'){
        $queries['ids'] = $latestIds;
    }else if($curTab == 'recommend'){
        $queries['ids'] = $recommendIds;
    }

    $hallModel = new Hall();
    $halls = $hallModel->search($queries);
    adjustTimeStamp($halls);

    foreach($halls as $hall){
        $hall->is_latest = in_array($hall->id, $latestIds);
        $hall->is_recommend = in_array($hall->id, $recommendIds);
        $hall->area = Area::area($hall->area_text, $hall->county, $hall->city, $hall->province);
    }

    $stats = option_hall_stat();
    $stats[''] = '状态';

    if(Request::ajax()){
        return rest_success(array('halls' => $halls->toArray(),'queries' => $queries));
    }else{
        return View::make('layout')->nest('content', 'hall.hall_mgr', array(
            'halls' => $halls, 'queries' => $queries, 'stats'=>$stats, 'tabs' => $tabs, 'curTab' => $curTab));
    }
}));

Route::post('/hall/generateUser/{hallId}', array('before' => 'auth', function($hallId){
    $user = Input::only(array('user_id', 'nickname', 'init_password'));
    if(empty($user['nickname']) || empty($user['init_password'])){
        return rest_fail('登录名或初始密码均不能为空！');
    }

    if(empty($user['user_id'])){
        $hall = new Hall();
        $generateUser = $hall->generateUser($hallId, $user['nickname'], $user['init_password']);
    }else{
        return rest_success($user);
    }

    return rest_success($generateUser);
}));

/**
 * 更新地图信息
 */
Route::post('/hall/saveMap/{hallId}', array('before' => 'auth', function($hallId){
    $mapData = Input::only(array('long', 'lat', 'baidu_code'));
    $mapData['hall_id'] = $hallId;

    $mapId = Input::get('id');
    if(!empty($mapId)){
        $res = HallMap::whereId($mapId)->update($mapData);
    }else{
        $res = HallMap::create($mapData);
    }

    return rest_success($res);
}));

Route::post('/hall/saveCourtGroup/{hallId}', array('before' => 'auth', function($hallId){
    $courtGroupData = Input::only(array('name', 'count'));
    $courtGroupData['hall_id'] = $hallId;

    $mapId = Input::get('id');
    if(!empty($mapId)){
        $res = CourtGroup::whereId($mapId)->update($courtGroupData);
    }else{
        $res = CourtGroup::create($courtGroupData);
    }

    return rest_success($res);
}));

Route::post('/hall/savePrice/{hallId}', array('before' => 'auth', function($hallId){
    $priceData = Input::only(array('court_type', 'hall_id', 'market', 'member', 'name', 'purchase', 'vip'));
    $priceData['hall_id'] = $hallId;

    $priceId = Input::get('id');
    if(!empty($priceId)){
        $res = HallPrice::whereId($priceId)->update($priceData);
    }else{
        $res = HallPrice::create($priceData);
    }

    return rest_success($res);
}));

Route::post('hall/deletePrice/{id}', array('before' => 'auth', function($id){
    $res = HallPrice::whereId($id)->delete();
    return rest_success($res);
}));

Route::post('/hall/saveMarket/{hallId}', array('before' => 'auth', function($hallId){
    $marketData = Input::only(array('type', 'hall_id', 'start_week', 'end_week', 'start', 'end', 'price'));
    $marketData['hall_id'] = $hallId;

    $market = Input::get('id');
    if(!empty($market)){
        $res = HallMarket::whereId($market)->update($marketData);
    }else{
        $res = HallMarket::create($marketData);
    }

    return rest_success($res);
}));

Route::post('hall/deleteMarket/{id}', array('before' => 'auth', function($id){
    $res = HallMarket::whereId($id)->delete();
    return rest_success($res);
}));

Route::post('hall/saveImage/{hallId}', array('before' => 'auth', function($hallId){
    $file = Input::file('qqfile');
    $destination = public_path() . '/uploadfiles/court/';
    $file->move($destination, $file->getClientOriginalName());

    $res = HallImage::create(array('hall_id' => $hallId,
        'path' => '/uploadfiles/court/' . $file->getClientOriginalName()));

    return rest_success($res);
}));

Route::post('hall/deleteImage/{id}', array('before' => 'auth', function($id){
    $res = HallImage::whereId($id)->delete();
    return rest_success($res);
}));

Route::post('hall/setEnvelope/{hallId}/{imageId}', function($hallId, $imageId){
    $res = Hall::whereId($hallId)->update(array('image' => $imageId));
    return rest_success($res);
});

Route::post('/hall/update/{hallId}', array('before' => 'auth', function($hallId){
    $mapData = Input::only(array('name','code','telephone','linkman','province','city','county',
        'area_text','sort','business','air','bath','park','thread','good','comment'));
    $res = Hall::whereId($hallId)->update($mapData);
    return rest_success($res);
}));

Route::any('/hall/create', array('before' => 'auth', function(){
    Layout::setHighlightHeader('nav_新增场馆');

    if(Request::isMethod('post')){
        $rules = array(
            'name' => 'required|hall_unique',
        );
        $messages = array(
            'required' => '此项不能为空',
            'hall_unique' => '该场馆名称已经被添加过'
        );

        $validator = Validator::make(Input::all(), $rules, $messages);
        if ($validator->fails()) {
            return View::make('layout')->nest('content', 'hall.create', array('errors' => $validator->messages()));
        }

        $data = Input::only('name', 'code');
        $data['stat'] = HALL_STAT_DRAFT;
        $hall = Hall::create($data);

        return Redirect::to('/hall/detail/' . $hall->id);
    }
    return View::make('layout')->nest('content', 'hall.create');
}));

Route::any('hall/active/operate/{hallId}/{type}/{online}', function($hallId, $type, $online){
    HallActive::whereHallId($hallId)->whereType($type)->delete();
    if($online){
        HallActive::create(array('hall_id'=> $hallId, 'type' => $type));
    }
    return Redirect::to(URL::previous());
});

Route::any('hall/publish/{hallId}/{online}', function($hallId, $online){
    Hall::whereId($hallId)->update(array('stat' => $online ? HALL_STAT_PUBlISH : HALL_STAT_DRAFT));
    return Redirect::to(URL::previous());
});