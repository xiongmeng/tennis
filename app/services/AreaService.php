<?php
class AreaService  {
    private $directControlledMunicipality = array(
        '110100',   //北京
        '120100',   //天津
        '310100',   //上海
        '440300',   //深圳
        //目前只添加这几个，具体可参见x_address_city
    );

    public function area($detail, $county, $city, $province){
        if($city && !in_array($city, $this->directControlledMunicipality)){
            $province = Province::whereProvinceId($province)->remember(-1)->first();
        }
        $city = City::whereCityId($city)->remember(-1)->first();
        $county = County::whereCountyId($county)->remember(-1)->first();

        return sprintf('%s%s%s%s', ($province instanceof Province) ? $province->province : '',
            ($city instanceof City) ? $city->city : '',
            ($county instanceof County) ? $county->county : '',
            $detail
        );
    }

    public function cities($province){
        return City::whereFatherId($province)->remember(-1)->get();
    }

    public function counties($city){
        return County::whereFatherId($city)->remember(-1)->get();
    }

    public function provinces(){
        return Province::remember(-1)->get();
    }
}