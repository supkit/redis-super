<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>登录</title>
    <link rel="stylesheet" href="<?=assets()?>/css/beauty.css">
    <link rel="stylesheet" href="<?=assets()?>/css/icons.css">
    <style>
        * {
            margin: 0;
            padding: 0;
        }
        html, body {
            height: 100%;
            overflow: hidden;
        }
        body {
            background-color: #4688C3;
        }
        .dialog {
            background-color: rgba(255, 0, 0, 0.72);
            position: fixed;
            width: 200px;
            left: 50%;
            margin-left: -105px;
            top: 170px;
            padding: 15px 10px;
            text-align: center;
            border-radius: 3px;
            color: #ffffff;
            transition: 0.5s ease;
        }
        .hide {
            top: 80px;
            opacity: 0;
        }
        .container {
            height: 100%;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        #section {
            width: 300px;
            height: auto;
            overflow: hidden;
        }
        #section form .input-group{
            border-radius: 3px;
            background-color: #fff;
        }
        #section .form-group {
            width: 100%;
            position: relative;
        }
        #section .form-group input[type=text], #section .form-group input[type=text], #section .form-group input[type=password]{
            width: 100%;
            border-left: 0px;
            border-right: 0px;
            border-top: 0px;
            outline: none;
            box-sizing: border-box;
            padding-left: 46px;
            padding-top: 14px;
            padding-bottom: 14px;
            padding-right: 10px;
            font-size: 16px;
            font-weight: 100;
            border-radius: 3px 3px 0 0;
        }
        #section .form-group input[type=text], #section .form-group input[type=email] {
            border-bottom: 1px solid #ccc;
        }

        #section .form-group input[type=password] {
            border-radius: 3px;
            border: 0px;
        }
        #section .form-group i {
            position: absolute;
            left: 10px;
            font-size: 16px;
            top: 16px;
            color: #444444;
            border-right: 1px solid #ccc;
            padding-right: 5px;
            width: 20px;
        }
        #section .form-submit {
            margin-top: 20px;
        }
        #section .form-submit input {
            background-color: #000;
            background: linear-gradient(to bottom, #111, #000);
            border: 0px;
            color: #fff;
            width: 100%;
            padding-top: 12px;
            padding-bottom: 12px;
            cursor: pointer;
            border-radius: 3px;
            font-size: 16px;
            outline: none;
            display: none;
        }
        #section .form-submit input:hover {
            background-color: #50BFFF;
            transition: all ease-in 0.3s;
        }
        #section .form-group span {
            position: absolute;
            right: 13px;
            top: 14px;
            font-size: 16px;
            color: #FF4949;
            transform: translate(0,0);
        }
        #section .form-group span.error {
            transform: translate(20px, 0);
            transition: .25s ease-out;
            opacity: 0;
        }
        #section .form-group span.is-visible {
            transform: translate(0, 0);
            opacity: 1;
        }
        #section .other {
            margin-top: 30px;
        }
        #section .other a {
            color: #555555;
            text-decoration: none;
        }
        #particles-js {
            width: 100%;
            height: 100%;
            position: fixed;
            top: 0;
            z-index: -1;
        }
    </style>
</head>
<body>
<div class="container">
    <div id="section">
        <form action="" method="post" id="login">
            <div class="input-group">
                <div class="form-group">
                    <i class="icon-user"></i>
                    <input type="text" name="account" id="account" placeholder="账号" autocomplete="off" max="32">
                </div>
                <div class="form-group">
                    <i class="icon-lock"></i>
                    <input type="password" name="password" id="password" placeholder="密码" max="20">
                </div>
            </div>
            <div class="form-submit">
                <input type="submit" class="submit" value="登录">
            </div>
        </form>
    </div>
</div>
<div id="particles-js"></div>
<script src="<?=assets()?>/js/jquery.js"></script>
<script src="<?=assets()?>/js/particles.min.js"></script>
<script src="<?=assets()?>/js/beauty.js"></script>

<script>

    particlesJS.load('particles-js', '<?=assets()?>/js/particles.json', function() {
        console.log('callback - particles.js config loaded');
    });

    var beauty = new Beauty();

    $(function () {
        var isSubmit = true;
        var locked = false;

        $('#username').focus();

        $('#username').focus(function () {
            $('body').find('.dialog').remove();
            hide($('#username'), 200);
        });

        $('#password').focus(function () {
            hide($('#password'), 200);
        });

        $('#login').bind('submit', function() {

            if (locked) {
                return false;
            }

            var accountCheck = false;
            var passwordCheck = false;

            // 账号验证
            if ($('#account').val() == '' || $('#account').val().length == 0) {
                notice($('#username'), '请填写账号', 100);
                isSubmit = false;
            } else {
                accountCheck = true;
            }

            // 密码验证
            if ($('#password').val() == '' || $('#password').val().length == 0) {
                notice($('#password'), '请填写密码', 100);
                passwordCheck = false;
            } else {
                if ($('#password').val().length < 8 || $('#password').val().length > 20) {
                    notice($('#password'), '密码须8-20位', 100);
                    passwordCheck = false;
                } else {
                    passwordCheck = true;
                }
            }

            if (accountCheck && passwordCheck) {
                $.ajax({
                    type : 'post',
                    async: false,
                    url : "<?=route('login.auth')?>",
                    data : {account: $('#account').val(), password : $('#password').val()},
                    beforeSend : function () {
                        $('.submit').val('登陆中...');
                    },
                    success : function(data) {
                        if (data.code > 200) {
                            beauty.message(data.message, 'danger', 1);
                            setTimeout(function() {
                                $('body').find('.dialog').addClass('hide');
                            }, 2000);
                            isSubmit = false;
                            return false;
                        }

                        locked = true;
                        beauty.message('登陆成功', 'success', 1, function () {
                            window.location.href = "<?=route('index.entry')?>";
                        });
                    }
                });
            }

            return false;
        });
    });

    // 显示提示
    function notice(ele, msg, time) {
        ele.parent('.form-group').find('span').remove();
        ele.parent('.form-group').append('<span>'+msg+'</span>');
        ele.parent('.form-group').find('span').addClass('error');
        setTimeout(function() {
            ele.parent('.form-group').find('span').addClass('is-visible');
        }, time);
    }

    // 隐藏提示
    function hide(ele, time) {
        ele.parent('.form-group').find('span').removeClass('is-visible');
        setTimeout(function() {
            ele.parent('.form-group').find('span').remove();
        }, time);
    }
</script>
</body>
</html>