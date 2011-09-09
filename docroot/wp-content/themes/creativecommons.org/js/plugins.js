
// usage: log('inside coolFunc', this, arguments);
// paulirish.com/2009/log-a-lightweight-wrapper-for-consolelog/
window.log = function(){
  log.history = log.history || [];   // store logs to an array for reference
  log.history.push(arguments);
  if(this.console) {
    arguments.callee = arguments.callee.caller;
    var newarr = [].slice.call(arguments);
    (typeof console.log === 'object' ? log.apply.call(console.log, console, newarr) : console.log.apply(console, newarr));
  }
};

// make it safe to use console.log always
(function(b){function c(){}for(var d="assert,count,debug,dir,dirxml,error,exception,group,groupCollapsed,groupEnd,info,log,timeStamp,profile,profileEnd,time,timeEnd,trace,warn".split(","),a;a=d.pop();){b[a]=b[a]||c}})((function(){try
{console.log();return window.console;}catch(err){return window.console={};}})());


// place any jQuery/helper plugins in here, instead of separate, slower script files.

/*
 * jQuery Easing v1.3 - http://gsgd.co.uk/sandbox/jquery/easing/
 *
 * Uses the built in easing capabilities added In jQuery 1.1
 * to offer multiple easing options
 *
 * TERMS OF USE - jQuery Easing
 * 
 * Open source under the BSD License. 
 * 
 * Copyright © 2008 George McGinley Smith
 * All rights reserved.
 * 
 * Redistribution and use in source and binary forms, with or without modification, 
 * are permitted provided that the following conditions are met:
 * 
 * Redistributions of source code must retain the above copyright notice, this list of 
 * conditions and the following disclaimer.
 * Redistributions in binary form must reproduce the above copyright notice, this list 
 * of conditions and the following disclaimer in the documentation and/or other materials 
 * provided with the distribution.
 * 
 * Neither the name of the author nor the names of contributors may be used to endorse 
 * or promote products derived from this software without specific prior written permission.
 * 
 * THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS "AS IS" AND ANY 
 * EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT LIMITED TO, THE IMPLIED WARRANTIES OF
 * MERCHANTABILITY AND FITNESS FOR A PARTICULAR PURPOSE ARE DISCLAIMED. IN NO EVENT SHALL THE
 *  COPYRIGHT OWNER OR CONTRIBUTORS BE LIABLE FOR ANY DIRECT, INDIRECT, INCIDENTAL, SPECIAL,
 *  EXEMPLARY, OR CONSEQUENTIAL DAMAGES (INCLUDING, BUT NOT LIMITED TO, PROCUREMENT OF SUBSTITUTE
 *  GOODS OR SERVICES; LOSS OF USE, DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER CAUSED 
 * AND ON ANY THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT LIABILITY, OR TORT (INCLUDING
 *  NEGLIGENCE OR OTHERWISE) ARISING IN ANY WAY OUT OF THE USE OF THIS SOFTWARE, EVEN IF ADVISED 
 * OF THE POSSIBILITY OF SUCH DAMAGE. 
 *
*/

