<html>
<head>
    <meta http-equiv="content-type" content="text/html;charset=utf-8"/>
    <meta name="viewport" content="initial-scale=1, maximum-scale=1, user-scalable=no, minimal-ui">

    <title>微信安全支付</title>

    <script type="text/javascript">

        //调用微信JS api 支付
        function jsApiCall()
        {
            WeixinJSBridge.invoke(
                'getBrandWCPayRequest',
                <?php echo $jsApiParameters; ?>,
                function(res){
                    if(res.err_msg == "get_brand_wcpay_request:ok"){
                        window.location.href = '/pay_success';
                    }else{
                        window.location.href = '/pay_fail';
//                        WeixinJSBridge.log(res.err_msg);
//                        alert(res.err_code+res.err_desc+res.err_msg);
                    }
                }
            );
        }

        function callpay()
        {
            if (typeof WeixinJSBridge == "undefined"){
                if( document.addEventListener ){
                    document.addEventListener('WeixinJSBridgeReady', jsApiCall, false);
                }else if (document.attachEvent){
                    document.attachEvent('WeixinJSBridgeReady', jsApiCall);
                    document.attachEvent('onWeixinJSBridgeReady', jsApiCall);
                }
            }else{
                jsApiCall();
            }
        }
    </script>
</head>
<body>
    <p style="text-align: center;margin: 50px auto; font-size: 25px">请稍候••••••</p>
</body>
<script>
    callpay();
</script>
</html>