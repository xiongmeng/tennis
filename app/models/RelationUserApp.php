<?php

class RelationUserApp extends Eloquent {
    protected $table = 'gt_relation_user_app';


    public function search($aQuery, $iPageSize = 20){
        $query = RelationUserApp::leftJoin('gt_user_tiny', 'gt_relation_user_app.user_id', '=', 'gt_user_tiny.user_id');
        if(!empty($aQuery['user_id'])){
            $query->where('gt_user_tiny.user_id', '=', $aQuery['user_id']);
        }
        if(!empty($aQuery['nickname'])){
            $query->where('gt_user_tiny.nickname', 'like', '%' . $aQuery['nickname'] . '%');
        }
        if(!empty($aQuery['telephone'])){
            $query->where('gt_user_tiny.telephone', 'like', '%' . $aQuery['telephone'] . '%');
        }
        if(!empty($aQuery['app_user_id'])){
            $query->where('gt_relation_user_app.app_user_id', 'like', '%' . $aQuery['app_user_id'] . '%');
        }
        if(!empty($aQuery['app_id'])){
            $query->where('gt_relation_user_app.app_id', '=', $aQuery['app_id']);
        }
        return $query->orderBy('gt_relation_user_app.id', 'desc')
            ->paginate($iPageSize);
    }
}