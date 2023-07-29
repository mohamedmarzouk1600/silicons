/******/ (() => { // webpackBootstrap
var __webpack_exports__ = {};
// This entry need to be wrapped in an IIFE because it need to be isolated against other entry modules.
(() => {
/*!****************************************************!*\
  !*** ./resources/admin-assets/js/core/app-menu.js ***!
  \****************************************************/
/*=========================================================================================
  File Name: app-menu.js
  Description: Menu navigation, custom scrollbar, hover scroll bar, multilevel menu
  initialization and manipulations
  ----------------------------------------------------------------------------------------
  Item Name: Stack - Responsive Admin Theme
  Version: 1.0
  Author: GeeksLabs
  Author URL: http://www.themeforest.net/user/geekslabs
==========================================================================================*/
(function (window, document, $) {
  'use strict';

  $.app = $.app || {};
  var $body = $('body');
  var $window = $(window);
  var menuWrapper_el = $('div[data-menu="menu-wrapper"]').html();
  var menuWrapperClasses = $('div[data-menu="menu-wrapper"]').attr('class'); // Main menu

  $.app.menu = {
    expanded: null,
    collapsed: null,
    hidden: null,
    container: null,
    horizontalMenu: false,
    manualScroller: {
      obj: null,
      init: function init() {
        var scroll_theme = $('.main-menu').hasClass('menu-dark') ? 'light' : 'dark';
        this.obj = $(".main-menu-content").perfectScrollbar({
          suppressScrollX: true,
          theme: scroll_theme
        });
      },
      update: function update() {
        if (this.obj) {
          // Scroll to currently active menu on page load if data-scroll-to-active is true
          if ($('.main-menu').data('scroll-to-active') === true) {
            var position;

            if ($(".main-menu-content").find('li.active').parents('li').length > 0) {
              position = $(".main-menu-content").find('li.active').parents('li').last().position();
            } else {
              position = $(".main-menu-content").find('li.active').position();
            }

            setTimeout(function () {
              // $.app.menu.container.scrollTop(position.top);
              if (position) $.app.menu.container.stop().animate({
                scrollTop: position.top
              }, 300);
              $('.main-menu').data('scroll-to-active', 'false');
            }, 300);
          }

          $(".main-menu-content").perfectScrollbar('update');
        }
      },
      enable: function enable() {
        this.init();
      },
      disable: function disable() {
        if (this.obj) {
          $('.main-menu-content').perfectScrollbar('destroy');
        }
      },
      updateHeight: function updateHeight() {
        if (($body.data('menu') == 'vertical-menu' || $body.data('menu') == 'vertical-overlay-menu') && $('.main-menu').hasClass('menu-fixed')) {
          $('.main-menu-content').css('height', $(window).height() - $('.header-navbar').height() - $('.main-menu-header').outerHeight() - $('.main-menu-footer').outerHeight());
          this.update();
        }
      }
    },
    init: function init() {
      if ($('.main-menu-content').length > 0) {
        this.container = $('.main-menu-content');
        var menuObj = this;
        this.change();
      } else {
        // For 1 column layout menu won't be initialized so initiate drill down for mega menu
        // Drill down menu
        // ------------------------------
        this.drillDownMenu();
      }
    },
    drillDownMenu: function drillDownMenu(screenSize) {
      if ($('.drilldown-menu').length) {
        if (screenSize == 'sm' || screenSize == 'xs') {
          if ($('#navbar-mobile').attr('aria-expanded') == 'true') {
            $('.drilldown-menu').slidingMenu({
              backLabel: true
            });
          }
        } else {
          $('.drilldown-menu').slidingMenu({
            backLabel: true
          });
        }
      }
    },
    change: function change() {
      var currentBreakpoint = Unison.fetch.now(); // Current Breakpoint

      this.reset();
      var menuType = $body.data('menu');

      if (currentBreakpoint) {
        switch (currentBreakpoint.name) {
          case 'xl':
          case 'lg':
            if (menuType === 'vertical-overlay-menu') {
              this.hide();
            } else {
              this.expand();
            }

            break;

          case 'md':
            if (menuType === 'vertical-overlay-menu') {
              this.hide();
            } else {
              this.collapse();
            }

            break;

          case 'sm':
            this.hide();
            break;

          case 'xs':
            this.hide();
            break;
        }
      } // On the small and extra small screen make them overlay menu


      if (menuType === 'vertical-menu') {
        this.toOverlayMenu(currentBreakpoint.name);
      }

      if ($body.is('.horizontal-layout') && !$body.hasClass('.horizontal-menu-demo')) {
        this.changeMenu(currentBreakpoint.name);
        $('.menu-toggle').removeClass('is-active');
      } // Initialize drill down menu for vertical layouts, for horizontal layouts drilldown menu is intitialized in changemenu function


      if (menuType != 'horizontal-menu') {
        // Drill down menu
        // ------------------------------
        this.drillDownMenu(currentBreakpoint.name);
      } // Dropdown submenu on large screen on hover For Large screen only
      // ---------------------------------------------------------------


      if (currentBreakpoint.name == 'xl') {
        $('body[data-open="hover"] .dropdown').on('mouseenter', function () {
          if (!$(this).hasClass('open')) {
            $(this).addClass('open');
          } else {
            $(this).removeClass('open');
          }
        }).on('mouseleave', function (event) {
          $(this).removeClass('open');
        });
        $('body[data-open="hover"] .dropdown a').on('click', function (e) {
          if (menuType == 'horizontal-menu') {
            var $this = $(this);

            if ($this.hasClass('dropdown-toggle')) {
              return false;
            }
          }
        });
      } // Added data attribute brand-center for navbar-brand-center
      // TODO:AJ: Shift this feature in JADE.


      if ($('.header-navbar').hasClass('navbar-brand-center')) {
        $('.header-navbar').attr('data-nav', 'brand-center');
      }

      if (currentBreakpoint.name == 'sm' || currentBreakpoint.name == 'xs') {
        $('.header-navbar[data-nav=brand-center]').removeClass('navbar-brand-center');
      } else {
        $('.header-navbar[data-nav=brand-center]').addClass('navbar-brand-center');
      } // Dropdown submenu on small screen on click
      // --------------------------------------------------


      $('ul.dropdown-menu [data-toggle=dropdown]').on('click', function (event) {
        if ($(this).siblings('ul.dropdown-menu').length > 0) {
          event.preventDefault();
        }

        event.stopPropagation();
        $(this).parent().siblings().removeClass('open');
        $(this).parent().toggleClass('open');
      }); // Horizontal Fixed Nav Sticky hight issue on small screens

      if (menuType == 'horizontal-menu') {
        if (currentBreakpoint.name == 'sm' || currentBreakpoint.name == 'xs') {
          if ($(".menu-fixed").length) {
            $(".menu-fixed").unstick();
          }
        } else {
          if ($(".navbar-fixed").length) {
            $(".navbar-fixed").sticky();
          }
        }
      }
      /********************************************
      *             Searchable Menu               *
      ********************************************/


      function searchMenu(list) {
        var input = $(".menu-search");
        $(input).change(function () {
          var filter = $(this).val();

          if (filter) {
            // Hide Main Navigation Headers
            $('.navigation-header').hide(); // this finds all links in a list that contain the input,
            // and hide the ones not containing the input while showing the ones that do

            $(list).find("li a:not(:Contains(" + filter + "))").hide().parent().hide(); // $(list).find("li a:Contains(" + filter + ")").show().parents('li').show().addClass('open').closest('li').children('a').show();

            var searchFilter = $(list).find("li a:Contains(" + filter + ")");

            if (searchFilter.parent().hasClass('has-sub')) {
              searchFilter.show().parents('li').show().addClass('open').closest('li').children('a').show().children('li').show(); // searchFilter.parents('li').find('li').show().children('a').show();

              if (searchFilter.siblings('ul').length > 0) {
                searchFilter.siblings('ul').children('li').show().children('a').show();
              }
            } else {
              searchFilter.show().parents('li').show().addClass('open').closest('li').children('a').show();
            }
          } else {
            // return to default
            $('.navigation-header').show();
            $(list).find("li a").show().parent().show().removeClass('open');
          }

          $.app.menu.manualScroller.update();
          return false;
        }).keyup(function () {
          // fire the above change event after every letter
          $(this).change();
        });
      }

      if (menuType === 'vertical-menu' || menuType === 'vertical-overlay-menu') {
        // custom css expression for a case-insensitive contains()
        jQuery.expr[':'].Contains = function (a, i, m) {
          return (a.textContent || a.innerText || "").toUpperCase().indexOf(m[3].toUpperCase()) >= 0;
        };

        searchMenu($("#main-menu-navigation"));
      }
    },
    changeLogo: function changeLogo(menuType) {
      var logo = $('.brand-logo');
      var logoText = $('.brand-text');

      if (menuType == 'expand') {
        // logo.attr('src',logo.data('expand'));
        logoText.delay(100).fadeIn(200); // logoText.addClass('d-inline-block').removeClass('d-none');
      } else {
        // logo.attr('src',logo.data('collapse'));
        logoText.fadeOut(100); // logoText.addClass('d-none').removeClass('d-inline-block');
      }
    },
    transit: function transit(callback1, callback2) {
      var menuObj = this;
      $body.addClass('changing-menu');
      callback1.call(menuObj);

      if ($body.hasClass('vertical-layout')) {
        if ($body.hasClass('menu-open') || $body.hasClass('menu-expanded')) {
          $('.menu-toggle').addClass('is-active'); // Show menu header search when menu is normally visible

          if ($body.data('menu') === 'vertical-menu') {
            if ($('.main-menu-header')) {
              $('.main-menu-header').show();
            }
          }
        } else {
          $('.menu-toggle').removeClass('is-active'); // Hide menu header search when only menu icons are visible

          if ($body.data('menu') === 'vertical-menu') {
            if ($('.main-menu-header')) {
              $('.main-menu-header').hide();
            }
          }
        }
      }

      setTimeout(function () {
        callback2.call(menuObj);
        $body.removeClass('changing-menu');
        menuObj.update();
      }, 500);
    },
    open: function open() {
      this.transit(function () {
        $body.removeClass('menu-hide menu-collapsed').addClass('menu-open');
        this.hidden = false;
        this.expanded = true;
      }, function () {
        if (!$('.main-menu').hasClass('menu-native-scroll') && $('.main-menu').hasClass('menu-fixed')) {
          this.manualScroller.enable();
          $('.main-menu-content').css('height', $(window).height() - $('.header-navbar').height() - $('.main-menu-header').outerHeight() - $('.main-menu-footer').outerHeight()); // this.manualScroller.update();
        }
      });
    },
    hide: function hide() {
      this.transit(function () {
        $body.removeClass('menu-open menu-expanded').addClass('menu-hide');
        this.hidden = true;
        this.expanded = false;
      }, function () {
        if (!$('.main-menu').hasClass('menu-native-scroll') && $('.main-menu').hasClass('menu-fixed')) {
          this.manualScroller.enable();
        }
      });
    },
    expand: function expand() {
      if (this.expanded === false) {
        if ($body.data('menu') == 'vertical-menu') {
          this.changeLogo('expand');
        }

        this.transit(function () {
          $body.removeClass('menu-collapsed').addClass('menu-expanded');
          this.collapsed = false;
          this.expanded = true;
        }, function () {
          if ($('.main-menu').hasClass('menu-native-scroll') || $body.data('menu') == 'horizontal-menu') {
            this.manualScroller.disable();
          } else {
            if ($('.main-menu').hasClass('menu-fixed')) this.manualScroller.enable();
          }

          if ($body.data('menu') == 'vertical-menu' && $('.main-menu').hasClass('menu-fixed')) {
            $('.main-menu-content').css('height', $(window).height() - $('.header-navbar').height() - $('.main-menu-header').outerHeight() - $('.main-menu-footer').outerHeight()); // this.manualScroller.update();
          }
        });
      }
    },
    collapse: function collapse() {
      if (this.collapsed === false) {
        if ($body.data('menu') == 'vertical-menu') {
          this.changeLogo('collapse');
        }

        this.transit(function () {
          $body.removeClass('menu-expanded').addClass('menu-collapsed');
          this.collapsed = true;
          this.expanded = false;
        }, function () {
          if ($body.data('menu') == 'horizontal-menu' && $body.hasClass('vertical-overlay-menu')) {
            if ($('.main-menu').hasClass('menu-fixed')) this.manualScroller.enable();
          }

          if ($body.data('menu') == 'vertical-menu' && $('.main-menu').hasClass('menu-fixed')) {
            $('.main-menu-content').css('height', $(window).height() - $('.header-navbar').height()); // this.manualScroller.update();
          }
        });
      }
    },
    toOverlayMenu: function toOverlayMenu(screen) {
      var menu = $body.data('menu');

      if (screen == 'sm' || screen == 'xs') {
        if ($body.hasClass(menu)) {
          $body.removeClass(menu).addClass('vertical-overlay-menu');
        }
      } else {
        if ($body.hasClass('vertical-overlay-menu')) {
          $body.removeClass('vertical-overlay-menu').addClass(menu);
        }
      }
    },
    changeMenu: function changeMenu(screen) {
      // Replace menu html
      $('div[data-menu="menu-wrapper"]').html('');
      $('div[data-menu="menu-wrapper"]').html(menuWrapper_el);
      var menuWrapper = $('div[data-menu="menu-wrapper"]'),
          menuContainer = $('div[data-menu="menu-container"]'),
          menuNavigation = $('ul[data-menu="menu-navigation"]'),
          megaMenu = $('li[data-menu="megamenu"]'),
          megaMenuCol = $('li[data-mega-col]'),
          dropdownMenu = $('li[data-menu="dropdown"]'),
          dropdownSubMenu = $('li[data-menu="dropdown-submenu"]');

      if (screen == 'sm' || screen == 'xs') {
        // Change body classes
        $body.removeClass($body.data('menu')).addClass('vertical-layout vertical-overlay-menu fixed-navbar'); // Add navbar-fix-top class on small screens

        $('nav.header-navbar').addClass('navbar-fixed-top'); // Change menu wrapper, menu container, menu navigation classes

        menuWrapper.removeClass().addClass('main-menu menu-light menu-fixed menu-shadow'); // menuContainer.removeClass().addClass('main-menu-content');

        menuNavigation.removeClass().addClass('navigation navigation-main'); // If Mega Menu

        megaMenu.removeClass('dropdown mega-dropdown').addClass('has-sub');
        megaMenu.children('ul').removeClass();
        megaMenuCol.each(function (index, el) {
          // Remove drilldown-menu and menu list
          var megaMenuSub = $(el).find('.mega-menu-sub');
          megaMenuSub.find('li').has('ul').addClass('has-sub'); // if mega menu title is given, remove title and make it list item with mega menu title's text

          var first_child = $(el).children().first(),
              first_child_text = '';

          if (first_child.is('h6')) {
            first_child_text = first_child.html();
            first_child.remove();
            $(el).prepend('<a href="#">' + first_child_text + '</a>').addClass('has-sub mega-menu-title');
          }
        });
        megaMenu.find('a').removeClass('dropdown-toggle');
        megaMenu.find('a').removeClass('dropdown-item'); // If Dropdown Menu

        dropdownMenu.removeClass('dropdown').addClass('has-sub');
        dropdownMenu.find('a').removeClass('dropdown-toggle nav-link');
        dropdownMenu.children('ul').find('a').removeClass('dropdown-item');
        dropdownMenu.find('ul').removeClass('dropdown-menu');
        dropdownSubMenu.removeClass().addClass('has-sub');
        $.app.nav.init(); // Dropdown submenu on small screen on click
        // --------------------------------------------------

        $('ul.dropdown-menu [data-toggle=dropdown]').on('click', function (event) {
          event.preventDefault();
          event.stopPropagation();
          $(this).parent().siblings().removeClass('open');
          $(this).parent().toggleClass('open');
        });
      } else {
        // Change body classes
        $body.removeClass('vertical-layout vertical-overlay-menu fixed-navbar').addClass($body.data('menu')); // Remove navbar-fix-top class on large screens

        $('nav.header-navbar').removeClass('navbar-fixed-top'); // Change menu wrapper, menu container, menu navigation classes

        menuWrapper.removeClass().addClass(menuWrapperClasses); // Intitialize drill down menu for horizontal layouts
        // --------------------------------------------------

        this.drillDownMenu(screen);
        $('a.dropdown-item.nav-has-children').on('click', function () {
          event.preventDefault();
          event.stopPropagation();
        });
        $('a.dropdown-item.nav-has-parent').on('click', function () {
          event.preventDefault();
          event.stopPropagation();
        });
      }
    },
    toggle: function toggle() {
      var currentBreakpoint = Unison.fetch.now(); // Current Breakpoint

      var collapsed = this.collapsed;
      var expanded = this.expanded;
      var hidden = this.hidden;
      var menu = $body.data('menu');

      switch (currentBreakpoint.name) {
        case 'xl':
        case 'lg':
        case 'md':
          if (expanded === true) {
            if (menu == 'vertical-overlay-menu') {
              this.hide();
            } else {
              this.collapse();
            }
          } else {
            if (menu == 'vertical-overlay-menu') {
              this.open();
            } else {
              this.expand();
            }
          }

          break;

        case 'sm':
          if (hidden === true) {
            this.open();
          } else {
            this.hide();
          }

          break;

        case 'xs':
          if (hidden === true) {
            this.open();
          } else {
            this.hide();
          }

          break;
      } // Re-init sliding menu to update width


      this.drillDownMenu(currentBreakpoint.name);
    },
    update: function update() {
      this.manualScroller.update();
    },
    reset: function reset() {
      this.expanded = false;
      this.collapsed = false;
      this.hidden = false;
      $body.removeClass('menu-hide menu-open menu-collapsed menu-expanded');
    }
  }; // Navigation Menu

  $.app.nav = {
    container: $('.navigation-main'),
    initialized: false,
    navItem: $('.navigation-main').find('li').not('.navigation-category'),
    config: {
      speed: 300
    },
    init: function init(config) {
      this.initialized = true; // Set to true when initialized

      $.extend(this.config, config);
      this.bind_events();
    },
    bind_events: function bind_events() {
      var menuObj = this;
      $('.navigation-main').on('mouseenter.app.menu', 'li', function () {
        var $this = $(this);
        $('.hover', '.navigation-main').removeClass('hover');

        if ($body.hasClass('menu-collapsed')) {
          $('.main-menu-content').children('span.menu-title').remove();
          $('.main-menu-content').children('a.menu-title').remove();
          $('.main-menu-content').children('ul.menu-content').remove(); // Title

          var menuTitle = $this.find('span.menu-title').clone(),
              tempTitle,
              tempLink;

          if (!$this.hasClass('has-sub')) {
            tempTitle = $this.find('span.menu-title').text();
            tempLink = $this.children('a').attr('href');

            if (tempTitle !== '') {
              menuTitle = $("<a>");
              menuTitle.attr("href", tempLink);
              menuTitle.attr("title", tempTitle);
              menuTitle.text(tempTitle);
              menuTitle.addClass("menu-title");
            }
          } // menu_header_height = ($('.main-menu-header').length) ? $('.main-menu-header').height() : 0,
          // fromTop = menu_header_height + $this.position().top + parseInt($this.css( "border-top" ),10);


          var fromTop;

          if ($this.css("border-top")) {
            fromTop = $this.position().top + parseInt($this.css("border-top"), 10);
          } else {
            fromTop = $this.position().top;
          }

          if ($body.data('menu') !== 'vertical-compact-menu') {
            menuTitle.appendTo('.main-menu-content').css({
              position: 'fixed',
              top: fromTop
            });
          } // Content


          if ($this.hasClass('has-sub') && $this.hasClass('nav-item')) {
            var menuContent = $this.children('ul:first');
            menuObj.adjustSubmenu($this);
          }
        }

        $this.addClass('hover');
      }).on('mouseleave.app.menu', 'li', function () {// $(this).removeClass('hover');
      }).on('active.app.menu', 'li', function (e) {
        $(this).addClass('active');
        e.stopPropagation();
      }).on('deactive.app.menu', 'li.active', function (e) {
        $(this).removeClass('active');
        e.stopPropagation();
      }).on('open.app.menu', 'li', function (e) {
        var $listItem = $(this);
        $listItem.addClass('open');
        menuObj.expand($listItem); // If menu collapsible then do not take any action

        if ($('.main-menu').hasClass('menu-collapsible')) {
          return false;
        } // If menu accordion then close all except clicked once
        else {
          $listItem.siblings('.open').find('li.open').trigger('close.app.menu');
          $listItem.siblings('.open').trigger('close.app.menu');
        }

        e.stopPropagation();
      }).on('close.app.menu', 'li.open', function (e) {
        var $listItem = $(this);
        $listItem.removeClass('open');
        menuObj.collapse($listItem);
        e.stopPropagation();
      }).on('click.app.menu', 'li', function (e) {
        var $listItem = $(this);

        if ($listItem.is('.disabled')) {
          e.preventDefault();
        } else {
          if ($body.hasClass('menu-collapsed')) {
            e.preventDefault();
          } else {
            if ($listItem.has('ul')) {
              if ($listItem.is('.open')) {
                $listItem.trigger('close.app.menu');
              } else {
                $listItem.trigger('open.app.menu');
              }
            } else {
              if (!$listItem.is('.active')) {
                $listItem.siblings('.active').trigger('deactive.app.menu');
                $listItem.trigger('active.app.menu');
              }
            }
          }
        }

        e.stopPropagation();
      });
      $('.main-menu-content').on('mouseleave', function () {
        if ($body.hasClass('menu-collapsed')) {
          $('.main-menu-content').children('span.menu-title').remove();
          $('.main-menu-content').children('a.menu-title').remove();
          $('.main-menu-content').children('ul.menu-content').remove();
        }

        $('.hover', '.navigation-main').removeClass('hover');
      }); // If list item has sub menu items then prevent redirection.

      $('.navigation-main li.has-sub > a').on('click', function (e) {
        e.preventDefault();
      });
      $('ul.menu-content').on('click', 'li', function (e) {
        var $listItem = $(this);

        if ($listItem.is('.disabled')) {
          e.preventDefault();
        } else {
          if ($listItem.has('ul')) {
            if ($listItem.is('.open')) {
              $listItem.removeClass('open');
              menuObj.collapse($listItem);
            } else {
              $listItem.addClass('open');
              menuObj.expand($listItem); // If menu collapsible then do not take any action

              if ($('.main-menu').hasClass('menu-collapsible')) {
                return false;
              } // If menu accordion then close all except clicked once
              else {
                $listItem.siblings('.open').find('li.open').trigger('close.app.menu');
                $listItem.siblings('.open').trigger('close.app.menu');
              }

              e.stopPropagation();
            }
          } else {
            if (!$listItem.is('.active')) {
              $listItem.siblings('.active').trigger('deactive.app.menu');
              $listItem.trigger('active.app.menu');
            }
          }
        }

        e.stopPropagation();
      });
    },

    /**
     * Ensure an admin submenu is within the visual viewport.
     * @param {jQuery} $menuItem The parent menu item containing the submenu.
     */
    adjustSubmenu: function adjustSubmenu($menuItem) {
      var menuHeaderHeight,
          menutop,
          topPos,
          winHeight,
          bottomOffset,
          subMenuHeight,
          popOutMenuHeight,
          borderWidth,
          scroll_theme,
          $submenu = $menuItem.children('ul:first'),
          ul = $submenu.clone(true);
      menuHeaderHeight = $('.main-menu-header').height();
      menutop = $menuItem.position().top;
      winHeight = $window.height() - $('.header-navbar').height();
      borderWidth = 0;
      subMenuHeight = $submenu.height();

      if (parseInt($menuItem.css("border-top"), 10) > 0) {
        borderWidth = parseInt($menuItem.css("border-top"), 10);
      }

      popOutMenuHeight = winHeight - menutop - $menuItem.height() - 30;
      scroll_theme = $('.main-menu').hasClass('menu-dark') ? 'light' : 'dark';
      topPos = menutop + $menuItem.height() + borderWidth;
      ul.addClass('menu-popout').appendTo('.main-menu-content').css({
        'top': topPos,
        'position': 'fixed',
        'max-height': popOutMenuHeight
      });
      $('.main-menu-content > ul.menu-content').perfectScrollbar({
        theme: scroll_theme
      });
    },
    collapse: function collapse($listItem, callback) {
      var $subList = $listItem.children('ul');
      $subList.show().slideUp($.app.nav.config.speed, function () {
        $(this).css('display', '');
        $(this).find('> li').removeClass('is-shown');

        if (callback) {
          callback();
        }

        $.app.nav.container.trigger('collapsed.app.menu');
      });
    },
    expand: function expand($listItem, callback) {
      var $subList = $listItem.children('ul');
      var $children = $subList.children('li').addClass('is-hidden');
      $subList.hide().slideDown($.app.nav.config.speed, function () {
        $(this).css('display', '');

        if (callback) {
          callback();
        }

        $.app.nav.container.trigger('expanded.app.menu');
      });
      setTimeout(function () {
        $children.addClass('is-shown');
        $children.removeClass('is-hidden');
      }, 0);
    },
    refresh: function refresh() {
      $.app.nav.container.find('.open').removeClass('open');
    }
  };
})(window, document, jQuery);
})();

// This entry need to be wrapped in an IIFE because it need to be isolated against other entry modules.
(() => {
/*!***********************************************!*\
  !*** ./resources/admin-assets/js/core/app.js ***!
  \***********************************************/
(function (window, document, $) {
  'use strict';

  var $html = $('html');
  var $body = $('body');
  $(window).on('load', function () {
    var rtl;

    if ($('html').data('textdirection') == 'rtl') {
      rtl = true;
    }

    setTimeout(function () {
      $html.removeClass('loading').addClass('loaded');
    }, 1200);
    $.app.menu.init(); // Navigation configurations

    var config = {
      speed: 300 // set speed to expand / collpase menu

    };

    if ($.app.nav.initialized === false) {
      $.app.nav.init(config);
    }

    Unison.on('change', function (bp) {
      $.app.menu.change();
    }); // Tooltip Initialization

    $('[data-toggle="tooltip"]').tooltip({
      container: 'body'
    }); // Top Navbars - Hide on Scroll

    if ($(".navbar-hide-on-scroll").length > 0) {
      $(".navbar-hide-on-scroll.navbar-fixed-top").headroom({
        "offset": 205,
        "tolerance": 5,
        "classes": {
          // when element is initialised
          initial: "headroom",
          // when scrolling up
          pinned: "headroom--pinned-top",
          // when scrolling down
          unpinned: "headroom--unpinned-top"
        }
      }); // Bottom Navbars - Hide on Scroll

      $(".navbar-hide-on-scroll.navbar-fixed-bottom").headroom({
        "offset": 205,
        "tolerance": 5,
        "classes": {
          // when element is initialised
          initial: "headroom",
          // when scrolling up
          pinned: "headroom--pinned-bottom",
          // when scrolling down
          unpinned: "headroom--unpinned-bottom"
        }
      });
    } // Mega menu slick carousel

    /*if ($('.responsive-slick').length > 0) {
        var $megamenuSlick = $('.responsive-slick');
        $megamenuSlick.slick({
            rtl: rtl,
            dots: false,
            arrows: false,
            infinite: true,
            autoplay: true,
            autoplaySpeed: 2000,
            slidesToShow: 1,
            slidesToScroll: 1
        });
    }*/
    // Collapsible Card


    $('a[data-action="collapse"]').on('click', function (e) {
      e.preventDefault();
      $(this).closest('.card').children('.card-body').collapse('toggle');
      $(this).closest('.card').find('[data-action="collapse"] i').toggleClass('icon-minus4 icon-plus4');
    }); // Toggle fullscreen

    $('a[data-action="expand"]').on('click', function (e) {
      e.preventDefault();
      $(this).closest('.card').find('[data-action="expand"] i').toggleClass('icon-expand2 icon-contract');
      $(this).closest('.card').toggleClass('card-fullscreen');
    }); //  Notifications & messages scrollable

    if ($('.scrollable-container').length > 0) {
      $('.scrollable-container').perfectScrollbar({
        theme: "dark"
      });
    } // Reload Card


    $('a[data-action="reload"]').on('click', function () {
      var block_ele = $(this).closest('.card'); // Block Element

      block_ele.block({
        message: '<div class="ft-refresh-cw icon-spin font-medium-2"></div>',
        timeout: 2000,
        //unblock after 2 seconds
        overlayCSS: {
          backgroundColor: '#FFF',
          cursor: 'wait'
        },
        css: {
          border: 0,
          padding: 0,
          backgroundColor: 'none'
        }
      });
    }); // Close Card

    $('a[data-action="close"]').on('click', function () {
      $(this).closest('.card').removeClass().slideUp('fast');
    }); // Match the height of each card in a row

    setTimeout(function () {
      $('.row.match-height').each(function () {
        $(this).find('.card').not('.card .card').matchHeight(); // Not .card .card prevents collapsible cards from taking height
      });
    }, 500);
    $('.card .heading-elements a[data-action="collapse"]').on('click', function () {
      var $this = $(this),
          card = $this.closest('.card');
      var cardHeight;
      console.log(parseInt(card[0].style.height, 10));

      if (parseInt(card[0].style.height, 10) > 0) {
        cardHeight = card.css('height');
        card.css('height', '').attr('data-height', cardHeight);
      } else {
        if (card.data('height')) {
          cardHeight = card.data('height');
          card.css('height', cardHeight).attr('data-height', '');
        }
      }
    }); //Adjust fixed header body top padding

    /*if(!$('.header-navbar').hasClass('navbar-fixed-top')){
        $('body').css('padding-top',0);
    }*/
    // Remove the top padding in

    /*if(! $('.header-navbar').hasClass('navbar-fixed-top')){
        $('body').css('padding','0');
    }*/
    // Add open class to parent list item if subitem is active except compact menu

    var menuType = $body.data('menu');

    if (menuType != 'horizontal-menu') {
      $(".main-menu-content").find('li.active').parents('li').addClass('open');
    }

    if (menuType == 'horizontal-menu') {
      $(".main-menu-content").find('li.active').parents('li:not(.nav-item)').addClass('open');
      $(".main-menu-content").find('li.active').parents('li').addClass('active');
    } //card heading actions buttons small screen support


    $(".heading-elements-toggle").on("click", function () {
      $(this).parent().children(".heading-elements").toggleClass("visible");
    }); //  Dynamic height for the chartjs div for the chart animations to work

    var chartjsDiv = $('.chartjs'),
        canvasHeight = chartjsDiv.children('canvas').attr('height');
    chartjsDiv.css('height', canvasHeight);

    if ($('body').hasClass('boxed-layout')) {
      if ($('body').hasClass('vertical-overlay-menu')) {
        var menuWidth = $('.main-menu').width();
        var contentPosition = $('.app-content').position().left;
        var menuPositionAdjust = contentPosition - menuWidth;

        if ($('body').hasClass('menu-flipped')) {
          $('.main-menu').css('right', menuPositionAdjust + 'px');
        } else {
          $('.main-menu').css('left', menuPositionAdjust + 'px');
        }
      }
    }

    $('.nav-link-search').on('click', function () {
      var $this = $(this),
          searchInput = $(this).siblings('.search-input');

      if (searchInput.hasClass('open')) {
        searchInput.removeClass('open');
      } else {
        searchInput.addClass('open');
      }
    });
  });
  $(document).on('click', '.menu-toggle', function (e) {
    e.preventDefault(); // Toggle menu

    $.app.menu.toggle();
    setTimeout(function () {
      $(window).trigger("resize");
    }, 100);
    return false;
  });
  $(document).on('click', '.open-navbar-container', function (e) {
    var currentBreakpoint = Unison.fetch.now(); // Init drilldown on small screen

    $.app.menu.drillDownMenu(currentBreakpoint.name); // return false;
  });
  $(document).on('click', '.main-menu-footer .footer-toggle', function (e) {
    e.preventDefault();
    $(this).find('i').toggleClass('pe-is-i-angle-down pe-is-i-angle-up');
    $('.main-menu-footer').toggleClass('footer-close footer-open');
    return false;
  }); // Add Children Class

  $('.navigation').find('li').has('ul').addClass('has-sub');
  $('.carousel').carousel({
    interval: 2000
  }); // Page full screen

  $('.nav-link-expand').on('click', function (e) {
    if (typeof screenfull != 'undefined') {
      if (screenfull.enabled) {
        screenfull.toggle();
      }
    }
  });

  if (typeof screenfull != 'undefined') {
    if (screenfull.enabled) {
      $(document).on(screenfull.raw.fullscreenchange, function () {
        if (screenfull.isFullscreen) {
          $('.nav-link-expand').find('i').toggleClass('icon-contract icon-expand2');
        } else {
          $('.nav-link-expand').find('i').toggleClass('icon-expand2 icon-contract');
        }
      });
    }
  }

  $(document).on('click', '.mega-dropdown-menu', function (e) {
    e.stopPropagation();
  });
  $(document).ready(function () {
    /**********************************
    *   Form Wizard Step Icon
    **********************************/
    $('.step-icon').each(function () {
      var $this = $(this);

      if ($this.siblings('span.step').length > 0) {
        $this.siblings('span.step').empty();
        $(this).appendTo($(this).siblings('span.step'));
      }
    });
  }); // Update manual scroller when window is resized

  $(window).resize(function () {
    $.app.menu.manualScroller.updateHeight();
  }); // TODO : Tabs dropdown fix, remove this code once fixed in bootstrap 4.

  $('.nav.nav-tabs a.dropdown-item').on('click', function () {
    var $this = $(this),
        href = $this.attr('href');
    var tabs = $this.closest('.nav');
    tabs.find('.nav-link').removeClass('active');
    $this.closest('.nav-item').find('.nav-link').addClass('active');
    $this.closest('.dropdown-menu').find('.dropdown-item').removeClass('active');
    $this.addClass('active');
    tabs.next().find(href).siblings('.tab-pane').removeClass('active in').attr('aria-expanded', false);
    $(href).addClass('active in').attr('aria-expanded', 'true');
  });
  $('#sidebar-page-navigation').on('click', 'a.nav-link', function (e) {
    e.preventDefault();
    e.stopPropagation();
    var $this = $(this),
        href = $this.attr('href');
    var offset = $(href).offset();
    var scrollto = offset.top - 80; // minus fixed header height

    $('html, body').animate({
      scrollTop: scrollto
    }, 0);
    setTimeout(function () {
      $this.parent('.nav-item').siblings('.nav-item').children('.nav-link').removeClass('active');
      $this.addClass('active');
    }, 100);
  });
})(window, document, jQuery);
})();

/******/ })()
;