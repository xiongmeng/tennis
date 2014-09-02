<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>GoTennis</title>

    <!-- Sets initial viewport load and disables zooming  -->
    <meta name="viewport" content="initial-scale=1, maximum-scale=1, user-scalable=no, minimal-ui">

    <!-- Makes your prototype chrome-less once bookmarked to your phone's home screen -->
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black">

    <!-- Include the compiled Ratchet CSS -->
    <link href="/mobile/css/ratchet.css" rel="stylesheet">
    <link href="/mobile/css/app.css" rel="stylesheet">

    <!-- Include the compiled Ratchet JS -->
    <script src="/mobile/js/ratchet.js"></script>

    <script type="text/javascript" src="/assets/plugins/seajs/sea.js"></script>
    <script type="text/javascript" src="/assets/js/seajs-config.js"></script>
    <script type="text/javascript" src="/assets/plugins/jquery-1.10.2.min.js"></script>

    <script type="text/javascript">
        seajs.config({
            paths: {
                plugin: '/assets/plugins',
                module: '/assets/js/module'
            },
            base: '/assets/js/module/',
            charset: 'utf-8',
            map: [
                [/(.*\/assets\/js\/module\/.*\.(?:css|js|tpl))(?:.*)$/i, '$1?' + '201409021922']
            ]
        });
    </script>
</head>
<body>

<!--==content==-->
<?php echo $content; ?>
<!--==end content==-->

<!--==nav==-->
<?php echo $footer; ?>
<!--==end nav==-->
<script>
    $(function(){
        $('#search-more-btn').click(function(){

            $('#search-more-icon').toggle();
            $('#search-list').toggle();
        });
    });
</script>
</body>
</html>