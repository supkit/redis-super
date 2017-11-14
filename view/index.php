<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Redisuper</title>
    <link rel="stylesheet" href="<?=assets()?>/css/icons.css">
    <link rel="stylesheet" href="<?=assets()?>/css/default.css">
    <link rel="stylesheet" href="<?=assets()?>/css/prism-themes/prism-okaidia.css">
</head>
<body>
<section>
    <div class="databases">
        <div class="logo"><img src="<?=assets()?>/images/redis.svg"></div>
        <div class="connection-data">
            <p>Redisuper</p>
        </div>
        <ul class="db-list">
            <?php for ($i = 0; $i < $databases; $i++) : ?>
                <li><a data-index="<?=$i?>" href="<?=route('index.entry', ['index' => $i])?>"<?php if ($i == $index){ echo ' class="active"'; } ?>><i class="fa fa-database"></i><span><?=$i?></span></a></li>
            <?php endfor; ?>
        </ul>
    </div>
    <div class="keys" style="width: <?=$sidebarWidth;?>px">
        <div class="item-database"><span><i class="md-add-circle"></i></span><span class="search"><input type="text"></span><i class="md-search"></i></div>
        <div class="key-list"><?=$keysHtml?></div>
    </div>
    <div class="move"></div>
    <div class="contents">
        <div class="contents-header">
            <h2><span class="key-name">Redis</h2>
        </div>
        <div class="init-data">
            <h2>当前库</h2>
            <table>
                <col width="30%" />
                <col width="70%" />
                <tr>
                    <td>编号</td>
                    <td class="info-db-index"><?=$index?></td>
                </tr>
                <tr>
                    <td>记录数</td>
                    <td class="info-db-size"><?=$dbSize?></td>
                </tr>
            </table>

            <h2>Redis信息</h2>
            <table>
                <col width="30%" />
                <col width="70%" />
                <tr>
                    <td>Redis版本</td>
                    <td><?=isset($info['redis_version']) ? $info['redis_version'] : ''?></td>
                </tr>
                <tr>
                    <td>内存使用情况</td>
                    <td><?=isset($info['used_memory']) ? $info['used_memory'] : ''?></td>
                </tr>
                <tr>
                    <td>操作系统</td>
                    <td><?=isset($info['os']) ? $info['os'] : ''?></td>
                </tr>
                <tr>
                    <td>系统最大内存</td>
                    <td><?=isset($info['total_system_memory_human']) ? $info['total_system_memory_human'] : ''?></td>
                </tr>
                <tr>
                    <td>当前角色</td>
                    <td><?=isset($info['role']) ? $info['role'] : ''?></td>
                </tr>
                <tr>
                    <td>已连接客户端数量</td>
                    <td><?=isset($info['connected_clients']) ? $info['connected_clients'] : ''?></td>
                </tr>
            </table>
            <h2>配置信息</h2>
            <table>
                <col width="30%" />
                <col width="70%" />
                <tr>
                    <td>数据库安装路径</td>
                    <td><?=$redisDir['dir']?></td>
                </tr>
                <tr>
                    <td>端口</td>
                    <td><?=$port['port']?></td>
                </tr>
            </table>
        </div>
        <div class="power">Powered By <a href="http://chenshuo.net">chenshuo</a> <i class="fa fa-github"></i> <a href="https://github.com/chenshuox">github.com/chenshuox</a></div>
    </div>
</section>
<script src="<?=assets()?>/js/jquery.js"></script>
<script src="<?=assets()?>/js/jquery-cookie.js"></script>
<script src="<?=assets()?>/js/prism.js"></script>
<script>

    $(function() {

        // 高亮显示代码
        Prism.highlightAll();

        // 左侧移动
        var isResizing = false;
        var lastDownX  = 0;
        var lastWidth  = 0;

        var db = <?=$index?>;

        var resizeSidebar = function(w) {
            $('.keys').css('width', w);
            $('.move').css('left', w + 10);
        };

        if (parseInt($.cookie('sidebar')) > 0) {
            resizeSidebar(parseInt($.cookie('sidebar')));
        }

        $('.move').on('mousedown', function (e) {
            isResizing = true;
            lastDownX  = e.clientX;
            lastWidth  = $('.keys').width();
            $(this).width('5px');
            e.preventDefault();
        });

        $(document).on('mousemove', function (e) {
            if (!isResizing) {
                return;
            }

            $(this).width('5px');

            var w = lastWidth - (lastDownX - e.clientX);
            if (w < 250 ) {
                w = 250;
            } else if (w > 1000) {
                w = 1000;
            }
            $.cookie('sidebar', w);
            resizeSidebar(w);
        }).on('mouseup', function (e) {
            $('.move').width('1px');
            isResizing = false;
        });

        $('.db-list a').bind('click', function() {
            $('.db-list a').removeClass('active');
            $(this).addClass('active');
            var index = $(this).data('index');

            var url = this.href;

            var state = { //这里可以是你想给浏览器的一个State对象，为后面的StateEvent做准备。
                title : "HTML 5 History API simple demo",
                url : 'test'
            };

            history.pushState(state, "HTML 5 History API simple demo", url);

            getKeyByDb(index);
            return false;
        });

        // KEY click event
        $('.keys').on('click', '.item li div', function() {
            $('.keys .item li div span').removeClass('active');
            $(this).find('span').addClass('active');

            if ($(this).next('ul').length == 0) {
                var key = $(this).parent('li').data('key');
                getValueByKey(key);
                return true;
            }
            if ($(this).next('ul').is(':visible')) {
                $(this).find('i.icon').attr('class', 'icon md-folder');
                $(this).next('ul').hide();
            } else {
                $(this).find('i.icon').attr('class', 'icon md-folder-open');
                $(this).next('ul').show();
            }
        });

        $('.keys').on('click', '.item li div i.md-close', function(e) {
            var isFullKey = $(this).data('full');
            var key = $(this).parent('div').parent('li').data('key');

            console.log(isFullKey);
            console.log(key);

            $.ajax({
                type: 'get',
                data: {index: db, key: key, isFullKey: isFullKey},
                url: "<?=route('index.delete')?>",
                success: function(response) {
                    console.log(response);
                }
            });
            $(this).parent('div').parent('li').remove();
            e.stopPropagation();
        });

        // search bind foucs event
        $('.keys .item-database input').bind('focus', function() {
            $(this).addClass('focus');
            $('.keys .item-database .md-add-circle').addClass('add-hide');
        });
        $('.keys .item-database input').bind('blur', function() {
            $(this).removeClass('focus');
            $('.keys .item-database .md-add-circle').removeClass('add-hide');
        });

        $('.keys .item-database input').bind('input', function() {
            var index = db;
            var key = $(this).val();

            if (key.length <= 0) {
                key = '*';
            }

            $.ajax({
                type: 'get',
                data: {index: index, key: key},
                url: "<?=route('index.search')?>",
                success: function (response) {
                    console.log(response);
                    $('.key-list').html(response.data.html);
                }
            });
        });

        function getValueByKey(key) {
            $.ajax({
                type: 'get',
                url: "<?=route('index.value')?>",
                data: {index: db, key: key},
                success: function (response) {
                    console.log(response);
                    $('.contents').html(response);
                }
            });
        }

        function getKeyByDb(index) {
            db = index;
            $.ajax({
                type: 'get',
                url: "<?=route('index.keyList')?>",
                data: {index: index},
                success: function (response) {
                    console.log(response);
                    $('.key-list').html(response.data.html);
                    $('.info-db-index').text(index);
                    $('.info-db-size').text(response.data.dbSize);
                }
            });
        }
    });
</script>
</body>
</html>
