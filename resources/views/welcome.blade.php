<!doctype html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>RAMS</title>

    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css?family=Raleway:100,600" rel="stylesheet" type="text/css">

    <!-- Latest compiled and minified CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css"
          integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous"/>
    <!-- Styles -->
    <style>
        html, body {
            background-color: #fff;
            color: #636b6f;
            font-family: 'Raleway', sans-serif;
            font-weight: 100;
            margin: 0;
        }

        .content {
            text-align: center;
        }

        .title {
            font-size: 84px;
            margin-top: 20px;
        }

        iframe {
            width: 100%;
            height: 200px;
            border: 1px solid #ccc;
            border-radius: 4px;
            background-color: #f9f9f9;
        }

        .input-method {
            background-color: #333;
            color: #f9f9f9;
        }

        .input-group {
            margin: .5rem 0;
        }

        .container {
            margin-bottom: 30px;
        }
    </style>
</head>
<body>
<div class="">
    <div class="content">
        <div class="title">
            RAMS
        </div>
        <div style="margin-bottom: 40px">RESFful APIs Management System</div>
    </div>
    <!--
    <div class="container">
        <h3>User create</h3>
        <form action="/api/user" method="post" target="api_user_create">
            <div class="input-group">
                <span class="input-group-addon input-method"><strong>POST</strong></span>
                <input type="text" class="form-control" name="user" value="/api/user"/>
                <span class="input-group-btn">
                    <button class="btn btn-primary" type="submit">Submit</button>
                </span>
            </div>
            <div class="input-group">
                <span class="input-group-addon">Email</span>
                <input type="text" class="form-control" name="email" value=""/>
            </div>
            <div class="input-group">
                <span class="input-group-addon">Password</span>
                <input type="password" class="form-control" name="password" value=""/>
            </div>
        </form>
        <iframe name="api_user_create"></iframe>
    </div>

    <div class="container">
        <h3>User Login</h3>
        <form action="/api/login" method="post" target="api_user_login">
            <div class="input-group">
                <span class="input-group-addon input-method"><strong>POST</strong></span>
                <input type="text" class="form-control" name="user" value="/api/login"/>
                <span class="input-group-btn">
                    <button class="btn btn-primary" type="submit">Submit</button>
                </span>
            </div>
            <div class="input-group">
                <span class="input-group-addon">Email</span>
                <input type="text" class="form-control" name="email" value=""/>
            </div>
            <div class="input-group">
                <span class="input-group-addon">Password</span>
                <input type="password" class="form-control" name="password" value=""/>
            </div>
        </form>
        <iframe name="api_user_login"></iframe>
    </div>

    <div class="container">
        <h3>User info</h3>
        <form action="/api/user" method="get" target="api_user_info">
            <div class="input-group">
                <span class="input-group-addon input-method"><strong>GET</strong></span>
                <input type="text" class="form-control" name="user" value="/api/user"/>
                <span class="input-group-btn">
                    <button class="btn btn-primary" type="submit">Submit</button>
                </span>
            </div>
            <div class="input-group">
                <span class="input-group-addon">Token</span>
                <input type="text" class="form-control" name="token" value=""/>
            </div>
        </form>
        <iframe name="api_user_info"></iframe>
    </div>
    -->
</div>
<script id="api-template" type="text/x-handlebars-template">
    <div class="container">
        <h3><%title%></h3>
        <form method="<%# not_equal method 'GET' %>POST<%/not_equal%>" target="<%target%>">
            <%# not_equal method 'GET' %>
            <input type="hidden" name="_method" value="<%method%>">
            <%/not_equal%>

            <div class="input-group">
                <span class="input-group-addon input-method"><strong><%method%></strong></span>
                <input type="text" class="form-control" name="action" value="<%action%>"/>
                <span class="input-group-btn">
                    <button class="btn btn-primary" type="submit">Submit</button>
                </span>

            </div>
            <%#each param%>
            <div class="input-group">
                <span class="input-group-addon"><%name%></span>
                <input type="text" class="form-control" name="<%name%>" value=""/>
            </div>
            <%/each%>
        </form>
        <iframe name="<%target%>"></iframe>
    </div>
