!function(a,b,c,d){a.fn.doubleTapToGo=function(d){return"ontouchstart"in b||navigator.msMaxTouchPoints||navigator.userAgent.toLowerCase().match(/windows phone os 7/i)?("unbind"===d?this.each(function(){a(this).off(),a(c).off("click touchstart MSPointerDown",handleTouch)}):this.each(function(){function b(b){for(var c=!0,e=a(b.target).parents(),f=0;f<e.length;f++)e[f]==d[0]&&(c=!1);c&&(d=!1)}var d=!1;a(this).on("click",function(b){var c=a(this);c[0]!=d[0]&&(b.preventDefault(),d=c)}),a(c).on("click touchstart MSPointerDown",b)}),this):!1}}(jQuery,window,document);

!function(a,b,c){"use strict";Drupal.behaviors.atrM={attach:function(d,e){function f(c){c.preventDefault(),c.stopPropagation(),a(b.body).toggleClass("rm-is-open"),"true"==a(this).attr("aria-expanded")?a(this).attr("aria-expanded","false"):"false"==a(this).attr("aria-expanded")&&a(this).attr("aria-expanded","true"),a(b).one("click",function(c){0===a(".rm-block").has(c.target).length&&(a(b.body).removeClass("rm-is-open"),a(m).attr("aria-expanded","false"))})}function g(b){b.preventDefault(),b.stopPropagation(),a(this).toggleClass("is-open--parent"),"true"==a(this).attr("aria-expanded")?a(this).attr("aria-expanded","false"):"false"==a(this).attr("aria-expanded")&&a(this).attr("aria-expanded","true"),a(this).parent().next(".is-child").toggleClass("is-open--child")}function h(b){var c=a("#rm-accordion-trigger").html();a(b).each(function(){0==a(this).next(".rm-accordion-trigger").length&&a(this).after(c);var b=a(this).parent().parent().attr("id");a(this).next().attr("aria-controls",b+"__child-menu"),a(this).parent().next(".is-child").attr("id",b+"__child-menu")})}if(a(".rm-block").removeClass("js-hide"),c.matchMedia("only screen").matches){var i=e.ajaxPageState.theme,j=e[i].at_responsivemenus,k=j["default"],l=j.responsive,m=".rm-block .rm-toggle__link",n=j.vertical_position||null,o=j.horizontal_position||null,p=j.acd.acd_default,q=j.acd.acd_responsive,r=j.acd.acd_both,s=j.acd.acd_load;a(m,d).on("click",f),enquire.register(j.bp,{setup:function(){a(b.body).addClass(k),a(".rm-block").parent(".l-r").addClass("rm-region").parent(".l-rw").addClass("rm-row"),"ms-dropmenu"==k&&a(".rm-block__content li:has(ul)").doubleTapToGo(),1==p&&1==s&&(a(".rm-block .menu-level-1").addClass("ms-accordion"),a.ready(h(".ms-accordion .is-parent__wrapper a")),a(".ms-accordion .rm-accordion-trigger",d).on("click",g))},match:function(){"ms-none"!==l&&l!==k&&(a(b.body).removeClass(k).addClass(l),1==s&&(1==q?0==r&&(a(".rm-block .menu-level-1").addClass("ms-accordion"),a.ready(h(".ms-accordion .is-parent__wrapper a")),a(".ms-accordion .rm-accordion-trigger",d).on("click",g)):(a(".ms-accordion .rm-accordion-trigger").remove(),a(".rm-block .menu-level-1").removeClass("ms-accordion"),a(".rm-block .menu").removeClass("is-open--child"))),"ms-dropmenu"==l?a(".rm-block__content li:has(ul)").doubleTapToGo():a(".rm-block__content li:has(ul)").doubleTapToGo("unbind"),n?a("."+l+" .rm-block").atFlexCenter({verticalPosition:n,horizontalPosition:o,parentSelector:".rm-row"}):o&&a("."+l+" .rm-block").atFlexCenter({horizontalPosition:o,parentSelector:".rm-row"}))},unmatch:function(){a(b.body).addClass(k),1==s&&(1==p?0==r&&(a(".rm-block .menu-level-1").addClass("ms-accordion"),a.ready(h(".ms-accordion .is-parent__wrapper a")),a(".ms-accordion .rm-accordion-trigger",d).on("click",g)):(a(".ms-accordion .rm-accordion-trigger").remove(),a(".rm-block .menu-level-1").removeClass("ms-accordion"),a(".rm-block .menu").removeClass("is-open--child"))),"ms-dropmenu"==k?a(".rm-block__content li:has(ul)").doubleTapToGo():a(".rm-block__content li:has(ul)").doubleTapToGo("unbind"),n?(a(".rm-region").removeAttr("style"),a("."+l+" .rm-block").removeClass("is-vertical-"+n).removeClass("is-horizontal-"+o).removeAttr("style")):o&&a("."+l+" .rm-block").removeClass("is-horizontal-"+o).removeAttr("style"),l!==k&&a(b.body).removeClass(l)}})}}}}(jQuery,document,window);

!function(){Drupal.behaviors.atOrientationChangeReload={attach:function(){window.addEventListener("orientationchange",function(){document.body.style.display="none",window.location.reload()})}}}();

/**
 * @file
 * Adapted from underscore.js with the addition Drupal namespace.
 */

/**
 * Limits the invocations of a function in a given time frame.
 *
 * The debounce function wrapper should be used sparingly. One clear use case
 * is limiting the invocation of a callback attached to the window resize event.
 *
 * Before using the debounce function wrapper, consider first whether the
 * callback could be attached to an event that fires less frequently or if the
 * function can be written in such a way that it is only invoked under specific
 * conditions.
 *
 * @param {function} func
 *   The function to be invoked.
 * @param {number} wait
 *   The time period within which the callback function should only be
 *   invoked once. For example if the wait period is 250ms, then the callback
 *   will only be called at most 4 times per second.
 * @param {bool} immediate
 *   Whether we wait at the beginning or end to execute the function.
 *
 * @return {function}
 *   The debounced function.
 */
Drupal.debounce = function (func, wait, immediate) {

  'use strict';

  var timeout;
  var result;
  return function () {
    var context = this;
    var args = arguments;
    var later = function () {
      timeout = null;
      if (!immediate) {
        result = func.apply(context, args);
      }
    };
    var callNow = immediate && !timeout;
    clearTimeout(timeout);
    timeout = setTimeout(later, wait);
    if (callNow) {
      result = func.apply(context, args);
    }
    return result;
  };
};
;
!function(a,b,c){"use strict";function d(d,e){function f(b){e.addClass("is-horizontal");var c=e.find(".is-responsive__list"),d=0;c.find(".is-responsive__item").each(function(){d+=a(this).outerWidth(!0)});var f=c.outerWidth(!0)<=d;1==f?e.removeClass("is-horizontal").addClass("is-vertical"):e.removeClass("is-vertical").addClass("is-horizontal")}var e=a(e);a(c).on("resize.lists",b.debounce(f,150)).trigger("resize.lists")}b.behaviors.atRL={attach:function(b){var c=a(b).find("[data-at-responsive-list]");c.length&&c.once().each(d)}}}(jQuery,Drupal,window);
