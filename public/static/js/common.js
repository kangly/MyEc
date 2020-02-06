$(function() {
    "use strict";
    var url = window.location + "";
    var path = url.replace(window.location.protocol + "//" + window.location.host + "/", "");
    var element = $('ul#sidebarnav a').filter(function() {
        return this.href === url || this.href === path;// || url.href.indexOf(this.href) === 0;
    });
    element.parentsUntil(".sidebar-nav").each(function (index){
        if($(this).is("li") && $(this).children("a").length !== 0){
            $(this).children("a").addClass("active");
            $(this).parent("ul#sidebarnav").length === 0
                ? $(this).addClass("active")
                : $(this).addClass("selected");
        }else if(!$(this).is("ul") && $(this).children("a").length === 0){
            $(this).addClass("selected");
        }else if($(this).is("ul")){
            $(this).addClass('in');
        }
    });
    element.addClass("active");
    $('#sidebarnav a').on('click', function (e) {
        if (!$(this).hasClass("active")) {
            // hide any open menus and remove all other classes
            $("ul", $(this).parents("ul:first")).removeClass("in");
            $("a", $(this).parents("ul:first")).removeClass("active");
            // open our new menu and add the open class
            $(this).next("ul").addClass("in");
            $(this).addClass("active");
        }else if ($(this).hasClass("active")) {
            $(this).removeClass("active");
            $(this).parents("ul:first").removeClass("active");
            $(this).next("ul").removeClass("in");
        }
    });
    $('#sidebarnav >li >a.has-arrow').on('click', function (e) {
        e.preventDefault();
    });
});

$(function() {
    "use strict";
    $(".left-sidebar").hover(
        function() {
            $(".navbar-header").addClass("expand-logo");
        },
        function() {
            $(".navbar-header").removeClass("expand-logo");
        }
    );
    $(".nav-toggler").on('click', function() {
        $("#main-wrapper").toggleClass("show-sidebar");
        $(".nav-toggler i").toggleClass("ti-menu");
    });
    $(".nav-lock").on('click', function() {
        $("body").toggleClass("lock-nav");
        $(".nav-lock i").toggleClass("mdi-toggle-switch-off");
        $("body, .page-wrapper").trigger("resize");
    });
    $(".search-box a, .search-box .app-search .srh-btn").on('click', function() {
        $(".app-search").toggle(200);
        $(".app-search input").focus();
    });

    $(function() {
        $(".service-panel-toggle").on('click', function() {
            $(".customizer").toggleClass('show-service-panel');
        });
        $('.page-wrapper').on('click', function() {
            $(".customizer").removeClass('show-service-panel');
        });
    });

    $('.floating-labels .form-control').on('focus blur', function(e) {
        $(this).parents('.form-group').toggleClass('focused', (e.type === 'focus' || this.value.length > 0));
    }).trigger('blur');

    $('body').delegate('[data-toggle="tooltip"]','mouseover',function(event){
        $(this).tooltip('show');
    });
    $('#open_new_win').attr('href',location.href);

    $("body, .page-wrapper").trigger("resize");
    $(".page-wrapper").show();

    $(".list-task li label").click(function() {
        $(this).toggleClass("task-done");
    });

    var setsidebartype = function() {
        var width = (window.innerWidth > 0) ? window.innerWidth : this.screen.width;
        if (width < 1170) {
            $("#main-wrapper").attr("data-sidebartype", "mini-sidebar");
        } else {
            $("#main-wrapper").attr("data-sidebartype", "full");
        }
    };
    $(window).ready(setsidebartype);
    $(window).on("resize", setsidebartype);

    $('.sidebartoggler').on("click", function() {
        $("#main-wrapper").toggleClass("mini-sidebar");
        if ($("#main-wrapper").hasClass("mini-sidebar")) {
            $(".sidebartoggler").prop("checked", !0);
            $("#main-wrapper").attr("data-sidebartype", "mini-sidebar");
        } else {
            $(".sidebartoggler").prop("checked", !1);
            $("#main-wrapper").attr("data-sidebartype", "full");
        }
    });
});

