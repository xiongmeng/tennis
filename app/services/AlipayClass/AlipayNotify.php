<?php
/* *
 * 类名：AlipayNotify
 * 功能：支付宝通知处理类
 * 详细：处理支付宝各接口通知返回
 * 版本：3.2
 * 日期：2011-03-25
 * 说明：
 * 以下代码只是为了方便商户测试而提供的样例代码，商户可以根据自己网站的需要，按照技术文档编写,并非一定要使用该代码。
 * 该代码仅供学习和研究支付宝接口使用，只是提供一个参考

 *************************注意*************************
 * 调试通知返回时，可查看或改写log日志的写入TXT里的数据，来检查通知返回是否正常
 */

require_once("alipay_function.php");

class AlipayNotify
{
    /**
     * HTTPS形式消息验证地址
     */
    var $https_verify_url = 'https://mapi.alipay.com/gateway.do?service=notify_verify&';
    /**
     * HTTP形式消息验证地址
     */
    var $http_verify_url = 'http://notify.alipay.com/trade/notify_query.do?';
    var $aliapy_config;

    function __construct($aliapy_config)
    {
        $this->aliapy_config = $aliapy_config;
    }

    function AlipayNotify($aliapy_config)
    {
        $this->__construct($aliapy_config);
    }

    /**
     * 针对notify_url验证消息是否是支付宝发出的合法消息
     * @return 验证结果
     */
    function verifyNotify()
    {
        if (empty($_POST)) { //判断POST来的数组是否为空
            return false;
        } else {
            //生成签名结果
            $mysign = $this->getMysign($_POST);
            //获取支付宝远程服务器ATN结果（验证是否是支付宝发来的消息）
            $responseTxt = 'true';
            if (!empty($_POST["notify_id"])) {
                $responseTxt = $this->getResponse($_POST["notify_id"]);
            }
            //写日志记录
//			$log_text = "verifyNotify\nresponseTxt=".$responseTxt."\n notify_url_log:sign=".$_POST["sign"]."&mysign=".$mysign.",";
//			$log_text = $log_text.createLinkString($_POST);
//			logResult($log_text);

            //验证
            //$responsetTxt的结果不是true，与服务器设置问题、合作身份者ID、notify_id一分钟失效有关
            //mysign与sign不等，与安全校验码、请求时的参数格式（如：带自定义参数等）、编码格式有关


            $bRes = (preg_match("/true$/i", $responseTxt) && $mysign == $_POST['sign']);
            //记录数据库
            /**
             * 将支付宝的日志写入数据库
             * @param type $aResponseText 服务端返回的结果是否为true
             * @param type $aClientSign 客户端计算出来的签名结果
             * @param type $aParams 服务端返回的所有参数
             * @param type $aInterface 接口名称，是notify 或者 return
             */
            // credit_Api::logAliPay($responseTxt, $mysign, $_POST, $bRes);
            $aAlipayLog = new AlipayLog();
            $aAlipayLog->response = $responseTxt;
            $aAlipayLog->mysign = $mysign;
            $aAlipayLog->out_trade_no = intval($_POST['out_trade_no']);
            $aAlipayLog->buyer_email = $_POST['buyer_email'];
            $aAlipayLog->total_fee = $_POST['total_fee'];
            $aAlipayLog->createtime = time();
            $aAlipayLog->trade_no = $_POST['trade_no'];
            $aAlipayLog->notify_type = $_POST['notify_type'];
            $aAlipayLog->sign = $_POST['sign'];
            //$aAlipayLog->other_text = $_POST;
            $aAlipayLog->result =$bRes;
            $aAlipayLog->save();
            return $bRes;
        }
    }

    /**
     * 针对return_url验证消息是否是支付宝发出的合法消息
     * @return 验证结果
     */
    function verifyReturn()
    {
        if (empty($_REQUEST)) { //判断POST来的数组是否为空
            return false;
        } else {
            //生成签名结果
            $mysign = $this->getMysign($_REQUEST);
            //获取支付宝远程服务器ATN结果（验证是否是支付宝发来的消息）
            $responseTxt = 'true';
            if (!empty($_REQUEST["notify_id"])) {
                $responseTxt = $this->getResponse($_REQUEST["notify_id"]);
            }
            //写日志记录
//			$log_text = "verifyReturn\nresponseTxt=".$responseTxt."\n notify_url_log:sign=".$_REQUEST["sign"]."&mysign=".$mysign.",";
//			$log_text = $log_text.createLinkString($_REQUEST);
//			logResult($log_text);

            //验证
            //$responsetTxt的结果不是true，与服务器设置问题、合作身份者ID、notify_id一分钟失效有关
            //mysign与sign不等，与安全校验码、请求时的参数格式（如：带自定义参数等）、编码格式有关
            $bRes = (preg_match("/true$/i", $responseTxt) && $mysign == $_REQUEST['sign']);

            //记录数据库
            /**
             * 将支付宝的日志写入数据库
             * @param type $aResponseText 服务端返回的结果是否为true
             * @param type $aClientSign 客户端计算出来的签名结果
             * @param type $aParams 服务端返回的所有参数
             * @param type $aInterface 接口名称，是notify 或者 return
             */
            // credit_Api::logAliPay($responseTxt, $mysign, $_REQUEST, $bRes);
            $aAlipayLog = new AlipayLog();
            $aAlipayLog->response = $responseTxt;
            $aAlipayLog->mysign = $mysign;
            $aAlipayLog->out_trade_no = intval($_REQUEST['out_trade_no']);
            $aAlipayLog->buyer_email = $_REQUEST['buyer_email'];
            $aAlipayLog->total_fee = $_REQUEST['total_fee'];
            $aAlipayLog->createtime = time();
            $aAlipayLog->trade_no = $_REQUEST['trade_no'];
            $aAlipayLog->notify_type = $_REQUEST['notify_type'];
            $aAlipayLog->sign = $_REQUEST['sign'];
            //$aAlipayLog->other_text = $_REQUEST;
            $aAlipayLog->result =$bRes;
            $aAlipayLog->save();
            return $bRes;

        }
    }

    /**
     * 根据反馈回来的信息，生成签名结果
     * @param $para_temp 通知返回来的参数数组
     * @return 生成的签名结果
     */
    function getMysign($para_temp)
    {
        //除去待签名参数数组中的空值和签名参数
        $para_filter = paraFilter($para_temp);

        //对待签名参数数组排序
        $para_sort = argSort($para_filter);
        //生成签名结果

        $mysign = buildMysign($para_sort, trim($_ENV['Alipay_KEY']), strtoupper(trim($this->aliapy_config['sign_type'])));

        return $mysign;
    }

    /**
     * 获取远程服务器ATN结果,验证返回URL
     * @param $notify_id 通知校验ID
     * @return 服务器ATN结果
     * 验证结果集：
     * invalid命令参数不对 出现这个错误，请检测返回处理中partner和key是否为空
     * true 返回正确信息
     * false 请检查防火墙或者是服务器阻止端口问题以及验证时间是否超过一分钟
     */
    function getResponse($notify_id)
    {
        $transport = strtolower(trim($this->aliapy_config['transport']));
        $partner = trim($this->aliapy_config['partner']);
        $veryfy_url = '';
        if ($transport == 'https') {
            $veryfy_url = $this->https_verify_url;
        } else {
            $veryfy_url = $this->http_verify_url;
        }
        $veryfy_url = $veryfy_url . "partner=" . $partner . "&notify_id=" . $notify_id;
        $responseTxt = getHttpResponse($veryfy_url);

        return $responseTxt;
    }
}

?>
