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
<!--<script src="/mobile/js/ratchet.js"></script>-->

<script type="text/javascript" src="/assets/plugins/seajs/sea.js"></script>
<script type="text/javascript" src="/assets/js/seajs-config.js?201411121121"></script>
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
            [/(.*\/assets\/js\/module\/.*\.(?:css|js|tpl))(?:.*)$/i, '$1?' + '201411130929'],
            [/(.*\/mobile\/js\/.*\.(?:css|js|tpl))(?:.*)$/i, '$1?' + '201411130929']
        ]
    });
</script>