var _sub_tab_opener_Page_id;
var itn_tab_Page_Idx = 1;
var itn_tabContainerId_default = "tab_content";
var itn_tabPageContainerId_default = "tab_content_page";
var _tab_default_top_scroll_val = -1;
var _tab_link_page_id = "href";
var _tab_link_page_url = "data-page";
var _default_tab_page_id = '#tab1 ';//当前显示标签页id,默认#tab_home
var _closing_tab_page_id;//正在关闭(与_current相同)，或已经关闭（上一个）tabId
var _current_tab_page_id = _default_tab_page_id;
var _last_show_tab_page_id;//上一个显示的标签，默认与_current_tab_page_id相同
var _activating_tab_page_id;//马上显示(一定会显示),或已经显示(显示后与_current相同)tabId暂时未处理新建tab时,对_activating_tab_page_id的赋值
var _pad_adv_filter_id = "_pad_adv_filter_id";//数据列表检索排序字符串保存的键值(值以$.data的方式保存在_current_tab_page_id对应的对象上)
var _pad_search_params_id = "_pad_search_params_id";//数据列表检索条件数据
var _pad_page_base_params_id = "_pad_page_base_params_id";//页面初次加载时保存参数
var _pad_grid_page_size = "gridPageSize";
var _grid_row_selected_row_ids = 'row_selected_ids';//当前被选中的行id保存到container
var _operate_is_processing = 0;//系统tip标识

function _tab_load(tabLinkA,isLoadNewTab,params,callback){
    var jLinkA = $(tabLinkA);
    var pageId = jLinkA.attr(_tab_link_page_id);
    if(pageId==''){
        return;
    }
    _tab_load_page_content(jLinkA,params,callback,isLoadNewTab);
}

//给指定元素加载内容
function _p_a_load(url,pageContainerId,keepParams,data,callBack,isGridInit){
    _pad_all_loadPage(url,pageContainerId,keepParams,data,callBack,isGridInit);
}

function _load_tab_page_content(_tab_page_tabA,params,callback){
    if(!_tab_page_tabA){
        _tab_page_tabA = $('#myTab a[data-page]:first');
    }
    _tab_load_page_content(_tab_page_tabA,params,callback);
}

//页面内的tab元素加载
function _tab_load_page_content(_tab_page_tabA,params,callBack,isLoadNewTab){
    var _tab_page_tabUl;
    var _tab_page_tabLi;
    if(_tab_page_tabA && _tab_page_tabA.length>0){
        _tab_page_tabLi = _tab_page_tabA.parent();
        _tab_page_tabUl = _tab_page_tabLi.parent();
    }else{
        _tab_page_tabUl = $('#'+itn_tabContainerId_default+'>ul.nav-tabs');
        _tab_page_tabLi	= _tab_page_tabUl.children('li:first');
        _tab_page_tabA = _tab_page_tabLi.find('a:first');
        //这种情况应该是发生在页面初次加载默认tab时，给_current_tab_page_id赋值
        _current_tab_page_id = _tab_page_tabA.attr(_tab_link_page_id) + ' ';
    }
    _tab_page_tabA.attr('load_count',1);
    var _tab_page_load_url = _tab_page_tabA.attr(_tab_link_page_url);
    var _tab_page_load_container = _tab_page_tabA.attr(_tab_link_page_id);
    var thisTabCallBack = new _tab_after_load_event(callBack);
    var keepParams = true;
    if(isLoadNewTab)
        keepParams = false;
    _pad_all_loadPage(_tab_page_load_url,_tab_page_load_container,keepParams,params,function(pageContainerId){
        if(thisTabCallBack.userCallBackFunc)
            thisTabCallBack.userCallBackFunc(pageContainerId);
        //switch to new tab，放在这里可以避免每次没加载完html内容就显示时，页面会跳到最上面
        _tab_page_tabA.tab('show');
        //thisTabCallBack.scrollContent(pageContainerId);
    });
}

function _tab_after_load_event(userCallBack){
    this.userCallBackFunc = userCallBack;
    this.scrollContent=_tab_scrollTabContent;
}

//滚动到tab内容顶部
function _tab_scrollTabContent(){
    if(_tab_default_top_scroll_val!=-1)
        $(window).scrollTop(_tab_default_top_scroll_val);
}

