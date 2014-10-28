<?php
class NotifyRecord extends Eloquent {
    protected $table = 'gt_notify_record';

    public function search($aQuery, $iPageSize =20){
        return NotifyRecord::where(function(\Illuminate\Database\Eloquent\Builder $builder) use ($aQuery){
            if(!empty($aQuery['created_at_start'])){
                $builder->where('created_at', '>=', $aQuery['created_at_start']);
            }
            if(!empty($aQuery['created_at_end'])){
                $builder->where('created_at', '<=', $aQuery['created_at_end']);
            }
            if(!empty($aQuery['object'])){
                $builder->where('object', '=', $aQuery['object']);
            }
            if(!empty($aQuery['who'])){
                $builder->where('who', 'like', '%' . $aQuery['who'] . '%');
            }
            if(!empty($aQuery['event'])){
                is_array($aQuery['event']) ? $builder->getQuery()->whereIn('event', $aQuery['event'])
                    : $builder->where('event', '=', $aQuery['event']);
            }
            if(!empty($aQuery['channel'])){
                is_array($aQuery['channel']) ? $builder->getQuery()->whereIn('channel', $aQuery['channel'])
                    : $builder->where('channel', '=', $aQuery['channel']);
            }

        })->orderBy('created_at', 'desc')
            ->paginate($iPageSize);
    }
}