jQuery.easing["jswing"]=jQuery.easing["swing"];jQuery.extend(jQuery.easing,{def:"easeOutQuad",swing:function(a,b,c,d,e){return jQuery.easing[jQuery.easing.def](a,b,c,d,e)},easeInQuad:function(a,b,c,d,e){return d*(b/=e)*b+c},easeOutQuad:function(a,b,c,d,e){return-d*(b/=e)*(b-2)+c},easeInOutQuad:function(a,b,c,d,e){if((b/=e/2)<1)return d/2*b*b+c;return-d/2*(--b*(b-2)-1)+c},easeInCubic:function(a,b,c,d,e){return d*(b/=e)*b*b+c},easeOutCubic:function(a,b,c,d,e){return d*((b=b/e-1)*b*b+1)+c},easeInOutCubic:function(a,b,c,d,e){if((b/=e/2)<1)return d/2*b*b*b+c;return d/2*((b-=2)*b*b+2)+c},easeInQuart:function(a,b,c,d,e){return d*(b/=e)*b*b*b+c},easeOutQuart:function(a,b,c,d,e){return-d*((b=b/e-1)*b*b*b-1)+c},easeInOutQuart:function(a,b,c,d,e){if((b/=e/2)<1)return d/2*b*b*b*b+c;return-d/2*((b-=2)*b*b*b-2)+c},easeInQuint:function(a,b,c,d,e){return d*(b/=e)*b*b*b*b+c},easeOutQuint:function(a,b,c,d,e){return d*((b=b/e-1)*b*b*b*b+1)+c},easeInOutQuint:function(a,b,c,d,e){if((b/=e/2)<1)return d/2*b*b*b*b*b+c;return d/2*((b-=2)*b*b*b*b+2)+c},easeInSine:function(a,b,c,d,e){return-d*Math.cos(b/e*(Math.PI/2))+d+c},easeOutSine:function(a,b,c,d,e){return d*Math.sin(b/e*(Math.PI/2))+c},easeInOutSine:function(a,b,c,d,e){return-d/2*(Math.cos(Math.PI*b/e)-1)+c},easeInExpo:function(a,b,c,d,e){return b==0?c:d*Math.pow(2,10*(b/e-1))+c},easeOutExpo:function(a,b,c,d,e){return b==e?c+d:d*(-Math.pow(2,-10*b/e)+1)+c},easeInOutExpo:function(a,b,c,d,e){if(b==0)return c;if(b==e)return c+d;if((b/=e/2)<1)return d/2*Math.pow(2,10*(b-1))+c;return d/2*(-Math.pow(2,-10*--b)+2)+c},easeInCirc:function(a,b,c,d,e){return-d*(Math.sqrt(1-(b/=e)*b)-1)+c},easeOutCirc:function(a,b,c,d,e){return d*Math.sqrt(1-(b=b/e-1)*b)+c},easeInOutCirc:function(a,b,c,d,e){if((b/=e/2)<1)return-d/2*(Math.sqrt(1-b*b)-1)+c;return d/2*(Math.sqrt(1-(b-=2)*b)+1)+c},easeInElastic:function(a,b,c,d,e){var f=1.70158;var g=0;var h=d;if(b==0)return c;if((b/=e)==1)return c+d;if(!g)g=e*.3;if(h<Math.abs(d)){h=d;var f=g/4}else var f=g/(2*Math.PI)*Math.asin(d/h);return-(h*Math.pow(2,10*(b-=1))*Math.sin((b*e-f)*2*Math.PI/g))+c},easeOutElastic:function(a,b,c,d,e){var f=1.70158;var g=0;var h=d;if(b==0)return c;if((b/=e)==1)return c+d;if(!g)g=e*.3;if(h<Math.abs(d)){h=d;var f=g/4}else var f=g/(2*Math.PI)*Math.asin(d/h);return h*Math.pow(2,-10*b)*Math.sin((b*e-f)*2*Math.PI/g)+d+c},easeInOutElastic:function(a,b,c,d,e){var f=1.70158;var g=0;var h=d;if(b==0)return c;if((b/=e/2)==2)return c+d;if(!g)g=e*.3*1.5;if(h<Math.abs(d)){h=d;var f=g/4}else var f=g/(2*Math.PI)*Math.asin(d/h);if(b<1)return-.5*h*Math.pow(2,10*(b-=1))*Math.sin((b*e-f)*2*Math.PI/g)+c;return h*Math.pow(2,-10*(b-=1))*Math.sin((b*e-f)*2*Math.PI/g)*.5+d+c},easeInBack:function(a,b,c,d,e,f){if(f==undefined)f=1.70158;return d*(b/=e)*b*((f+1)*b-f)+c},easeOutBack:function(a,b,c,d,e,f){if(f==undefined)f=1.70158;return d*((b=b/e-1)*b*((f+1)*b+f)+1)+c},easeInOutBack:function(a,b,c,d,e,f){if(f==undefined)f=1.70158;if((b/=e/2)<1)return d/2*b*b*(((f*=1.525)+1)*b-f)+c;return d/2*((b-=2)*b*(((f*=1.525)+1)*b+f)+2)+c},easeInBounce:function(a,b,c,d,e){return d-jQuery.easing.easeOutBounce(a,e-b,0,d,e)+c},easeOutBounce:function(a,b,c,d,e){if((b/=e)<1/2.75){return d*7.5625*b*b+c}else if(b<2/2.75){return d*(7.5625*(b-=1.5/2.75)*b+.75)+c}else if(b<2.5/2.75){return d*(7.5625*(b-=2.25/2.75)*b+.9375)+c}else{return d*(7.5625*(b-=2.625/2.75)*b+.984375)+c}},easeInOutBounce:function(a,b,c,d,e){if(b<e/2)return jQuery.easing.easeInBounce(a,b*2,0,d,e)*.5+c;return jQuery.easing.easeOutBounce(a,b*2-e,0,d,e)*.5+d*.5+c}});