function _pad_all_reload_my_container(obj,more_parems,callback){
    var jobj = find_jquery_object(obj);
    var parent_container = jobj.parents('.tab-pane.active');
    _pad_all_reloadPage(parent_container.attr('id'), more_parems, callback);
}

function _pad_all_reloadPage(pageContainerId,more_parems,callback){
    var container = find_jquery_object(pageContainerId);
    if(container){
        var url = container.attr('content_url');
        _pad_all_loadPage(url, pageContainerId, true, more_parems, callback);
    }
}

//keepParams 是否保留container上缓存的参数，首次加载时一般为false
function _pad_all_loadPage(url,pageContainerId,keepParams,data,callBack,isGridInit){
    if(typeof(pageContainerId)=='string'){
        if(pageContainerId.substring(0,1)=='#')
            pageContainerId = pageContainerId.substring(1);
    }
    var containerObj = find_jquery_object(pageContainerId);
    if(!data){
        data = {};
    }
    var initPageSize = containerObj.attr(_pad_grid_page_size);
    if(initPageSize && initPageSize>0){
        //有初始化containerObj中grid的pageSize
        data = _pad_add_param_to_post_data(data,'gridPageSize',initPageSize);
    }
    //将容器id传入到action的loadPageId，在页面初始化脚本中可以使用
    if(url.indexOf('loadPageId')==-1 && (!data || !data.loadPageId) && (typeof(data)!='string'||data.indexOf('loadPageId')==-1)){
        data = _pad_add_param_to_post_data(data,'loadPageId',pageContainerId);
    }
    if(isGridInit && data){
        containerObj.data(_pad_search_params_id, data);
    }
    if(!keepParams)
        _pad_clear_container_old_data(pageContainerId);

    _pad_clear_container_old_data(pageContainerId);
    var pageBaseParams = containerObj.data(_pad_page_base_params_id);
    var param_idx = url.indexOf('?');
    if(param_idx!=-1){
        var param_idx_2 = url.indexOf('#');
        var params_str = '';
        if(param_idx_2!=-1){
            params_str = url.substring(param_idx+1,param_idx_2);
        }else{
            params_str = url.substring(param_idx+1);
        }
        url = url.substring(0,param_idx);
        if(params_str.length>0){
            if(!data){
                data = {};
            }else if(Object.prototype.toString.call(data) === "[object String]"){
                params_str = params_str + '&' + data;
                data = {};
            }
            var param_arr = params_str.split('&');
            for(var pi=0;pi<param_arr.length;pi++){
                var p_key_val = param_arr[pi].split('=');
                if(p_key_val.length>1){
                    data[p_key_val[0]] = p_key_val[1];
                }
            }
        }
    }
    if(!pageBaseParams && data){
        pageBaseParams = data;
    }else{
        pageBaseParams = _pad_mergeJsonObject(pageBaseParams, data);
    }
    containerObj.data(_pad_page_base_params_id, pageBaseParams);
    $.ajax({
        url:url,
        type: "post",
        data: pageBaseParams,
        cache:false,
        beforeSend:function(XMLHttpRequest){},
        success:function(html){
            if(html && isJson(html)){
                //返回错误异常
                if(html.error){
                    alert(html.error);
                    _hide_top_loading();
                }
                return;
            }
            _pad_add_pageInfo_to_loadPageHtml(html, pageContainerId, url);
            //处理如果html中有grid，为grid加上containerId
            var tempIdx1 = html.indexOf('<table');
            if(tempIdx1!=-1){
                tempIdx1 = tempIdx1 + 6;
                var tempIdx2 = html.indexOf('>',tempIdx1);
                var tempIdx3 = html.indexOf('table',tempIdx1);
                if(tempIdx3!=-1 && tempIdx3< tempIdx2){
                    //尝试准确的定位"table.table:first"的table
                    html = html.substring(0,tempIdx1) + ' containerId="#'+pageContainerId+'"' + html.substring(tempIdx1);
                }
            }
            containerObj.html(html);
            containerObj.trigger('new_content_load');
            _update_pager_click_event(containerObj);
            var anyGrid = _pad_findGridByContainerId(pageContainerId);
            if(anyGrid.length>0){
                add_event_for_jm_table(anyGrid);
            }
            _pad_add_clearable_input_btn(pageContainerId);//添加clearable输入框清除按键
            if(callBack){
                callBack(pageContainerId);
            }
        }
    }).always(function(){});
}

