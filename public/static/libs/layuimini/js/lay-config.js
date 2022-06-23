/**
 * date:2019/08/16
 * author:Mr.Chung
 * description:此处放layui自定义扩展
 * version:2.0.4
 */

window.rootPath = (function (src) {
    src = document.scripts[document.scripts.length - 1].src;
    return src.substring(0, src.lastIndexOf("/") + 1);
})();

layui.config({
    base: rootPath + "lay-module/",
    version: true
}).extend({
    excel: "excel/excel", //excel 操作  https://gitee.com/wangerzi/layui-excel
    selectN: "selectN/selectN", // 多级联动  https://fly.layui.com/extend/selectN,selectM/
    miniAdmin: "layuimini/miniAdmin", // layuimini后台扩展
    miniMenu: "layuimini/miniMenu", // layuimini菜单扩展
    miniTab: "layuimini/miniTab", // layuimini tab扩展
    miniTheme: "layuimini/miniTheme", // layuimini 主题扩展
    miniTongji: "layuimini/miniTongji", // layuimini 统计扩展
    step: 'step-lay/step', // 分步表单扩展
    treeTable: 'treetable-lay/treeTable', //table树形扩展  https://gitee.com/whvse/treetable-lay
    tableSelect: 'tableSelect/tableSelect', // table选择扩展
    iconPickerFa: 'iconPicker/iconPickerFa', // fa图标选择扩展
    echarts: 'echarts/echarts', // echarts图表扩展
    echartsTheme: 'echarts/echartsTheme', // echarts图表主题扩展
    formSelects: 'formSelects/formSelects-v4',  //下拉框多选  https://hnzzmsf.github.io/example/example_v4.html
    layarea: 'layarea/layarea', //  省市县区三级联动下拉选择器
});