</script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/handlebars.js/4.0.11/handlebars.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/lodash.js/4.17.5/lodash.min.js"></script>
<script>
    (function () {
        Handlebars.registerHelper('equal', function(arg1, arg2, options) {
            return (arg1 == arg2) ? options.fn(this) : options.inverse(this);
        });
        Handlebars.registerHelper('not_equal', function(arg1, arg2, options) {
            return (arg1 != arg2) ? options.fn(this) : options.inverse(this);
        });

        var source = document.getElementById("api-template").innerHTML.replace(/<%/g, '\{\{').replace(/%>/g, '}}');
        var template = Handlebars.compile(source);

        var api = {
            user: {
                create: {
                    title: 'User create',
                    action: '/api/user',
                    method: 'POST',
                    target: 'api_user_crate',
                    param: [
                        {name: 'email', value: ''},
                        {name: 'password', value: ''}
                    ]
                },
                login: {
                    title: 'User login',
                    action: '/api/login',
                    method: 'POST',
                    target: 'api_user_login',
                    param: [
                        {name: 'email', value: ''},
                        {name: 'password', value: ''}
                    ]
                },
                info: {
                    title: 'User info',
                    action: '/api/user',
                    method: 'GET',
                    target: 'api_user_logout',
                    param: [
                        {name: 'token', value: ''},
                    ]
                },
                logout: {
                    title: 'User logout',
                    action: '/api/logout',
                    method: 'POST',
                    target: 'api_user_logout',
                    param: [
                        {name: 'token', value: ''},
                    ]
                },
            },
            store: {
                create: {
                    title: 'Store create',
                    action: '/api/store',
                    method: 'POST',
                    target: 'api_store_crate',
                    param: [
                        {name: 'token', value: ''},
                        {name: 'name', value: ''},
                        //{name: 'addon', value: ''},
                    ]
                },
                read: {
                    title: 'Store read',
                    action: '/api/store',
                    method: 'GET',
                    target: 'api_store_read',
                    param: [
                        {name: 'token', value: ''},
                    ]
                },
                write:{
                    title: 'Store wrtie',
                    action: '/api/store/#name#',
                    method: 'POST',
                    target: 'api_store_write',
                    param: [
                        {name: 'token', value: ''},
                        {name: 'email', value: ''},
                        {name: 'subject', value: ''},
                        {name: 'description', value: ''}
                    ]
                },
                view:{
                    title: 'Store view',
                    action: '/api/store/#name#/#xid#',
                    method: 'GET',
                    target: 'api_store_view',
                    param: [
                        {name: 'token', value: ''},
                        {name: 'exclude', value: ''},
                    ]
                },
                modify:{
                    title: 'Store modify',
                    action: '/api/store/#name#/#xid#',
                    method: 'PATCH',
                    target: 'api_store_modify',
                    param: [
                        {name: 'token', value: ''},
                        {name: 'subject', value: ''},
                        {name: 'description', value: ''},
                    ]
                },
            },
            addon: {
                /*
                create: {
                    title: 'Agent create',
                    action: '/api/agent',
                    method: 'POST',
                    target: 'api_agent_create',
                    param: [
                        {name: 'token', value: ''},
                        {name: 'store', value: ''},
                    ]
                },*/
                read: {
                    title: 'Addon list',
                    action: '/api/addon',
                    method: 'GET',
                    target: 'api_addon',
                    param: [
                        {name: 'token', value: ''},
                    ]
                },
                attach: {
                    title: 'Addon attach',
                    action: '/api/addon/#name#',
                    method: 'POST',
                    target: 'api_addon_attach',
                    param: [
                        {name: 'token', value: ''},
                        {name: 'store', value: ''},
                    ]
                },
                remove:{
                    title: 'Addon take off',
                    action: '/api/addon/#name#',
                    method: 'delete',
                    target: 'api_addon_take_off',
                    param: [
                        {name: 'token', value: ''},
                        {name: 'store', value: ''},
                    ]
                }

            }
        };

        var html = '';
        var key = '';

        // user
        var user = api['user'];
        for (key in user) {
            html += template(user[key]);
        }

        // store
        var store = api['store'];
        for (key in store) {
            html += template(store[key]);
        }

        // addon
        var addon = api['addon'];
        for (key in addon) {
            html += template(addon[key]);
        }

        document.documentElement.insertAdjacentHTML('beforeend', html);
    })();
</script>
<script>
    (function () {
        var iframe = document.querySelectorAll('iframe');
        _.each(iframe, function (node) {
            node.onload = function (e) {
                var text = JSON.stringify(JSON.parse(e.target.contentDocument.documentElement.innerText), null, 4);
                e.target.contentDocument.documentElement.innerHTML = '<pre>' + JSON.stringify(JSON.parse(text), null, 2) + '</pre>';
            }
        });

        var form = document.querySelectorAll('form');
        _.each(form, function (node) {
            node.addEventListener('submit', function (e) {
                e.preventDefault();

                var item = e.target.closest('form');
                var action = item.querySelector('input[name="action"]').value;
                var match = /#\w+#/g.exec(action);
                if( match !== null ){
                    alert('Please chack the "'+match[0].replace(/#/g, '')+'" value');
                    return false;
                }
                item.action = action;
                item.submit();
            })
        })
    })();
</script>
</body>
</html>