function find_jquery_object(obj){
    var jObj = null;
    if(obj instanceof jQuery){
        jObj = obj;
    }else{
        if(typeof(obj)=='string'){
            if(obj.substring(0,1)!='#')
                obj = '#' + obj;
            jObj = $(obj);
        }else{
            jObj = $(obj);
        }
    }
    return jObj;
}

function _pad_add_param_to_post_data(data, paramName, paramValue){
    if(!data || data.length==0){
        data = paramName+'='+paramValue;
    }else{
        if(typeof(data)=='string'){
            if(data!=''){
                if(data.substring(0,1)=='&'){
                    data = data.substring(1);
                }
                if(data.substring(data.length-1)=='&'){
                    data = data.substring(0,data.length-1);
                }
                if(data!=''){
                    var param_arr = data.split('&');
                    data = {};
                    for(var pi=0;pi<param_arr.length;pi++){
                        var p_key_val = param_arr[pi].split('=');
                        if(p_key_val.length>1){
                            data[p_key_val[0]] = p_key_val[1];
                        }
                    }
                }
            }
        }
        data[paramName] = paramValue;
    }
    return data;
}

function _pad_clear_container_old_data(containerId){
    if(containerId.substring(0,1)!='#')
        containerId = '#' + containerId;

    var container = $(containerId);
    container.attr('content_url','');
    //container.removeAttr(_pad_grid_page_size);//不去掉就没法设置pageSize
    container.removeAttr('pageNo');
    container.removeData(_pad_adv_filter_id);
    container.removeData(_pad_search_params_id);
    container.removeData(_pad_page_base_params_id);
    try{
        container.removeData(_grid_row_selected_row_ids);
    }catch(e){}
    try{
    }catch(e){
        alert(e);
    }
}

function _pad_mergeJsonObject(baseData, newData){
    if(!baseData)
        return newData;
    if(!newData)
        return baseData;

    var resultJsonObject={};
    for(var attr in baseData){
        resultJsonObject[attr]=baseData[attr];
    }
    for(var attr in newData){
        resultJsonObject[attr]=newData[attr];
    }
    return resultJsonObject;
}

function _show_top_loading(){
    $('#top_loading_mark').fadeIn('fast');
}

function _hide_top_loading(){
    $('#top_loading_mark').fadeOut('fast');
}

function _pad_add_pageInfo_to_loadPageHtml(jqHtml, pageContainerId, url){
    var container = find_jquery_object(pageContainerId);
    container.attr('content_url',url);
}

/**
 * 无刷新分页
 * @param container
 * @private
 */
function _update_pager_click_event(container){
    var pagerObjs = container.find('.pagination.in_tab');
    pagerObjs.each(function(){
        $(this).find('a').each(function() {
            $(this).click(function (event) {
                var url = $(this).attr('href');
                _pad_all_loadPage(url,container.attr('id'),true);
                return false;//阻止链接跳转
            });
        });
    });
}

function _pad_findGridByContainerId(containerId){
    if(containerId.substring(0,1)!='#')
        containerId = '#' + containerId;
    var containerObj = $(containerId);
    var anyGrid = containerObj.find('table.table:first');
    if(anyGrid.length>0){
        var ori_containerId = anyGrid.attr('containerId');
        if(!ori_containerId || ori_containerId==''){
            anyGrid.attr('containerId',containerId);
        }
    }
    return anyGrid;
}

function add_event_for_jm_table(gridTable){
    var gridContainerId = gridTable.attr('containerId');
    add_event_for_jm_table_sort(gridContainerId);
}