/*
* Slides, A Slideshow Plugin for jQuery
* Intructions: http://slidesjs.com
* By: Nathan Searles, http://nathansearles.com
* Version: 1.1.8
* Updated: June 1st, 2011
*
* Licensed under the Apache License, Version 2.0 (the "License");
* you may not use this file except in compliance with the License.
* You may obtain a copy of the License at
*
* http://www.apache.org/licenses/LICENSE-2.0
*
* Unless required by applicable law or agreed to in writing, software
* distributed under the License is distributed on an "AS IS" BASIS,
* WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
* See the License for the specific language governing permissions and
* limitations under the License.
*/
(function(A){A.fn.slides=function(B){B=A.extend({},A.fn.slides.option,B);return this.each(function(){A("."+B.container,A(this)).children().wrapAll('<div class="slides_control"/>');var V=A(this),J=A(".slides_control",V),Z=J.children().size(),Q=J.children().outerWidth(),M=J.children().outerHeight(),D=B.start-1,L=B.effect.indexOf(",")<0?B.effect:B.effect.replace(" ","").split(",")[0],S=B.effect.indexOf(",")<0?L:B.effect.replace(" ","").split(",")[1],O=0,N=0,C=0,P=0,U,H,I,X,W,T,K,F;function E(c,b,a){if(!H&&U){H=true;B.animationStart(P+1);switch(c){case"next":N=P;O=P+1;O=Z===O?0:O;X=Q*2;c=-Q*2;P=O;break;case"prev":N=P;O=P-1;O=O===-1?Z-1:O;X=0;c=0;P=O;break;case"pagination":O=parseInt(a,10);N=A("."+B.paginationClass+" li."+B.currentClass+" a",V).attr("href").match("[^#/]+$");if(O>N){X=Q*2;c=-Q*2;}else{X=0;c=0;}P=O;break;}if(b==="fade"){if(B.crossfade){J.children(":eq("+O+")",V).css({zIndex:10}).fadeIn(B.fadeSpeed,B.fadeEasing,function(){if(B.autoHeight){J.animate({height:J.children(":eq("+O+")",V).outerHeight()},B.autoHeightSpeed,function(){J.children(":eq("+N+")",V).css({display:"none",zIndex:0});J.children(":eq("+O+")",V).css({zIndex:0});B.animationComplete(O+1);H=false;});}else{J.children(":eq("+N+")",V).css({display:"none",zIndex:0});J.children(":eq("+O+")",V).css({zIndex:0});B.animationComplete(O+1);H=false;}});}else{J.children(":eq("+N+")",V).fadeOut(B.fadeSpeed,B.fadeEasing,function(){if(B.autoHeight){J.animate({height:J.children(":eq("+O+")",V).outerHeight()},B.autoHeightSpeed,function(){J.children(":eq("+O+")",V).fadeIn(B.fadeSpeed,B.fadeEasing);});}else{J.children(":eq("+O+")",V).fadeIn(B.fadeSpeed,B.fadeEasing,function(){if(A.browser.msie){A(this).get(0).style.removeAttribute("filter");}});}B.animationComplete(O+1);H=false;});}}else{J.children(":eq("+O+")").css({left:X,display:"block"});if(B.autoHeight){J.animate({left:c,height:J.children(":eq("+O+")").outerHeight()},B.slideSpeed,B.slideEasing,function(){J.css({left:-Q});J.children(":eq("+O+")").css({left:Q,zIndex:5});J.children(":eq("+N+")").css({left:Q,display:"none",zIndex:0});B.animationComplete(O+1);H=false;});}else{J.animate({left:c},B.slideSpeed,B.slideEasing,function(){J.css({left:-Q});J.children(":eq("+O+")").css({left:Q,zIndex:5});J.children(":eq("+N+")").css({left:Q,display:"none",zIndex:0});B.animationComplete(O+1);H=false;});}}if(B.pagination){A("."+B.paginationClass+" li."+B.currentClass,V).removeClass(B.currentClass);A("."+B.paginationClass+" li:eq("+O+")",V).addClass(B.currentClass);}}}function R(){clearInterval(V.data("interval"));}function G(){if(B.pause){clearTimeout(V.data("pause"));clearInterval(V.data("interval"));K=setTimeout(function(){clearTimeout(V.data("pause"));F=setInterval(function(){E("next",L);},B.play);V.data("interval",F);},B.pause);V.data("pause",K);}else{R();}}if(Z<2){return ;}if(D<0){D=0;}if(D>Z){D=Z-1;}if(B.start){P=D;}if(B.randomize){J.randomize();}A("."+B.container,V).css({overflow:"hidden",position:"relative"});J.children().css({position:"absolute",top:0,left:J.children().outerWidth(),zIndex:0,display:"none"});J.css({position:"relative",width:(Q*3),height:M,left:-Q});A("."+B.container,V).css({display:"block"});if(B.autoHeight){J.children().css({height:"auto"});J.animate({height:J.children(":eq("+D+")").outerHeight()},B.autoHeightSpeed);}if(B.preload&&J.find("img:eq("+D+")").length){A("."+B.container,V).css({background:"url("+B.preloadImage+") no-repeat 50% 50%"});var Y=J.find("img:eq("+D+")").attr("src")+"?"+(new Date()).getTime();if(A("img",V).parent().attr("class")!="slides_control"){T=J.children(":eq(0)")[0].tagName.toLowerCase();}else{T=J.find("img:eq("+D+")");}J.find("img:eq("+D+")").attr("src",Y).load(function(){J.find(T+":eq("+D+")").fadeIn(B.fadeSpeed,B.fadeEasing,function(){A(this).css({zIndex:5});A("."+B.container,V).css({background:""});U=true;B.slidesLoaded();});});}else{J.children(":eq("+D+")").fadeIn(B.fadeSpeed,B.fadeEasing,function(){U=true;B.slidesLoaded();});}if(B.bigTarget){J.children().css({cursor:"pointer"});J.children().click(function(){E("next",L);return false;});}if(B.hoverPause&&B.play){J.bind("mouseover",function(){R();});J.bind("mouseleave",function(){G();});}if(B.generateNextPrev){A("."+B.container,V).after('<a href="#" class="'+B.prev+'">Prev</a>');A("."+B.prev,V).after('<a href="#" class="'+B.next+'">Next</a>');}A("."+B.next,V).click(function(a){a.preventDefault();if(B.play){G();}E("next",L);});A("."+B.prev,V).click(function(a){a.preventDefault();if(B.play){G();}E("prev",L);});if(B.generatePagination){if(B.prependPagination){V.prepend("<ul class="+B.paginationClass+"></ul>");}else{V.append("<ul class="+B.paginationClass+"></ul>");}J.children().each(function(){A("."+B.paginationClass,V).append('<li><a href="#'+C+'">'+(C+1)+"</a></li>");C++;});}else{A("."+B.paginationClass+" li a",V).each(function(){A(this).attr("href","#"+C);C++;});}A("."+B.paginationClass+" li:eq("+D+")",V).addClass(B.currentClass);A("."+B.paginationClass+" li a",V).click(function(){if(B.play){G();}I=A(this).attr("href").match("[^#/]+$");if(P!=I){E("pagination",S,I);}return false;});A("a.link",V).click(function(){if(B.play){G();}I=A(this).attr("href").match("[^#/]+$")-1;if(P!=I){E("pagination",S,I);}return false;});if(B.play){F=setInterval(function(){E("next",L);},B.play);V.data("interval",F);}});};A.fn.slides.option={preload:false,preloadImage:"/img/loading.gif",container:"slides_container",generateNextPrev:false,next:"next",prev:"prev",pagination:true,generatePagination:true,prependPagination:false,paginationClass:"pagination",currentClass:"current",fadeSpeed:350,fadeEasing:"",slideSpeed:350,slideEasing:"",start:1,effect:"slide",crossfade:false,randomize:false,play:0,pause:0,hoverPause:false,autoHeight:false,autoHeightSpeed:350,bigTarget:false,animationStart:function(){},animationComplete:function(){},slidesLoaded:function(){}};A.fn.randomize=function(C){function B(){return(Math.round(Math.random())-0.5);}return(A(this).each(function(){var F=A(this);var E=F.children();var D=E.length;if(D>1){E.hide();var G=[];for(i=0;i<D;i++){G[G.length]=i;}G=G.sort(B);A.each(G,function(I,H){var K=E.eq(H);var J=K.clone(true);J.show().appendTo(F);if(C!==undefined){C(K,J);}K.remove();});}}));};})(jQuery);
