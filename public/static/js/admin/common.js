window.tools = {
    timeChange: function(source, inFormat, outFormat) { //时间转化
        var checkTime = function(time) {
            if(time < 10) {
                time = "0" + time;
            };
            return time;
        };
        switch(inFormat) {
            case 'Y-m-d H:i:s':
                var reg =  /^(\d{1,4})(-|\/)(\d{1,2})\2(\d{1,2}) (\d{1,2}):(\d{1,2}):(\d{1,2})$/;
                source = source.match(reg);
                source = new Date(source[1], source[3]-1, source[4], source[5], source[6], source[7]);
                break;
            case 'Y-m-d H:i':
                var reg =  /^(\d{1,4})(-|\/)(\d{1,2})\2(\d{1,2}) (\d{1,2}):(\d{1,2})$/;
                source = source.match(reg);
                source = new Date(source[1], source[3]-1, source[4], source[5], source[6]);
                break;
            case 'Y-m-d':
                var reg = /^(\d{1,4})(-|\/)(\d{1,2})\2(\d{1,2})$/;
                source = source.match(reg);
                source = new Date(source[1], source[3]-1, source[4]);
                break;
            case 'timestamp':
                source = new Date(parseInt(source)*1000);
                break;
        };
        switch(outFormat) {
            case 'Y-m-d H:i:s':
                return source.getFullYear() + '-'
                    + checkTime(source.getMonth()+1)
                    + '-'
                    + checkTime(source.getDate())
                    + ' '
                    + checkTime(source.getHours())
                    + ':'
                    + checkTime(source.getMinutes())
                    + ':'
                    + checkTime(source.getSeconds());
                break;
            case 'Y-m-d H:i':
                return source.getFullYear() + '-'
                    + checkTime(source.getMonth()+1)
                    + '-'
                    + checkTime(source.getDate())
                    + ' '
                    + checkTime(source.getHours())
                    + ':'
                    + checkTime(source.getMinutes());
                break;
            case 'Y-m-d':
                return source.getFullYear() + '-'
                    + checkTime(source.getMonth()+1)
                    + '-'
                    + checkTime(source.getDate());
                break;
            case 'Y.m.d':
                return source.getFullYear() + '.'
                    + checkTime(source.getMonth()+1)
                    + '.'
                    + checkTime(source.getDate());
                break;
            case 'm-d H:i':
                return checkTime(source.getMonth()+1)
                    + '-'
                    + checkTime(source.getDate())
                    + ' '
                    + checkTime(source.getHours())
                    + ':'
                    + checkTime(source.getMinutes());
                break;
            case 'm月d日 H:i':
                return checkTime(source.getMonth()+1)
                    + '月'
                    + checkTime(source.getDate())
                    + '日 '
                    + checkTime(source.getHours())
                    + ':'
                    + checkTime(source.getMinutes());
                break;
            case 'm月d日':
                return checkTime(source.getMonth()+1)
                    + '月'
                    + checkTime(source.getDate())
                    + '日 ';
                break;
            case 'm-d':
                return checkTime(source.getMonth()+1)
                    + '-'
                    + checkTime(source.getDate());
                break;
            case 'm.d':
                return checkTime(source.getMonth()+1)
                    + '.'
                    + checkTime(source.getDate());
                break;
            case 'm.d H:i':
                return checkTime(source.getMonth()+1)
                    + '.'
                    + checkTime(source.getDate())
                    + ' '
                    + checkTime(source.getHours())
                    + ':'
                    + checkTime(source.getMinutes());
                break;
            case 'H:i':
                return checkTime(source.getHours())
                    + ':'
                    + checkTime(source.getMinutes());
                break;
            case 'timestamp':
                return parseInt(source.getTime()/1000);
                break;
            case 'newDate':
                return source;
                break;
            case 'Y/m/d':
                return source.getFullYear() + '/'
                    + checkTime(source.getMonth()+1)
                    + '/'
                    + checkTime(source.getDate());
                break;
        };
    },
};

/**
 * 打开emoji弹窗
 * @param string/array img src字符串或src数组
 */
window.chooseEmoji = function () {
    layer.open({
        type: 2,
        title: "选择Emoji",
        shadeClose: false,
        shade: 0.8,
        area: ['80%', '80%'],
        content: ["/admin/emoji/index"] //这里content是一个URL，如果你不想让iframe出现滚动条，你还可以content: ['http://sentsin.com', 'no']
    });
};