function add_event_for_jm_table_sort(containerId){
    if(containerId.substring(0,1)!='#')
        containerId = '#' + containerId;

    var container = $(containerId);
    var page_params = container.data(_pad_page_base_params_id);
    if(page_params && page_params.sort_column){
        var on_sorting = container.find('[sort_column="'+page_params.sort_column+'"]');
        if(on_sorting.length==1){
            on_sorting.attr('sort_type', page_params.sort_type);
        }
    }
    container.find('.tab_sorter').each(function(){
        var column = $(this);
        var sort_column = column.attr('sort_column');
        if(sort_column!=''){
            var column_table = column.parents('[content_url]:first');
            var sort_type = column.attr('sort_type');
            column_table.removeClass('icon-sort-up icon-sort-down');
            if(sort_type=='asc'){
                column.addClass('icon-sort-up');
            }else if(sort_type=='desc'){
                column.addClass('icon-sort-down');
            }else{
                column.addClass('icon-sort');
            }
            column.unbind('click').bind('click',function(){
                _show_top_loading();
                var thiscolumn = $(this);
                var thissort = thiscolumn.attr('sort_column');
                var thistype = thiscolumn.attr('sort_type');
                if(thiscolumn.is('.icon-sort-down')){
                    thistype = 'asc';
                }else if(thiscolumn.is('.icon-sort-up')){
                    thistype = '';
                }else{
                    thistype = 'desc';
                }
                thiscolumn.attr('sort_type',thistype);
                var table_id = column_table.attr('id');
                var table_url = column_table.attr('content_url');
                var params = column_table.data(_pad_page_base_params_id);
                if(!params){
                    params = {};
                }
                params = _pad_add_param_to_post_data(params,'sort_column',thissort);
                params = _pad_add_param_to_post_data(params,'sort_type',thistype);
                _p_a_load(table_url, table_id, null, params, function(){
                    _hide_top_loading();
                });
            });
        }
    });
}

function _pad_add_clearable_input_btn(containerId){
    var container = find_jquery_object(containerId);
    container.find('input.clearable').each(function(){
        $(this).wrap('<div class="clearable_container"></div>');
        var clear_btn = $('<i class="icon-remove clear_btn"></i>');
        clear_btn.click(function(){
            $(this).prev('input').val('');
        });
        $(this).after(clear_btn);
    });
}

//显示新tab
function _tabShow(title,contentUrl,pageId,tabContainerId,tabPageContainerId,params,callBack){
    if(!tabContainerId)tabContainerId = itn_tabContainerId_default;
    if(!tabPageContainerId)tabPageContainerId = itn_tabPageContainerId_default;

    _sub_tab_opener_Page_id = _current_tab_page_id;

    var tabContainer = $('#'+tabContainerId);
    var tabList = tabContainer.find('ul:first');
    var tabPageContainer = $('#'+tabPageContainerId);
    if(!pageId){
        pageId = 'subPage_'+itn_tab_Page_Idx;
        itn_tab_Page_Idx++;
    }
    if(!title)title=pageId;
    var newTab;
    var newTabContent;
    var tabSwitcher;
    if($('#'+pageId).length>0){
        newTab = tabList.find("li[pageId='"+pageId+"']");
        tabSwitcher = newTab.find('a:first');
        //注释掉下面3行，并添加trigger和return，可以防止从新加载已有页面
        newTabContent = $('#'+pageId);
        tabSwitcher.text(title);
        tabSwitcher.attr(_tab_link_page_url,contentUrl);
        //tabSwitcher.trigger('click');
        //return;
    }else{
        newTab = $('<li pageId="'+pageId+'" class="sub"></li>');
        tabSwitcher = $('<a data-toggle="tab" '+_tab_link_page_id+'="#'+pageId+'" '+_tab_link_page_url+'="'+contentUrl+'">'+title+' </a>');
        newTab.append(tabSwitcher);
        tabList.append(newTab);
        var tabClose = $('<span id="tab_close_'+ pageId +'" class="sub_close"></span>');
        newTab.append(tabClose);
        tabClose.bind('click',function(){
            var tabObj = $(this).parent();
            var tabListObj = tabObj.parent();
            var pageId = tabObj.attr('pageId');
            if(tabObj.hasClass('active') || tabObj.hasClass('current')){
                var tempHref = _last_show_tab_page_id;
                if(tempHref && tempHref.length>0)
                    tempHref = tempHref.substring(0,tempHref.length-1);
                var lastTabLink = tabListObj.find('li a['+_tab_link_page_id+'="'+tempHref+'"]');
                var prevTabLink;
                if(lastTabLink.length>0){
                    prevTabLink = lastTabLink;
                }else{
                    var prevTab = tabObj.prev();
                    prevTabLink = prevTab.find('a:first');
                }
                _closing_tab_page_id = _current_tab_page_id;
                if(prevTabLink && prevTabLink.length>0){
                    prevTabLink.tab('show');
                    //prevTabLink.trigger('click');
                    _activating_tab_page_id = prevTabLink.attr(_tab_link_page_id);
                }
            }
            tabObj.remove();
            $('#'+pageId).remove();
            if(tabListObj.children('li').length==1){
                _tab_default_top_scroll_val = -1;
            }
        });
        newTabContent = $('<div id="'+pageId+'" class="tab-pane new_tab" ></div>');
        tabPageContainer.append(newTabContent);
    }
    _tab_load_page_content(tabSwitcher,params,callBack); //load content for new tab
    if(tabList.hasClass('off_show')){
        tabList.slideDown('fast');
        tabList.removeClass('off_show');
        _tab_default_top_scroll_val = tabList.offset().top;
    }
    return pageId;
}

