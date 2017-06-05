<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>{{$smarty.const.SHOP_NAME}} - Admin</title>

    <link href="https://fonts.googleapis.com/css?family=Roboto+Condensed" rel="stylesheet">
    <link href="{{$domain}}/statics/css/font-awesome.css" type="text/css" rel="stylesheet">
    <link href="{{$domain}}/statics/css/jquery-ui.min.css" type="text/css" rel="stylesheet">
    <link href="{{$domain}}/statics/css/nanoscroller.css" type="text/css" rel="stylesheet">
    <link href="{{$domain}}/statics/css/form-components.css" type="text/css" rel="stylesheet">
    <link href="{{$domain}}/statics/css/admin.css" type="text/css" rel="stylesheet">
</head>
<body class="preload">
    <nav>
        <div id="logo-wrapper">
            <img src="{{$domain}}/statics/img/admin/logo.png" alt="{{$smarty.const.SHOP_NAME}} logo" />
        </div>
        <ul id="menu">
            <li><a href="{{$domain}}/admin/dashboard"><i class="fa fa-home"></i>Dashboard</a></li>
            <li><a href="{{$domain}}/admin/produtos"><i class="fa fa-tags"></i>Produtos</a></li>
            <li><a href="{{$domain}}/admin/pedidos"><i class="fa fa-shopping-cart"></i>Pedidos</a></li>
            <li><a href="{{$domain}}/admin/clientes"><i class="fa fa-users"></i>Clientes</a></li>
            <li><a href="{{$domain}}/admin/descontos"><i class="fa fa-percent"></i>Descontos</a></li>
            <li><a href="{{$domain}}/admin/relatorios"><i class="fa fa-file-text-o"></i>Relatórios</a></li>
        </ul>
    </nav>
    <section id="content">
        {{block name="content"}}{{/block}}
    </section>

    <script type="text/javascript">var $domain = "{{$domain}}";</script>
    <script type="text/javascript" src="{{$domain}}/statics/js/jquery.min.js"></script>
    <script type="text/javascript" src="{{$domain}}/statics/js/jquery-ui.min.js"></script>
    <script type="text/javascript" src="{{$domain}}/statics/js/nanoscroller.min.js"></script>
    <script type="text/javascript" src="{{$domain}}/statics/js/form-components.js"></script>
    <script type="text/javascript" src="{{$domain}}/statics/js/admin.js" async></script>
    <script> </script>
</body>
</html>