/**
 * 分步骤的进度条.
 * 渲染：stepprogress.render(param)
 * param = {
 * 		elem: '#stepProgressId',
 * 		width: '100%',
 * 		position: 0,		// 位置从0开始，即第一个step的position是0
 * 		stepItems: [{
 * 			title: '开始',
 * 			code: 'start'
 * 		},{
 * 			title: '读入基础数据',
 * 			code: 'readBasicData'
 * 		},{
 * 			title: '生成报表',
 * 			code: 'buildReport'
 * 		}]
 * }
 * 下一步：stepprogress.next(id)
 * 上一步：stepprogress.pre(id)
 * 指定当前位置：stepprogress.move(id, position)
 * 取得当前位置：getPosition(id)
 * 取得实例的options：getOptions(id)
 * 取得当前stepItem对象：getCurrentStepItem(id)
 */
layui.define(['laytpl', 'jquery'], function (exports) {
    var $ = layui.jquery;
    var laytpl = layui.laytpl;
	// 控件名称    
    var MOD_NAME = 'stepprogress';

	// 控件主体模板
	var TPL_MAIN = [
		'<div class="layui-step-progress">',
			'<div class="layui-step-item"></div>',
		'{{# layui.each(d.steps, function(index, item){ }}',
			'{{# ',
				'let stepStatusCls = index < d.position ? "layui-step-item-done" : index == d.position ? "layui-step-item-active" : "layui-step-item-inactive"; ',
			' }}',
			'<div class="layui-step-item {{= stepStatusCls }}">',
			'{{# if(index < d.steps.length - 1){ }}',
				'<div class="layui-step-item-tail">',
					'<i></i>',
				'</div>',
			'{{# } }}',
				'<div class="layui-step-item-head">',
					'<i class="layui-icon">{{= stepStatusCls == "layui-step-item-done" ? "" : index + 1 }}</i>',
				'</div>',
				'<div class="layui-step-item-title">{{= item.title }}</div>',
			'</div>',
		'{{# }); }}',
		'</div>'
	].join('');
	
	// 操作当前实例
	var thisModule = function(){
		var that = this
		,options = that.config
		,id = options.id || that.index;

		thisModule.that[id] = that; //记录当前实例对象

		return {
			config: options
			//重置实例
			,reload: function(options){
				that.reload.call(that, options);
			}
		}
	};
	//记录所有实例
	thisModule.that = {}; //记录所有实例对象
	//获取当前实例对象
	thisModule.getThis = function(id){
		var that = thisModule.that[id];
		if(!that) hint.error(id ? (MOD_NAME +' instance with ID \''+ id +'\' not found') : 'ID argument required');
		return that
	};
	//获取当前实例对象的options
	thisModule.getThisOptions = function(id){
		let that = thisModule.getThis(id);
		if(!that) return;
		return that.config;
	};
	
	// 构造器
	var Class = function(options){
		var that = this;
		that.index = ++stepprogress.index;
		that.config = $.extend({}, that.config, stepprogress.config, options);
		that.render();
	};
	
	// 默认配置
	Class.prototype.config = {
		width: '100%',
		position: 0
	};
	
	// 渲染控件
	Class.prototype.render = function() {
		let that = this
		,options = that.config;

		let elem = $(options.elem);
		if(!elem[0]) return;
		
		// 保存当前实例的ID
		options.id = options.id || elem.attr('id') || that.index;
		// 设置进度条显示宽度
		elem.css('width', options.width);
		// 保存当前进度的进度位置
		elem.data('current-step', options.position);
    
		// 渲染Dom
		renderDom(options.elem, options.stepItems, options.position);
    
		that.events(); //事件
	}

	//重载实例
	Class.prototype.reload = function(options){
		let that = this;

		//防止数组深度合并
		layui.each(options, function(key, item){
			if(layui.type(item) === 'array') delete that.config[key];
		});

		that.config = $.extend(true, {}, that.config, options);
		that.render();
	};

	// 事件
	Class.prototype.events = function(){
		let that = this
		,options = that.config
		,elem = $(options.elem);
		// 注册事件
	};
	
	// 外部接口
    var stepprogress = {
		config: {},
		index: layui[MOD_NAME] ? (layui[MOD_NAME].index + 10000) : 0,

        // 渲染进度条
        render: function (param) {
			let inst = new Class(param);
			return thisModule.call(inst);
        },
		// 重载进度条
		reload: function(id, options){
			let that = thisModule.that[id];
			that.reload(options);
			return thisModule.call(that);
		},
        // 下一步
        next: function (id) {
			let options = thisModule.getThisOptions(id)
			,elem = $(options.elem);
			
			let position = elem.data('current-step');
			// 当position == options.stepItems.length，表示所有步骤均已经完成，
			// 此时不能再向下一步前进
			if (position >= options.stepItems.length) {
				return;
			}
			// 移动到指定步骤
			moveToStep(options, ++position)
        },
        // 上一步
        pre: function (id) {
			let options = thisModule.getThisOptions(id)
			,elem = $(options.elem);
			
			let position = elem.data('current-step');
			position--;
			// position == -1 时，进度条处于“未开始”状态
			// 处于“未开始”状态时，不能再向上一步移动
			if (position < -1) {
				return;
			}
			// 移动到指定步骤
			moveToStep(options, position)
        },
		// 移动到指定位置
		// postion: 移动到目标位置，位置从0开始
		move: function (id, position) {
			let options = thisModule.getThisOptions(id);

			let targetPos = position || 0;
			if (/^(\-)?[0-9]*$/.test(targetPos)) {
				targetPos = Math.max(targetPos, -1);
				targetPos = Math.min(targetPos, options.stepItems.length);
				// 移动到指定步骤
				moveToStep(options, targetPos)
			}
		},
		// 取得实例的进度位置
		getPosition: function (id) {
			let options = thisModule.getThisOptions(id);
			return options.position;
		},
		// 取得实例的options对象
		getOptions: function(id) {
			let options = thisModule.getThisOptions(id);
			return options;
		},
		// 取得当前StepItem
		getCurrentStepItem: function(id) {
			let options = stepprogress.getOptions(id);
			return options.stepItems[options.position];
		},

		//设置全局项
		set: function(options){
			let that = this;
			that.config = $.extend({}, that.config, options);
			return that;
		},
        // 注册事件
        on: function(events, callback){
			return layui.onevent.call(this, MOD_NAME, events, callback);
		}
    };

    // 添加步骤条dom节点
    function renderDom(elem, stepItems, position) {
		// 生成progress step dom
        let stepDiv = laytpl(TPL_MAIN).render({
			steps: stepItems,
			position: position
		});
		let stepDivDom = $(stepDiv);
        // 动态设置每步的显示宽度
        let width = 100 / (stepItems.length);
        stepDivDom.find('.layui-step-item').css('width', width + '%');
		stepDivDom.find('.layui-step-item:first').css('width', (width * 1 / 3) + '%');
        stepDivDom.find('.layui-step-item:last').css('width', (width * 2 / 3) + '%');
        $(elem).empty().prepend(stepDivDom);
    };

	/**
	 * 移动到指定的step.
	 * options: 进度条的选项参数
	 * postion: 要移动到的目标位置,从0开始
	 */
	function moveToStep(options, position) {
		let elem = $(options.elem);
		let filter = elem.attr('lay-filter') ? elem.attr('lay-filter') : elem.attr('id');
		options.position = position;
		// 更新进度条
		renderDom(elem, options.stepItems, options.position);
		// 保存当前所在步骤
		elem.data('current-step', options.position);
		// 触发进度条变化事件
		layui.event.call(this, MOD_NAME, 'change(' + filter + ')', options);
	}
	
	// 加载css
	layui.link(layui.cache.base + "stepprogress/stepprogress.css");

    exports(MOD_NAME, stepprogress);
});