function isJson(obj){
    return typeof(obj) == "object" && Object.prototype.toString.call(obj).toLowerCase() == "[object object]" && !obj.length;
}

function isString(obj){
    return Object.prototype.toString.call(obj) === "[object String]"
}

function isFunction(obj){
    return typeof(obj)=='function' && Object.prototype.toString.call(obj)==='[object Function]'
}

/**
 * form 无刷新提交
 * @param formId
 * @param callback
 * @param url
 */
function submitTheForm(formId,callback,url){
    if(op_start()){
        $('#'+formId).ajaxSubmit({success: function (data) {
            op_end();
            if(data && data.error==0){
                if(data.error==0){
                    tip_success('添加/修改成功！',2);
                    if(url){
                        urlLocation(url);
                    }else{
                        callback && callback(data);
                    }
                }else{
                    tip(data.msg,5);
                }
            }else if(data == 'success'){
                tip_success('添加/修改成功！',2);
                if(url){
                    urlLocation(url);
                }else{
                    callback && callback(data);
                }
            }else if(data>0){
                tip_success('添加/修改成功！',2);
                if(url){
                    urlLocation(url);
                }else{
                    callback && callback(data);
                }
            }else if(data){
                if(!isJson(data))
                    tip_error(data,5);
            }else{
                tip_error('添加/修改失败，请稍后再试！',5);
            }
        }});
    }
}

/**
 * 去除空白字符并重新赋值
 * @param obj
 * @param is_assign
 * @returns {*|string}
 * @private
 */
function _trim_assign(obj,is_assign){
    var new_content = $.trim(obj.val());
    if(is_assign==1){
        obj.val(new_content);
    }else{
        if(new_content!=''){
            obj.val(new_content);
        }
    }
    return new_content;
}

/**
 * 阻止输入框回车默认跳转事件
 * @param objId
 * @param callback
 * @private
 */
function _add_enter_event(objId,callback){
    $('#'+objId).bind('keypress',function(e){
        var ev = document.all ? window.event : e;
        if(ev.keyCode==13) {
            if(callback){
                callback();
                return false;
            }
        }
    });
}

/**
 * 消息提示
 * @param msg_or_json
 * @param time
 * @param url
 * @param closeFunc
 * @param tip_type
 * @returns {*}
 */
function tip(msg_or_json, time, url, closeFunc, tip_type){
    time = time?time*1000:2000;
    var str = '';
    if(isString(msg_or_json)){
        str = msg_or_json;
    }else if(isJson(msg_or_json)){
        if(msg_or_json.msg){
            str = msg_or_json.msg;
        }
    }
    var class_name = 'info';
    if(tip_type=='success'){
        class_name = 'success';
    }else if(tip_type=='error'){
        class_name = 'error';
    }else if(tip_type=='alarm'){
        class_name = 'alarm';
    }else if(tip_type=='message'){
        class_name = 'message';
    }
    var tip_title = '操作提示';
    if(tip_type=='success'){
        tip_title = '操作成功';
    }else if(tip_type=='error'){
        tip_title = '操作异常';
    }else if(tip_type=='alarm'){
        tip_title = '系统提醒';
    }else if(tip_type=='message'){
        tip_title = '新消息通知';
    }
    var _unique_id = $.gritter.add({
        title:tip_title,
        text:str,
        time: time,
        class_name: class_name,
        after_open: function(e){},
        before_close:function(e){
            if(closeFunc){
                closeFunc();
            }
            if(url){
                setTimeout(function(){
                    window.location.href = url;
                },800);
            }
        },
        after_close:function(e){}
    });
    return _unique_id;
}

