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
    emptyData: '<div style="padding: 18px 0;"><p>暂无数据</p>,<p>本系统由 KyAdmin v1.0.0 强力驱动</p></div>'
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
};

//请求服务端
window.requestPost = function(url, params, callback, sync = false){
    layui.use(['layer', 'jquery'], function () {
        var $ = layui.jquery
            , layer = layui.layer;

        if(sync) $.ajaxSettings.async = false;
        var loadingIndex = layer.load(1);
        $.post(url, params, function(res){
            if(res.code === 1){
                layer.msg(res.msg, {time: 1500}, function () {
                    callback && typeof callback !== 'undefined' && callback(res);
                });
            }else{
                layer.alert(res.msg);
            }
        }, 'json').complete(function () {
            layer.close(loadingIndex);
        }).error(function (xhr, status, info) {
            layer.alert('系统繁忙，请刷新重试或联系管理员');
        });
    });
};

layui.use(['layer', 'jquery'], function () {
    var layer = layui.layer
        ,$ = layui.jquery;


});