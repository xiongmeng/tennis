<?php
return array(
    'WeChatMenu' =>array( '{

     "button":[
     {
           "name":"订场服务",
           "sub_button":[
           {
               "type":"click",
               "name":"推荐场馆",
               "key":"Recommend_Court"

            },
            {
               "type":"click",
               "name":"附近场馆",
               "key":"Nearby_Court"
            },
            {
               "type":"click",
               "name":"常订场馆",
               "key":"Ordered_Court"
            },
            {
               "type":"click",
               "name":"搜索场馆",
               "key":"Search_Court"
            }]
       },
       {
           "name":"会员中心",
           "sub_button":[
           {
               "type":"click",
               "name":"注册/绑定",
               "key":"Add_Bond"
            },
            {
               "type":"click",
               "name":"会员服务",
               "key":"Member_Sever"
            }]
       },
     {
          "type":"click",
          "name":"即时订场",
          "key":"Instant_Order"
      }]

 }'
    )
);