/**
 * 消息提示 start
 * @param text
 * @returns {boolean}
 */
function op_start(text){
    if(!text || text==''){
        text = '提交中...';
    }
    if(_operate_is_processing==0){
        _operate_is_processing = tip(text,100,null,function(){
            _operate_is_processing = 0;
        });
        _show_top_loading();
        return true;
    }else{
        tip('上次操作未结束',2);
        return false;
    }
}

/**
 * 消息提示 success
 * @param msg_or_json
 * @param time
 * @param closeFunc
 */
function tip_success(msg_or_json, time, closeFunc){
    if(!msg_or_json){
        msg_or_json = '操作成功';
    }
    if(!time || time<=0){
        time = 4;
    }
    if(!isFunction(closeFunc)){
        closeFunc = null;
    }
    tip(msg_or_json,time,null,closeFunc,'success');
}

/**
 * 消息提示 error
 * @param msg_or_json
 * @param time
 * @param closeFunc
 */
function tip_error(msg_or_json, time, closeFunc){
    if(!msg_or_json){
        msg_or_json = '操作失败';
    }
    if(!time || time<=0){
        time = 10;
    }
    if(!isFunction(closeFunc)){
        closeFunc = null;
    }
    tip(msg_or_json,time,null,closeFunc,'error');
}

//消息提示 end
function op_end(){
    $.gritter.remove(_operate_is_processing);
    _operate_is_processing = 0;
    _hide_top_loading();
}

layer.config({
    skin:'demo-class', //layer默认皮肤(全局)
});

/**
 * page弹出层
 * @param mark 窗口标识,相同标识只弹出一个窗口
 * @param title 窗口标题
 * @param url url地址,目前默认全部为get请求,格式:url?参数...
 * @param area ['宽','高']
 * @param offset 坐标,默认auto,即垂直水平居中
 * @param callback 保存成功后的回调方法
 * @private
 */
function _add_movable_popup(mark,title,url,area,offset,callback){
    _show_top_loading();
    if(mark && $('.layui-layer.layui-layer-page[identity="'+mark+'"]').length>0){
        //layer.close($('.layui-layer.layui-layer-page').attr('times'));
        $('.layui-layer.layui-layer-page[identity="'+mark+'"]').remove();
    }
    $.get(url,{},function(str){
        if(str){
            layer.open({
                type: 1,
                title: title,
                area: area?area:['900px','600px'],
                shade: 0,
                offset: offset?offset:'auto',
                content: str,
                btn: ['确定','关闭'],
                success: function(layero, index){
                    //弹层成功后的回调方法
                    $(layero).attr('identity',mark);
                    _hide_top_loading();
                },
                yes: function(index, layero){
                    //目前默认页面保存方法都是save方法
                    //执行回调方法并关闭弹层
                    save(function(){
                        if(callback){
                            callback();
                            setTimeout(function(){layer.close(index)},200);
                        }
                    });
                }
            });
        }else{
            tip_error('系统异常！',3);
        }
    },'html');
}

/**
 * 关闭layer所有弹出层
 * @private
 */
function _close_all_movable_popup(){
    layer.closeAll();
}

/**
 * 需用户确认的系统操作
 * @param title 提示信息
 * @param callback 回调函数
 * @private
 */
function _systemConfirm(title,callback){
    layer.confirm(title,{icon:3,title:'系统提示'},function(index){
        if(callback){
            callback();
        }
        layer.close(index);
    });
}

function _systemTips(obj){
    layer.open({
        title: '系統提示',
        content: $(obj).attr('data-original-title')
    });   
}