!function(e){"function"==typeof define&&define.amd?define(["jquery"],e):"object"==typeof exports?module.exports=e($||require("jquery")):e(jQuery)}(function(D){"use strict";var l="styler",s={idSuffix:"-styler",filePlaceholder:"Файл не выбран",fileBrowse:"Обзор...",fileNumber:"Выбрано файлов: %s",selectPlaceholder:"Выберите...",selectSearch:!1,selectSearchLimit:10,selectSearchNotFound:"Совпадений не найдено",selectSearchPlaceholder:"Поиск...",selectVisibleOptions:0,selectSmartPositioning:!0,locale:"ru",locales:{en:{filePlaceholder:"No file selected",fileBrowse:"Browse...",fileNumber:"Selected files: %s",selectPlaceholder:"Select...",selectSearchNotFound:"No matches found",selectSearchPlaceholder:"Search..."}},onSelectOpened:function(){},onSelectClosed:function(){},onFormStyled:function(){}};function o(e,t){this.element=e,this.options=D.extend({},s,t);t=this.options.locale;void 0!==this.options.locales[t]&&D.extend(this.options,this.options.locales[t]),this.init()}function I(e){var t,s;D(e.target).parents().hasClass("jq-selectbox")||"OPTION"==e.target.nodeName||D("div.jq-selectbox.opened").length&&(t=D("div.jq-selectbox.opened"),s=D("div.jq-selectbox__search input",t),e=D("div.jq-selectbox__dropdown",t),t.find("select").data("_"+l).options.onSelectClosed.call(t),s.length&&s.val("").keyup(),e.hide().find("li.sel").addClass("selected"),t.removeClass("focused opened dropup dropdown"))}o.prototype={init:function(){var e,t,s,i,l,N=D(this.element),P=this.options,H=!(!navigator.userAgent.match(/(iPad|iPhone|iPod)/i)||navigator.userAgent.match(/(Windows\sPhone)/i)),o=!(!navigator.userAgent.match(/Android/i)||navigator.userAgent.match(/(Windows\sPhone)/i));function A(){void 0!==N.attr("id")&&""!==N.attr("id")&&(this.id=N.attr("id")+P.idSuffix),this.title=N.attr("title"),this.classes=N.attr("class"),this.data=N.data()}N.is(":checkbox")?((e=function(){var e=new A,t=D('<div class="jq-checkbox"><div class="jq-checkbox__div"></div></div>').attr({id:e.id,title:e.title}).addClass(e.classes).data(e.data);N.after(t).prependTo(t),N.is(":checked")&&t.addClass("checked"),N.is(":disabled")&&t.addClass("disabled"),t.click(function(e){e.preventDefault(),N.triggerHandler("click"),t.is(".disabled")||(N.is(":checked")?(N.prop("checked",!1),t.removeClass("checked")):(N.prop("checked",!0),t.addClass("checked")),N.focus().change())}),N.closest("label").add('label[for="'+N.attr("id")+'"]').on("click.styler",function(e){D(e.target).is("a")||D(e.target).closest(t).length||(t.triggerHandler("click"),e.preventDefault())}),N.on("change.styler",function(){N.is(":checked")?t.addClass("checked"):t.removeClass("checked")}).on("keydown.styler",function(e){32==e.which&&t.click()}).on("focus.styler",function(){t.is(".disabled")||t.addClass("focused")}).on("blur.styler",function(){t.removeClass("focused")})})(),N.on("refresh",function(){N.closest("label").add('label[for="'+N.attr("id")+'"]').off(".styler"),N.off(".styler").parent().before(N).remove(),e()})):N.is(":radio")?((t=function(){var e=new A,t=D('<div class="jq-radio"><div class="jq-radio__div"></div></div>').attr({id:e.id,title:e.title}).addClass(e.classes).data(e.data);N.after(t).prependTo(t),N.is(":checked")&&t.addClass("checked"),N.is(":disabled")&&t.addClass("disabled"),D.fn.commonParents=function(){var e=this;return e.first().parents().filter(function(){return D(this).find(e).length===e.length})},D.fn.commonParent=function(){return D(this).commonParents().first()},t.click(function(e){e.preventDefault(),N.triggerHandler("click"),t.is(".disabled")||((e=D('input[name="'+N.attr("name")+'"]')).commonParent().find(e).prop("checked",!1).parent().removeClass("checked"),N.prop("checked",!0).parent().addClass("checked"),N.focus().change())}),N.closest("label").add('label[for="'+N.attr("id")+'"]').on("click.styler",function(e){D(e.target).is("a")||D(e.target).closest(t).length||(t.triggerHandler("click"),e.preventDefault())}),N.on("change.styler",function(){N.parent().addClass("checked")}).on("focus.styler",function(){t.is(".disabled")||t.addClass("focused")}).on("blur.styler",function(){t.removeClass("focused")})})(),N.on("refresh",function(){N.closest("label").add('label[for="'+N.attr("id")+'"]').off(".styler"),N.off(".styler").parent().before(N).remove(),t()})):N.is(":file")?((s=function(){var e=new A,i=N.data("placeholder");void 0===i&&(i=P.filePlaceholder);var t=N.data("browse");void 0!==t&&""!==t||(t=P.fileBrowse);var l=D('<div class="jq-file"><div class="jq-file__name">'+i+'</div><div class="jq-file__browse">'+t+"</div></div>").attr({id:e.id,title:e.title}).addClass(e.classes).data(e.data);N.after(l).appendTo(l),N.is(":disabled")&&l.addClass("disabled");var e=N.val(),o=D("div.jq-file__name",l);e&&o.text(e.replace(/.+[\\\/]/,"")),N.on("change.styler",function(){var e,t,s=N.val();N.is("[multiple]")&&(s="",0<(e=N[0].files.length)&&(void 0===(t=N.data("number"))&&(t=P.fileNumber),s=t=t.replace("%s",e))),o.text(s.replace(/.+[\\\/]/,"")),""===s?(o.text(i),l.removeClass("changed")):l.addClass("changed")}).on("focus.styler",function(){l.addClass("focused")}).on("blur.styler",function(){l.removeClass("focused")}).on("click.styler",function(){l.removeClass("focused")})})(),N.on("refresh",function(){N.off(".styler").parent().before(N).remove(),s()})):N.is('input[type="number"]')?((i=function(){var e=new A,t=D('<div class="jq-number"><div class="jq-number__spin minus"></div><div class="jq-number__spin plus"></div></div>').attr({id:e.id,title:e.title}).addClass(e.classes).data(e.data);N.after(t).prependTo(t).wrap('<div class="jq-number__field"></div>'),N.is(":disabled")&&t.addClass("disabled");var o,a,d,s=null,i=null;void 0!==N.attr("min")&&(o=N.attr("min")),void 0!==N.attr("max")&&(a=N.attr("max")),d=void 0!==N.attr("step")&&D.isNumeric(N.attr("step"))?Number(N.attr("step")):Number(1);function l(e){var t,s=N.val();D.isNumeric(s)||(s=0,N.val("0")),e.is(".minus")?t=Number(s)-d:e.is(".plus")&&(t=Number(s)+d);var i=(d.toString().split(".")[1]||[]).length;if(0<i){for(var l="1";l.length<=i;)l+="0";t=Math.round(t*l)/l}D.isNumeric(o)&&D.isNumeric(a)?o<=t&&t<=a&&N.val(t):D.isNumeric(o)&&!D.isNumeric(a)?o<=t&&N.val(t):(D.isNumeric(o)||!D.isNumeric(a)||t<=a)&&N.val(t)}t.is(".disabled")||(t.on("mousedown","div.jq-number__spin",function(){var e=D(this);l(e),s=setTimeout(function(){i=setInterval(function(){l(e)},40)},350)}).on("mouseup mouseout","div.jq-number__spin",function(){clearTimeout(s),clearInterval(i)}).on("mouseup","div.jq-number__spin",function(){N.change().trigger("input")}),N.on("focus.styler",function(){t.addClass("focused")}).on("blur.styler",function(){t.removeClass("focused")}))})(),N.on("refresh",function(){N.off(".styler").closest(".jq-number").before(N).remove(),i()})):N.is("select")?((l=function(){function j(t){var s,i,l=t.prop("scrollHeight")-t.outerHeight();t.off("mousewheel DOMMouseScroll").on("mousewheel DOMMouseScroll",function(e){s=e.originalEvent.detail<0||0<e.originalEvent.wheelDelta?1:-1,i=t.scrollTop(),(l<=i&&s<0||i<=0&&0<s)&&(e.stopPropagation(),e.preventDefault())})}var k=D("option",N),S="";function T(){for(var e=0;e<k.length;e++){var t=k.eq(e),s="",i="",l="",o="",a="",d="",r="",c="",n="";t.prop("selected")&&(i="selected sel"),t.is(":disabled")&&(i="disabled"),t.is(":selected:disabled")&&(i="selected sel disabled"),void 0!==t.attr("id")&&""!==t.attr("id")&&(o=' id="'+t.attr("id")+P.idSuffix+'"'),void 0!==t.attr("title")&&""!==k.attr("title")&&(a=' title="'+t.attr("title")+'"'),void 0!==t.attr("class")&&(r=" "+t.attr("class"),n=' data-jqfs-class="'+t.attr("class")+'"');var h,f=t.data();for(h in f)""!==f[h]&&(d+=" data-"+h+'="'+f[h]+'"');i+r!==""&&(l=' class="'+i+r+'"'),s="<li"+n+d+l+a+o+">"+t.html()+"</li>",t.parent().is("optgroup")&&(void 0!==t.parent().attr("class")&&(c=" "+t.parent().attr("class")),s="<li"+n+d+' class="'+i+r+" option"+c+'"'+a+o+">"+t.html()+"</li>",t.is(":first-child")&&(s='<li class="optgroup'+c+'">'+t.parent().attr("label")+"</li>"+s)),S+=s}}N.is("[multiple]")?o||H||function(){var e=new A,t=D('<div class="jq-select-multiple jqselect"></div>').attr({id:e.id,title:e.title}).addClass(e.classes).data(e.data);N.after(t),T(),t.append("<ul>"+S+"</ul>");var s=D("ul",t),l=D("li",t),i=N.attr("size"),e=s.outerHeight(),o=l.outerHeight();void 0!==i&&0<i?s.css({height:o*i}):s.css({height:4*o}),e>t.height()&&(s.css("overflowY","scroll"),j(s),l.filter(".selected").length&&s.scrollTop(s.scrollTop()+l.filter(".selected").position().top)),N.prependTo(t),N.is(":disabled")?(t.addClass("disabled"),k.each(function(){D(this).is(":selected")&&l.eq(D(this).index()).addClass("selected")})):(l.filter(":not(.disabled):not(.optgroup)").click(function(e){N.focus();var t,s,i=D(this);e.ctrlKey||e.metaKey||i.addClass("selected"),e.shiftKey||i.addClass("first"),e.ctrlKey||e.metaKey||e.shiftKey||i.siblings().removeClass("selected first"),(e.ctrlKey||e.metaKey)&&(i.is(".selected")?i.removeClass("selected first"):i.addClass("selected first"),i.siblings().removeClass("first")),e.shiftKey&&(s=t=!1,i.siblings().removeClass("selected").siblings(".first").addClass("selected"),i.prevAll().each(function(){D(this).is(".first")&&(t=!0)}),i.nextAll().each(function(){D(this).is(".first")&&(s=!0)}),t&&i.prevAll().each(function(){return!D(this).is(".selected")&&void D(this).not(".disabled, .optgroup").addClass("selected")}),s&&i.nextAll().each(function(){return!D(this).is(".selected")&&void D(this).not(".disabled, .optgroup").addClass("selected")}),1==l.filter(".selected").length&&i.addClass("first")),k.prop("selected",!1),l.filter(".selected").each(function(){var e=D(this),t=e.index();e.is(".option")&&(t-=e.prevAll(".optgroup").length),k.eq(t).prop("selected",!0)}),N.change()}),k.each(function(e){D(this).data("optionIndex",e)}),N.on("change.styler",function(){l.removeClass("selected");var t=[];k.filter(":selected").each(function(){t.push(D(this).data("optionIndex"))}),l.not(".optgroup").filter(function(e){return-1<D.inArray(e,t)}).addClass("selected")}).on("focus.styler",function(){t.addClass("focused")}).on("blur.styler",function(){t.removeClass("focused")}),e>t.height()&&N.on("keydown.styler",function(e){38!=e.which&&37!=e.which&&33!=e.which||s.scrollTop(s.scrollTop()+l.filter(".selected").position().top-o),40!=e.which&&39!=e.which&&34!=e.which||s.scrollTop(s.scrollTop()+l.filter(".selected:last").position().top-s.innerHeight()+2*o)}))}():function(){var e=new A,t="",s=N.data("placeholder"),i=N.data("search"),l=N.data("search-limit"),o=N.data("search-not-found"),a=N.data("search-placeholder"),r=N.data("smart-positioning");void 0===s&&(s=P.selectPlaceholder),void 0!==i&&""!==i||(i=P.selectSearch),void 0!==l&&""!==l||(l=P.selectSearchLimit),void 0!==o&&""!==o||(o=P.selectSearchNotFound),void 0===a&&(a=P.selectSearchPlaceholder),void 0!==r&&""!==r||(r=P.selectSmartPositioning);var c=D('<div class="jq-selectbox jqselect"><div class="jq-selectbox__select"><div class="jq-selectbox__select-text"></div><div class="jq-selectbox__trigger"><div class="jq-selectbox__trigger-arrow"></div></div></div></div>').attr({id:e.id,title:e.title}).addClass(e.classes).data(e.data);N.after(c).prependTo(c);var n=0<(n=c.css("z-index"))?n:1,d=D("div.jq-selectbox__select",c),h=D("div.jq-selectbox__select-text",c),e=k.filter(":selected");T(),i&&(t='<div class="jq-selectbox__search"><input type="search" autocomplete="off" placeholder="'+a+'"></div><div class="jq-selectbox__not-found">'+o+"</div>");var f=D('<div class="jq-selectbox__dropdown">'+t+"<ul>"+S+"</ul></div>");c.append(f);var u=D("ul",f),p=D("li",f),v=D("input",f),m=D("div.jq-selectbox__not-found",f).hide();p.length<l&&v.parent().hide(),""===k.first().text()&&k.first().is(":selected")&&!1!==s?h.text(s).addClass("placeholder"):h.text(e.text());var g=0,b=0;p.css({display:"inline-block"}),p.each(function(){var e=D(this);e.innerWidth()>g&&(g=e.innerWidth(),b=e.width())}),p.css({display:""}),h.is(".placeholder")&&h.width()>g?h.width(h.width()):(C=(q=c.clone().appendTo("body").width("auto")).outerWidth(),q.remove(),C==c.outerWidth()&&h.width(b)),g>c.width()&&f.width(g),""===k.first().text()&&""!==N.data("placeholder")&&p.first().hide();var C,x=c.outerHeight(!0),y=v.parent().outerHeight(!0)||0,w=u.css("max-height"),q=p.filter(".selected");q.length<1&&p.first().addClass("selected sel"),void 0===p.data("li-height")&&(C=p.outerHeight(),!1!==s&&(C=p.eq(1).outerHeight()),p.data("li-height",C));var _=f.css("top");"auto"==f.css("left")&&f.css({left:0}),"auto"==f.css("top")&&(f.css({top:x}),_=x),f.hide(),q.length&&(k.first().text()!=e.text()&&c.addClass("changed"),c.data("jqfs-class",q.data("jqfs-class")),c.addClass(q.data("jqfs-class"))),N.is(":disabled")?c.addClass("disabled"):(d.click(function(){var e,t,s,i,l,o,a;function d(){u.css("max-height",Math.floor((s-e.scrollTop()-20-y)/t)*t)}D("div.jq-selectbox").filter(".opened").length&&P.onSelectClosed.call(D("div.jq-selectbox").filter(".opened")),N.focus(),H||(e=D(window),t=p.data("li-height"),s=c.offset().top,i=e.height()-x-(s-e.scrollTop()),void 0!==(a=N.data("visible-options"))&&""!==a||(a=P.selectVisibleOptions),l=5*t,o=t*a,0<a&&a<6&&(l=o),0===a&&(o="auto"),a=function(){f.height("auto").css({bottom:"auto",top:_});function e(){u.css("max-height",Math.floor((i-20-y)/t)*t)}e(),u.css("max-height",o),"none"!=w&&u.css("max-height",w),i<f.outerHeight()+20&&e()},!0===r||1===r?l+y+20<i?(a(),c.removeClass("dropup").addClass("dropdown")):(f.height("auto").css({top:"auto",bottom:_}),d(),u.css("max-height",o),"none"!=w&&u.css("max-height",w),s-e.scrollTop()-20<f.outerHeight()+20&&d(),c.removeClass("dropdown").addClass("dropup")):!1===r||0===r?l+y+20<i&&(a(),c.removeClass("dropup").addClass("dropdown")):(f.height("auto").css({bottom:"auto",top:_}),u.css("max-height",o),"none"!=w&&u.css("max-height",w)),c.offset().left+f.outerWidth()>e.width()&&f.css({left:"auto",right:0}),D("div.jqselect").css({zIndex:n-1}).removeClass("opened"),c.css({zIndex:n}),f.is(":hidden")?(D("div.jq-selectbox__dropdown:visible").hide(),f.show(),c.addClass("opened focused"),P.onSelectOpened.call(c)):(f.hide(),c.removeClass("opened dropup dropdown"),D("div.jq-selectbox").filter(".opened").length&&P.onSelectClosed.call(c)),v.length&&(v.val("").keyup(),m.hide(),v.keyup(function(){var e=D(this).val();p.each(function(){D(this).html().match(new RegExp(".*?"+e+".*?","i"))?D(this).show():D(this).hide()}),""===k.first().text()&&""!==N.data("placeholder")&&p.first().hide(),p.filter(":visible").length<1?m.show():m.hide()})),p.filter(".selected").length&&(""===N.val()?u.scrollTop(0):(u.innerHeight()/t%2!=0&&(t/=2),u.scrollTop(u.scrollTop()+p.filter(".selected").position().top-u.innerHeight()/2+t))),j(u))}),p.hover(function(){D(this).siblings().removeClass("selected")}),p.filter(".selected").text(),p.filter(":not(.disabled):not(.optgroup)").click(function(){N.focus();var e,t=D(this),s=t.text();t.is(".selected")||(e=t.index(),e-=t.prevAll(".optgroup").length,t.addClass("selected sel").siblings().removeClass("selected sel"),k.prop("selected",!1).eq(e).prop("selected",!0),h.text(s),c.data("jqfs-class")&&c.removeClass(c.data("jqfs-class")),c.data("jqfs-class",t.data("jqfs-class")),c.addClass(t.data("jqfs-class")),N.change()),f.hide(),c.removeClass("opened dropup dropdown"),P.onSelectClosed.call(c)}),f.mouseout(function(){D("li.sel",f).addClass("selected")}),N.on("change.styler",function(){h.text(k.filter(":selected").text()).removeClass("placeholder"),p.removeClass("selected sel").not(".optgroup").eq(N[0].selectedIndex).addClass("selected sel"),k.first().text()!=p.filter(".selected").text()?c.addClass("changed"):c.removeClass("changed")}).on("focus.styler",function(){c.addClass("focused"),D("div.jqselect").not(".focused").removeClass("opened dropup dropdown").find("div.jq-selectbox__dropdown").hide()}).on("blur.styler",function(){c.removeClass("focused")}).on("keydown.styler keyup.styler",function(e){var t=p.data("li-height");""===N.val()?h.text(s).addClass("placeholder"):h.text(k.filter(":selected").text()),p.removeClass("selected sel").not(".optgroup").eq(N[0].selectedIndex).addClass("selected sel"),38!=e.which&&37!=e.which&&33!=e.which&&36!=e.which||(""===N.val()?u.scrollTop(0):u.scrollTop(u.scrollTop()+p.filter(".selected").position().top)),40!=e.which&&39!=e.which&&34!=e.which&&35!=e.which||u.scrollTop(u.scrollTop()+p.filter(".selected").position().top-u.innerHeight()+t),13==e.which&&(e.preventDefault(),f.hide(),c.removeClass("opened dropup dropdown"),P.onSelectClosed.call(c))}).on("keydown.styler",function(e){32==e.which&&(e.preventDefault(),d.click())}),I.registered||(D(document).on("click",I),I.registered=!0))}()})(),N.on("refresh",function(){N.off(".styler").parent().before(N).remove(),l()})):N.is(":reset")&&N.on("click",function(){setTimeout(function(){N.closest("form").find("input, select").trigger("refresh")},1)})},destroy:function(){var e=D(this.element);e.is(":checkbox")||e.is(":radio")?(e.removeData("_"+l).off(".styler refresh").removeAttr("style").parent().before(e).remove(),e.closest("label").add('label[for="'+e.attr("id")+'"]').off(".styler")):e.is('input[type="number"]')?e.removeData("_"+l).off(".styler refresh").closest(".jq-number").before(e).remove():(e.is(":file")||e.is("select"))&&e.removeData("_"+l).off(".styler refresh").removeAttr("style").parent().before(e).remove()}},D.fn[l]=function(t){var s,i=arguments;return void 0===t||"object"==typeof t?(this.each(function(){D.data(this,"_"+l)||D.data(this,"_"+l,new o(this,t))}).promise().done(function(){var e=D(this[0]).data("_"+l);e&&e.options.onFormStyled.call()}),this):"string"==typeof t&&"_"!==t[0]&&"init"!==t?(this.each(function(){var e=D.data(this,"_"+l);e instanceof o&&"function"==typeof e[t]&&(s=e[t].apply(e,Array.prototype.slice.call(i,1)))}),void 0!==s?s:this):void 0},I.registered=!1});