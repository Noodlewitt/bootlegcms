
/*
 * Class that provides the top navbar functionality.
 *
 * @class MainNavbar
 */

(function() {
  PixelAdmin.MainNavbar = function() {
    this._scroller = null;
    this._wheight = null;
    return this;
  };


  /*
   * Initialize plugin.
   */

  PixelAdmin.MainNavbar.prototype.init = function() {
    var is_mobile;
    this.$navbar = $('#main-navbar');
    this.$header = this.$navbar.find('.navbar-header');
    this.$toggle = this.$navbar.find('.navbar-toggle:first');
    this.$collapse = $('#main-navbar-collapse');
    this.$collapse_div = this.$collapse.find('> div');
    is_mobile = false;
    $(window).on('pa.screen.small pa.screen.tablet', (function(_this) {
      return function() {
        if (_this._scroller === null && _this.$navbar.css('position') === 'fixed') {
          _this._setupScroller();
        }
        return is_mobile = true;
      };
    })(this)).on('pa.screen.desktop', (function(_this) {
      return function() {
        if (_this._scroller !== null) {
          _this._removeScroller();
        }
        return is_mobile = false;
      };
    })(this));
    return this.$navbar.on('click', '.nav-icon-btn.dropdown > .dropdown-toggle', function(e) {
      if (is_mobile) {
        e.preventDefault();
        e.stopPropagation();
        document.location.href = $(this).attr('href');
        return false;
      }
    });
  };


  /*
   * Attach scroller to navbar collapse.
   */

  PixelAdmin.MainNavbar.prototype._setupScroller = function() {
    if (this._scroller !== null) {
      return;
    }
    if ($('html').hasClass('gt-ie8')) {
      this._scroller = new IScroll('#' + this.$collapse.attr('id'), {
        scrollbars: true,
        mouseWheel: true,
        preventDefault: false
      });
      this.$navbar.on('mousedown.mn_collapse', $.proxy(this._mousedownCallback, this)).on('mousemove.mn_collapse', $.proxy(this._mousemoveCallback, this)).on('mouseup.mn_collapse', $.proxy(this._mouseupCallback, this)).on('touchstart.mn_collapse touchmove.mn_collapse', function(e) {
        return e.preventDefault();
      });
    } else {
      this._scroller = true;
    }
    this.$navbar.on('shown.bs.collapse.mn_collapse', $.proxy(((function(_this) {
      return function() {
        _this._updateCollapseHeight();
        return _this._watchWindowHeight();
      };
    })(this)), this)).on('hidden.bs.collapse.mn_collapse', $.proxy(((function(_this) {
      return function() {
        return _this._wheight = null;
      };
    })(this)), this)).on('shown.bs.dropdown.mn_collapse', $.proxy(this._updateCollapseHeight, this)).on('hidden.bs.dropdown.mn_collapse', $.proxy(this._updateCollapseHeight, this));
    return this._updateCollapseHeight();
  };


  /*
   * Detach scroller from navbar collapse.
   */

  PixelAdmin.MainNavbar.prototype._removeScroller = function() {
    if (this._scroller === null) {
      return;
    }
    this._wheight = null;
    if (this._scroller !== true) {
      this._scroller.destroy();
      this.$navbar.off('mousedown.mn_collapse').off('mousemove.mn_collapse').off('mouseup.mn_collapse').off('touchstart.mn_collapse touchmove.mn_collapse');
    }
    this._scroller = null;
    this.$navbar.off('shown.bs.collapse.mn_collapse').off('hidden.bs.collapse.mn_collapse').off('shown.bs.dropdown.mn_collapse').off('hidden.bs.dropdown.mn_collapse');
    return this.$collapse.attr('style', '');
  };


  /*
   * Mousedown callback.
   *
   * @param  {Event} e
   */

  PixelAdmin.MainNavbar.prototype._mousedownCallback = function(e) {
    if ($(e.target).is('input')) {
      return;
    }
    this._isMousePressed = true;
    return e.preventDefault();
  };


  /*
   * Mousemove callback.
   *
   * @param  {Event} e
   */

  PixelAdmin.MainNavbar.prototype._mousemoveCallback = function(e) {
    if (this._isMousePressed) {
      return e.preventDefault();
    }
  };


  /*
   * Mouseup callback.
   *
   * @param  {Event} e
   */

  PixelAdmin.MainNavbar.prototype._mouseupCallback = function(e) {
    if (this._isMousePressed) {
      this._isMousePressed = false;
      return e.preventDefault();
    }
  };


  /*
   * Update height of the collapse container and refresh scroller.
   */

  PixelAdmin.MainNavbar.prototype._updateCollapseHeight = function() {
    var h_height, w_height;
    if (this._scroller === null) {
      return;
    }
    w_height = $(window).innerHeight();
    h_height = this.$header.outerHeight();
    if ((h_height + this.$collapse_div.outerHeight()) > w_height) {
      this.$collapse.css('height', w_height - h_height);
      if (this._scroller !== true) {
        return this._scroller.refresh();
      } else {
        return this.$collapse.css('overflow', 'scroll');
      }
    } else {
      this.$collapse.attr('style', '');
      if (this._scroller !== true) {
        return this._scroller.refresh();
      }
    }
  };


  /*
   * Detecting a change of the window height.
   */

  PixelAdmin.MainNavbar.prototype._watchWindowHeight = function() {
    var checkWindowInnerHeight;
    this._wheight = $(window).innerHeight();
    checkWindowInnerHeight = (function(_this) {
      return function() {
        if (_this._wheight === null) {
          return;
        }
        if (_this._wheight !== $(window).innerHeight()) {
          _this._updateCollapseHeight();
        }
        _this._wheight = $(window).innerHeight();
        return setTimeout(checkWindowInnerHeight, 100);
      };
    })(this);
    return window.setTimeout(checkWindowInnerHeight, 100);
  };

  PixelAdmin.MainNavbar.Constructor = PixelAdmin.MainNavbar;

  PixelAdmin.addInitializer(function() {
    return PixelAdmin.initPlugin('main_navbar', new PixelAdmin.MainNavbar);
  });

}).call(this);
