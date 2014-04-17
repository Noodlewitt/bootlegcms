
/*
 * Class that provides the main menu functionality.
 *
 * @class MainMenu
 */

(function() {
  PixelAdmin.MainMenu = function() {
    this._scroller = null;
    this._screen = null;
    this._last_screen = null;
    this._$dropdown = null;
    return this;
  };


  /*
   * Initialize plugin.
   */

  PixelAdmin.MainMenu.prototype.init = function() {
    var $ssw_point, $tsw_point, self, state;
    this.$window = $(window);
    this.$body = $('body');
    this.$menu = $('#main-menu');
    this.$animation_timer = null;
    if (!this.$menu.length) {
      return;
    }
    if (PixelAdmin.settings.main_menu.store_state) {
      state = this._getMenuState();
      if (state !== null) {
        if (state === 'collapsed') {
          this.$body.addClass('mm-no-transition').addClass('mmc');
          setTimeout((function(_this) {
            return function() {
              return _this.$body.removeClass('mm-no-transition');
            };
          })(this), 20);
        } else {
          this.$body.addClass('mm-no-transition').removeClass('mmc');
          setTimeout((function(_this) {
            return function() {
              return _this.$body.removeClass('mm-no-transition');
            };
          })(this), 20);
        }
      }
    }
    $ssw_point = $('#small-screen-width-point');
    $tsw_point = $('#tablet-screen-width-point');
    self = this;
    this._screen = getScreenSize($ssw_point, $tsw_point);
    this._last_screen = this._screen;
    this.turnOnAnimation(true);
    this.$window.on('pa.loaded', (function(_this) {
      return function() {
        $('#main-menu .navigation > li > a > .mm-text').removeClass('no-animation');
        $('#main-menu .navigation > .mm-dropdown > ul').removeClass('no-animation');
        return $('#main-menu .menu-content').removeClass('no-animation');
      };
    })(this));
    this.$window.on('resize.pa.mm', (function(_this) {
      return function() {
        _this._last_screen = _this._screen;
        _this._screen = getScreenSize($ssw_point, $tsw_point);
        _this.closeCurrentDropdown(true);
        if ((_this._screen === 'small' && _this._last_screen !== _this._screen) || (_this._screen === 'tablet' && _this._last_screen === 'small')) {
          _this.$body.addClass('mm-no-transition');
          return setTimeout(function() {
            return _this.$body.removeClass('mm-no-transition');
          }, 20);
        }
      };
    })(this));
    this.animation_end = (function(_this) {
      return function() {
        if (_this.$animation_timer) {
          window.clearTimeout(_this.$animation_timer);
          _this.$animation_timer = null;
        }
        return _this.$animation_timer = window.setTimeout(function() {
          _this.$menu.off('transitionend webkitTransitionEnd oTransitionEnd MSTransitionEnd');
          return $(window).trigger('resize');
        }, 200);
      };
    })(this);
    this.$window.on('click.pa.mm', (function(_this) {
      return function() {
        return _this.closeCurrentDropdown(true);
      };
    })(this));
    $(window).on('pa.screen.small', (function(_this) {
      return function() {
        if (_this._scroller === null) {
          return _this._setupScroller();
        }
      };
    })(this)).on('pa.screen.tablet pa.screen.desktop', (function(_this) {
      return function() {
        if (_this.$menu.css('position') === 'fixed') {
          if (_this._scroller === null) {
            return _this._setupScroller();
          }
        } else {
          if (_this._scroller !== null) {
            return _this._removeScroller();
          }
        }
      };
    })(this));
    this.$menu.find('.navigation > .mm-dropdown').addClass('mm-dropdown-root');
    this.$menu.on('click.pa.mm-dropdown', '.mm-dropdown > a', function() {
      var $elem;
      $elem = $(this).parent('.mm-dropdown');
      if ($elem.hasClass('mm-dropdown-root') && self._collapsed()) {
        if ($elem.hasClass('mmc-dropdown-open')) {
          if ($elem.hasClass('freeze')) {
            self.closeCurrentDropdown(true);
          } else {
            self.freezeDropdown($elem);
          }
        } else {
          self.openDropdown($elem, true);
        }
      } else {
        if ($elem.hasClass('open')) {
          self.collapseSubmenu($elem, true);
        } else {
          if (PixelAdmin.settings.main_menu.accordion) {
            self.collapseAllSubmenus($elem);
          }
          self.expandSubmenu($elem, true);
        }
      }
      return false;
    });
    this.$menu.find('.navigation').on('mouseenter.pa.mm-dropdown', '.mm-dropdown-root', function() {
      if (self._collapsed() && (!self._$dropdown || !self._$dropdown.hasClass('freeze'))) {
        return self.openDropdown($(this));
      }
    }).on('mouseleave.pa.mm-dropdown', '.mm-dropdown-root', function() {
      return self.closeCurrentDropdown();
    });
    return $('#main-menu-toggle').on('click.pa.mm_toggle', (function(_this) {
      return function() {
        _this._screen = getScreenSize($ssw_point, $tsw_point);
        if (_this._screen === 'small' || _this._screen === 'tablet') {
          $('#main-navbar-collapse').removeClass('in').removeClass('collapsing').stop().addClass('collapse').css('height', '0px');
          $('#main-navbar .navbar-toggle').addClass('collapsed');
          _this.$body.removeClass('mm-no-transition').toggleClass('mme');
        } else {
          _this.$body.toggleClass('mmc');
          if (PixelAdmin.settings.main_menu.store_state) {
            _this._storeMenuState(_this.$body.hasClass('mmc'));
          }
          if ($.support.transition) {
            _this.$menu.on('transitionend webkitTransitionEnd oTransitionEnd MSTransitionEnd', $.proxy(_this.animation_end, _this));
          } else {
            $(window).trigger('resize');
          }
        }
        if (_this._scroller) {
          return setTimeout($.proxy(_this._updateScroller, _this), 100);
        }
      };
    })(this));
  };


  /*
   * Checking if the main menu is collapsed.
   */

  PixelAdmin.MainMenu.prototype._collapsed = function() {
    return (this._screen === 'tablet' && !this.$body.hasClass('mme')) || (this._screen === 'desktop' && this.$body.hasClass('mmc'));
  };


  /*
   * Setup scroller.
   */

  PixelAdmin.MainMenu.prototype._setupScroller = function() {
    if (this._scroller !== null) {
      return;
    }
    if ($('html').hasClass('gt-ie8')) {
      this._scroller = new IScroll('#' + this.$menu.attr('id'), {
        mouseWheel: true
      });
      this._scroller.on('scrollStart', (function(_this) {
        return function() {
          return _this.closeCurrentDropdown(true);
        };
      })(this));
    } else {
      this._scroller = true;
    }
    $(window).on('pa.resize.mm', $.proxy(this._updateScroller, this));
    return this._updateScroller();
  };


  /*
   * Remove scroller.
   */

  PixelAdmin.MainMenu.prototype._removeScroller = function() {
    if (this._scroller === null) {
      return;
    }
    if (this._scroller !== true) {
      this._scroller.destroy();
    }
    this._scroller = null;
    $(window).off('pa.resize.mm');
    return this.$menu.attr('style', '');
  };


  /*
   * Update scroller.
   */

  PixelAdmin.MainMenu.prototype._updateScroller = function() {
    if (this._scroller === null) {
      return;
    }
    if (this._scroller !== true) {
      return this._scroller.refresh();
    } else {
      if (this.$menu.find('> div').outerHeight() > this.$menu.outerHeight()) {
        return this.$menu.css({
          'overflow-y': 'scroll',
          'overflow-y': 'scroll',
          '-ms-overflow-x': 'hidden',
          'overflow-x': 'hidden'
        });
      } else {
        return this.$menu.attr('style', '');
      }
    }
  };


  /*
   * Update dropdown scroller.
   *
   * @param  {jQuery Object} $elem
   */

  PixelAdmin.MainMenu.prototype._updateDropdownScroller = function($elem) {
    var $parent, scroller;
    $parent = $elem.parents('.mmc-dropdown-open');
    if ($parent.length) {
      scroller = $parent.data('scroller');
      if (scroller && scroller !== true) {
        return scroller.refresh();
      }
    }
  };


  /*
   * Get height of the dropdown.
   *
   * @param  {jQuery Object} $ul
   */

  PixelAdmin.MainMenu.prototype._getSubmenuHeight = function($ul) {
    var height;
    if ($ul.parent('.mm-dropdown').hasClass('open')) {
      height = $ul.height();
    } else {
      height = $ul.addClass('get-height').height();
      $ul.removeClass('get-height');
    }
    return height;
  };


  /*
   * Collapse main menu dropdown.
   *
   * @param  {jQuery Object} $elem
   * @param  {Boolean} refreshScroll
   */

  PixelAdmin.MainMenu.prototype.collapseSubmenu = function($elem, refreshScroll) {
    var ul;
    ul = $('> ul', $elem);
    return ul.animate({
      height: 0
    }, PixelAdmin.settings.main_menu.animation_speed, (function(_this) {
      return function() {
        $elem.removeClass('open');
        ul.css({
          display: 'none',
          height: 'auto'
        });
        $('.mm-dropdown.open', $elem).removeClass('open').find('> ul').css({
          display: 'none',
          height: 'auto'
        });
        if (refreshScroll) {
          _this._updateScroller();
        }
        return _this._updateDropdownScroller($elem);
      };
    })(this));
  };


  /*
   * Collapse main menu dropdown.
   *
   * @param  {jQuery Object} $elem
   * @param  {Boolean} refreshScroll
   */

  PixelAdmin.MainMenu.prototype.collapseAllSubmenus = function($elem, close_other) {
    var self;
    if (close_other == null) {
      close_other = false;
    }
    self = this;
    return $elem.parent().find('> .mm-dropdown.open').each(function() {
      if (!close_other || !$(this).is($elem)) {
        return self.collapseSubmenu($(this));
      }
    });
  };


  /*
   * Expand main menu dropdown.
   *
   * @param  {jQuery Object} $elem
   * @param  {Boolean} refreshScroll
   */

  PixelAdmin.MainMenu.prototype.expandSubmenu = function($elem, refreshScroll) {
    var height, ul;
    ul = $('> ul', $elem);
    height = this._getSubmenuHeight(ul);
    ul.css({
      display: 'block',
      'height': 0
    });
    $elem.addClass('open');
    return ul.animate({
      height: height
    }, PixelAdmin.settings.main_menu.animation_speed, (function(_this) {
      return function() {
        ul.attr('style', '');
        if (refreshScroll) {
          _this._updateScroller();
        }
        return _this._updateDropdownScroller($elem);
      };
    })(this));
  };


  /*
   * Open dropdown menu.
   *
   * @param  {jQuery Object} $elem
   * @param  {Boolean} freeze
   */

  PixelAdmin.MainMenu.prototype.openDropdown = function($elem, freeze) {
    var $title, $ul, $w, max_height, min_height, setupScroller, top, w_height;
    if (freeze == null) {
      freeze = false;
    }
    if (this._$dropdown) {
      this.closeCurrentDropdown(freeze);
    }
    this._$dropdown = $elem;
    $ul = $('> ul', $elem);
    $title = $ul.find('> .mmc-title');
    if ($title.length === 0) {
      $title = $('<div class="mmc-title"></div>').text($('> a > .mm-text', $elem).text());
      $ul.prepend($title);
    }
    $elem.addClass('mmc-dropdown-open');
    setupScroller = (function(_this) {
      return function($w) {
        var scroller;
        if ($('html').hasClass('gt-ie8')) {
          scroller = new IScroll('#mmc-wrapper', {
            mouseWheel: true
          });
          if (_this._scroller && _this._scroller !== null) {
            scroller.on('beforeScrollStart', function() {
              return _this._scroller.disable();
            });
            scroller.on('scrollEnd', function() {
              return _this._scroller.enable();
            });
          }
        } else {
          scroller = true;
          $w.css({
            'overflow-y': 'scroll',
            'overflow-y': 'scroll',
            '-ms-overflow-x': 'hidden',
            'overflow-x': 'hidden'
          });
        }
        return $elem.data('scroller', scroller);
      };
    })(this);
    if (this.$body.hasClass('main-menu-fixed')) {
      w_height = this.$window.innerHeight();
      top = $elem.position().top;
      min_height = $ul.find('> .mmc-title').outerHeight() + $ul.find('> li').first().outerHeight() * 3;
      if ((top + min_height) > w_height) {
        max_height = top - $('#main-navbar').outerHeight();
        $elem.addClass('top');
      } else {
        max_height = w_height - top - $elem.outerHeight();
      }
      $w = $('<div id="mmc-wrapper" style="overflow:hidden;position:relative;max-height:' + max_height + 'px;"></div>');
      $w.append($('<div></div>').append($ul.find('> li'))).appendTo($ul);
      setupScroller($w);
      if ($elem.hasClass('top')) {
        $ul.append($title);
      }
    }
    if (freeze) {
      return this.freezeDropdown($elem);
    }
  };


  /*
   * Close dropdown menu.
   *
   * @param  {Boolean} freeze
   */

  PixelAdmin.MainMenu.prototype.closeCurrentDropdown = function(force) {
    var $ul, scroller;
    if (force == null) {
      force = false;
    }
    if (!this._$dropdown || (this._$dropdown.hasClass('freeze') && !force)) {
      return;
    }
    $ul = this._$dropdown.find('> ul');
    scroller = this._$dropdown.data('scroller');
    if (scroller) {
      if (scroller !== true) {
        scroller.destroy();
      }
      this._$dropdown.data('scroller', null);
      $ul.append($('#mmc-wrapper > div > li'));
      $('#mmc-wrapper').remove();
    }
    if (this._scroller && this._scroller !== true) {
      this._scroller.enable();
    }
    this._$dropdown.removeClass('mmc-dropdown-open freeze top');
    return this._$dropdown = null;
  };


  /*
   * Freeze dropdown menu.
   *
   * @param  {jQuery Object} $dd
   */

  PixelAdmin.MainMenu.prototype.freezeDropdown = function($dd) {
    return $dd.addClass('freeze');
  };


  /*
   * Turn on dropdowns animation.
   */

  PixelAdmin.MainMenu.prototype.turnOnAnimation = function(delayed) {
    if (delayed == null) {
      delayed = false;
    }
    this.$body.addClass('main-menu-animated');
    if (delayed) {
      $('#main-menu .navigation > li > a > .mm-text').addClass('no-animation');
      $('#main-menu .navigation > .mm-dropdown > ul').addClass('no-animation');
      $('#main-menu .menu-content').addClass('no-animation');
    }
    $('#main-menu .navigation > li > a > .mm-text').addClass('mmc-dropdown-delay animated fadeIn');
    $('#main-menu .navigation > .mm-dropdown > ul').addClass('mmc-dropdown-delay animated fadeInLeft');
    $('#main-menu .menu-content').addClass('animated fadeIn');
    if (this.$body.hasClass('main-menu-right') || (this.$body.hasClass('right-to-left') && !this.$body.hasClass('main-menu-right'))) {
      return $('#main-menu .navigation > .mm-dropdown > ul').addClass('fadeInRight');
    } else {
      return $('#main-menu .navigation > .mm-dropdown > ul').addClass('fadeInLeft');
    }
  };


  /*
   * Turn off dropdowns animation.
   */

  PixelAdmin.MainMenu.prototype.turnOffAnimation = function() {
    this.$body.removeClass('main-menu-animated');
    $('#main-menu .navigation > li > a > .mm-text').removeClass('mmc-dropdown-delay animated fadeIn');
    $('#main-menu .menu-content').removeClass('animated fadeIn');
    return $('#main-menu .navigation > .mm-dropdown > ul').removeClass('mmc-dropdown-delay animated fadeInLeft fadeInRight');
  };


  /*
   * Load menu state.
   */

  PixelAdmin.MainMenu.prototype._getMenuState = function() {
    return PixelAdmin.getStoredValue(PixelAdmin.settings.main_menu.store_state_key, null);
  };


  /*
   * Store menu state.
   */

  PixelAdmin.MainMenu.prototype._storeMenuState = function(is_collapsed) {
    if (!PixelAdmin.settings.main_menu.store_state) {
      return;
    }
    return PixelAdmin.storeValue(PixelAdmin.settings.main_menu.store_state_key, is_collapsed ? 'collapsed' : 'expanded');
  };

  PixelAdmin.MainMenu.Constructor = PixelAdmin.MainMenu;

  PixelAdmin.addInitializer(function() {
    return PixelAdmin.initPlugin('main_menu', new PixelAdmin.MainMenu);
  });

}).call(this);