window.viewArticle = function (html = '') {
    html = "<div style='margin: 20px;line-height: 2em;overflow-wrap:anywhere;'>" + html + '</div>';
    layer.open({
        type: 1,
        title: "内容查看",
        content: html,
        shadeClose: false,
        shade: 0.8,
        area: ['80%', '80%'],
    });
};

window.jump = function (href = '', target = '_self') {
    if(target === '_self'){
        location.href = href;
    }else{
        window.open(href);
    }
};

/**
 * 预览图片
 * @param string/array img src字符串或src数组
 */
window.viewImg = function (img) {
    var data = [];
    if(typeof img === 'string'){
        data = [ {"src": img}];
    }else{
        for(var i in img){
            data.push({"src": img[i]})
        }
    }
    layui.use('layer', function () {
        layer.photos({
            photos: {data: data}
        });
    });
};

//全局字典
window.kyDicts = {
    emptyData: '<div style="padding: 18px 0;"><p>暂无数据</p>,<p>本系统由 DaoAdmin 强力驱动</p></div>'
};

//表格单元渲染
window.templets = {
    pictures : function (srcArr=[]) {
        var html = '';
        srcArr.forEach((src) => {
            html += ('<img style="width: 50px;margin: 2px;" src="'+src+'" class="js-view-img" onclick="viewImg(\''+src+'\');">');
        });
        return html;
    },
    picture : function (src = '') {
        return '<img style="width: 50px;" src="'+src+'" class="js-view-img" onclick="viewImg(\''+src+'\');">';
    }
    , video : function (src = '') {
        return '<a href="'+src+'" class="layui-btn layui-btn-xs" target="_blank">预览</a>';
    }
    , article : function (html = '') {
        return '<div><a onclick="viewArticle(\''+html+'\');" class="layui-btn layui-btn-xs">点击查看</a></div>';
    }
};

//请求服务端
window.requestPost = function(url, params, callback, sync = false, loading = true){
    layui.use(['layer', 'jquery'], function () {
        var $ = layui.jquery
            , layer = layui.layer;

        if(sync) $.ajaxSettings.async = false;
        var loadingIndex;
        if(loading) loadingIndex = top.layer.load('数据提交中...');
        $.post(url, params, function(res){
            if(res.code === 1){
                if(res.msg){
                    layer.msg(res.msg, {time: 1500}, function () {
                        callback && typeof callback !== 'undefined' && callback(res);
                    });
                }else{
                    callback && typeof callback !== 'undefined' && callback(res);
                }
            }else{
                layer.alert(res.msg);
            }
        }, 'json').complete(function () {
            loading && top.layer.close(loadingIndex);
        }).error(function (xhr, status, info) {
            layer.alert('系统繁忙，请刷新重试或联系管理员');
        });
    });
};

var $ = layui.jquery, layer = layui.layer;
$(document).ready(function () {
    $("[layer-open]").on('click', function () {
        var type = parseInt($(this).prop('layer-open')),
                title = $(this).data('title'),
                that = this;
        if(!type) type = 2;
        switch (type) {
            case 4:
                var follow = $(this).data('follow'),
                        position = $(this).data('position');
                if(!follow){
                    follow = that
                }
                if(!position){
                    position = 1
                }
                layer.tips(title, follow, {
                    tips: position
                });
                break;
            default:
                var area = $(this).data('area');
                if(area){
                    area = area.split(',');
                }else{
                    area = ['95%', '95%'];
                }
                layer.open({
                    title: title,
                    type: type,
                    shade: 0.2,
                    area: area,
                    content: $(this).data('content'),
                });
                break;
        }
    });

    if(typeof(ClipboardJS) !== 'undefined'){
        //复制: use: <span class='js-clipboard' data-clipboard-target='#目标容器id'>这是触发元素</span>
        var clipboard = new ClipboardJS('.js-clipboard');
        clipboard.on('success', function (e) {
            layer.tips('复制成功', $(e.trigger), {
                tips: [2, '#1e9fff'],
                time: 2000
            });
            e.clearSelection();
        });
        clipboard.on('error', function (e) {
            console.error('Action:', e.action);
            console.error('Trigger:', e.trigger);
        });
    }
});