# -------------------------------------------------------------------
# components / main-menu.coffee
#

###
 * Class that provides the main menu functionality.
 *
 * @class MainMenu
###

PixelAdmin.MainMenu = ->
  @_scroller = null
  @_screen = null
  @_last_screen = null
  @_$dropdown = null
  # @_animate = $('body').hasClass('main-menu-animated')
  @

###
 * Initialize plugin.
###
PixelAdmin.MainMenu.prototype.init = ->
  @$window = $(window)
  @$body = $('body')
  @$menu = $('#main-menu')
  @$animation_timer = null

  return unless @$menu.length

  # Restore menu state
  if PixelAdmin.settings.main_menu.store_state
    state = @_getMenuState()
    if state != null
      if state is 'collapsed'
        @$body.addClass('mm-no-transition').addClass('mmc')
        setTimeout =>
          @$body.removeClass('mm-no-transition')
        , 20
      else
        @$body.addClass('mm-no-transition').removeClass('mmc')
        setTimeout =>
          @$body.removeClass('mm-no-transition')
        , 20

  # Local variables
  $ssw_point = $('#small-screen-width-point')
  $tsw_point = $('#tablet-screen-width-point')
  self = @

  @_screen = getScreenSize($ssw_point, $tsw_point)
  @_last_screen = @_screen

  # Turn on dropdowns animation if the body has class '.main-menu-animated'
  # if @_animate and not (PixelAdmin.settings.main_menu.disable_animation_on_mobile and (@_screen is 'small' or @_screen is 'tablet'))
  # if @_animate
  @turnOnAnimation(true)
  @$window.on 'pa.loaded', =>
    $('#main-menu .navigation > li > a > .mm-text').removeClass 'no-animation'
    $('#main-menu .navigation > .mm-dropdown > ul').removeClass 'no-animation'
    $('#main-menu .menu-content').removeClass 'no-animation'
  
  # Resize event
  @$window.on 'resize.pa.mm', =>
    # Detect screen type
    @_last_screen = @_screen
    @_screen = getScreenSize($ssw_point, $tsw_point)

    # Close dropdowns
    @closeCurrentDropdown(true)

    # Remove animation
    if (@_screen == 'small' and @_last_screen != @_screen) or (@_screen == 'tablet' and @_last_screen == 'small')
      @$body.addClass('mm-no-transition')
      setTimeout =>
        @$body.removeClass('mm-no-transition')
      , 20
  
  @animation_end = =>
    if @$animation_timer
      window.clearTimeout @$animation_timer
      @$animation_timer = null

    @$animation_timer = window.setTimeout =>
      @$menu.off 'transitionend webkitTransitionEnd oTransitionEnd MSTransitionEnd'
      $(window).trigger('resize')
    , 200

  @$window.on 'click.pa.mm', => @closeCurrentDropdown(true)

  # On screen type change
  $(window).on 'pa.screen.small', =>
    # Attach scroller on a small screens
    @_setupScroller() if @_scroller is null
    # if @_animate and PixelAdmin.settings.main_menu.disable_animation_on_mobile and @_last_screen isnt 'small' and @_last_screen isnt 'tablet'
    #   @turnOffAnimation()

  # .on 'pa.screen.tablet', =>
  #   if @_animate and PixelAdmin.settings.main_menu.disable_animation_on_mobile and @_last_screen isnt 'small' and @_last_screen isnt 'tablet'
  #     @turnOffAnimation()

  # .on 'pa.screen.desktop', =>
  #   if @_animate and PixelAdmin.settings.main_menu.disable_animation_on_mobile and @_last_screen is 'small' or @_last_screen is 'tablet'
  #     @turnOnAnimation(false)

  .on 'pa.screen.tablet pa.screen.desktop', =>
    # Attach scroller if the main menu is fixed
    if @$menu.css('position') is 'fixed'
      @_setupScroller() if @_scroller is null
    else
      @_removeScroller() if @_scroller isnt null

  # Mark root elements
  @$menu.find('.navigation > .mm-dropdown').addClass('mm-dropdown-root')

  # Bind click event on the dropdown toggle
  @$menu.on 'click.pa.mm-dropdown', '.mm-dropdown > a', ->
    $elem = $(this).parent('.mm-dropdown')
    
    # $elem is a root and main menu is collapsed?
    if $elem.hasClass('mm-dropdown-root') and self._collapsed()
      # Is dropdown menu already opened?
      if $elem.hasClass('mmc-dropdown-open')
        if $elem.hasClass('freeze')
          self.closeCurrentDropdown(true)
        else
          self.freezeDropdown($elem)
      # Dropdown menu is closed
      else
        self.openDropdown($elem, true)
    
    # Main menu is expanded or $elem isn't a root
    else
      # Is submenu already opened?
      if $elem.hasClass('open')
        self.collapseSubmenu($elem, true)
      # Submenu is closed
      else
        self.collapseAllSubmenus($elem) if PixelAdmin.settings.main_menu.accordion
        self.expandSubmenu($elem, true)
    false

  # Open dropdown on mouse enter
  @$menu.find('.navigation').on 'mouseenter.pa.mm-dropdown', '.mm-dropdown-root', ->
    if self._collapsed() and (not self._$dropdown or not self._$dropdown.hasClass('freeze'))
      self.openDropdown($(this))
  # Close dropdown on mouse leave
  .on 'mouseleave.pa.mm-dropdown', '.mm-dropdown-root', ->
    self.closeCurrentDropdown()

  # Expand/collapse main menu
  $('#main-menu-toggle').on 'click.pa.mm_toggle', =>
    @_screen = getScreenSize($ssw_point, $tsw_point)
    if @_screen is 'small' or @_screen is 'tablet'
      # Collapse main navigation bar
      $('#main-navbar-collapse').removeClass('in').removeClass('collapsing').stop().addClass('collapse').css('height', '0px')
      $('#main-navbar .navbar-toggle').addClass('collapsed')

      # Expand main menu
      @$body.removeClass('mm-no-transition').toggleClass('mme')
    else
      # Collapse main menu
      @$body.toggleClass('mmc')
      @_storeMenuState(@$body.hasClass('mmc')) if PixelAdmin.settings.main_menu.store_state
      
      # if @_animate and $.support.transition and not (PixelAdmin.settings.main_menu.disable_animation_on_mobile and (@_screen is 'small' or @_screen is 'tablet'))
      if $.support.transition
        @$menu.on 'transitionend webkitTransitionEnd oTransitionEnd MSTransitionEnd', $.proxy(@animation_end, @)
      else
        $(window).trigger('resize')
    setTimeout($.proxy(@_updateScroller, @), 100) if @_scroller

###
 * Checking if the main menu is collapsed.
###
PixelAdmin.MainMenu.prototype._collapsed = ->
  ((@_screen is 'tablet' and not @$body.hasClass('mme')) or (@_screen is 'desktop' and @$body.hasClass('mmc')))

###
 * Setup scroller.
###
PixelAdmin.MainMenu.prototype._setupScroller = ->
  return if @_scroller isnt null
  if $('html').hasClass('gt-ie8')
    # Initialize IScroll
    @_scroller = new IScroll('#' + @$menu.attr('id'), { mouseWheel: true })
    @_scroller.on 'scrollStart', => @closeCurrentDropdown(true)
  else
    @_scroller = true

  $(window).on 'pa.resize.mm', $.proxy(@_updateScroller, @)
  @_updateScroller()

###
 * Remove scroller.
###
PixelAdmin.MainMenu.prototype._removeScroller = ->
  return if @_scroller is null
  # Destroy IScroll instance
  @_scroller.destroy() if @_scroller isnt true
  @_scroller = null
  $(window).off('pa.resize.mm')
  @$menu.attr('style', '')

###
 * Update scroller.
###
PixelAdmin.MainMenu.prototype._updateScroller = ->
  return if @_scroller is null
  if @_scroller isnt true
    # Not IE8 => refresh IScroll
    @_scroller.refresh()
  else
    # IE8 => add scrollbars
    if @$menu.find('> div').outerHeight() > @$menu.outerHeight()
      @$menu.css
        'overflow-y': 'scroll',
        'overflow-y': 'scroll',
        '-ms-overflow-x': 'hidden',
        'overflow-x': 'hidden'
    else
      # Remove scrollbars
      @$menu.attr('style', '')

###
 * Update dropdown scroller.
 *
 * @param  {jQuery Object} $elem
###
PixelAdmin.MainMenu.prototype._updateDropdownScroller = ($elem) ->
  $parent = $elem.parents('.mmc-dropdown-open')
  if $parent.length
    scroller = $parent.data('scroller')
    scroller.refresh() if scroller and scroller isnt true

###
 * Get height of the dropdown.
 *
 * @param  {jQuery Object} $ul
###
PixelAdmin.MainMenu.prototype._getSubmenuHeight = ($ul) ->
  if $ul.parent('.mm-dropdown').hasClass('open')
    height = $ul.height()
  else
    height = $ul.addClass('get-height').height()
    $ul.removeClass('get-height')
  height

###
 * Collapse main menu dropdown.
 *
 * @param  {jQuery Object} $elem
 * @param  {Boolean} refreshScroll
###
PixelAdmin.MainMenu.prototype.collapseSubmenu = ($elem, refreshScroll) ->
  ul = $('> ul', $elem)
  ul.animate { height: 0 }, PixelAdmin.settings.main_menu.animation_speed, =>
    $elem.removeClass('open')
    ul.css
      display: 'none'
      height: 'auto'

    # Close all opened submenus
    $('.mm-dropdown.open', $elem).removeClass('open').find('> ul').css({ display: 'none', height: 'auto'})
    
    @_updateScroller() if refreshScroll
    @_updateDropdownScroller($elem)

###
 * Collapse main menu dropdown.
 *
 * @param  {jQuery Object} $elem
 * @param  {Boolean} refreshScroll
###
PixelAdmin.MainMenu.prototype.collapseAllSubmenus = ($elem, close_other=false) ->
  self = @
  $elem.parent().find('> .mm-dropdown.open').each ->
    self.collapseSubmenu($(this)) if not close_other or not $(this).is($elem)

###
 * Expand main menu dropdown.
 *
 * @param  {jQuery Object} $elem
 * @param  {Boolean} refreshScroll
###
PixelAdmin.MainMenu.prototype.expandSubmenu = ($elem, refreshScroll) ->
  ul     = $('> ul', $elem)
  height = @_getSubmenuHeight(ul) 

  ul.css
    display: 'block'
    'height': 0
  $elem.addClass('open')

  ul.animate { height: height }, PixelAdmin.settings.main_menu.animation_speed, =>
    ul.attr 'style', ''

    @_updateScroller() if refreshScroll
    @_updateDropdownScroller($elem)
    
###
 * Open dropdown menu.
 *
 * @param  {jQuery Object} $elem
 * @param  {Boolean} freeze
###
PixelAdmin.MainMenu.prototype.openDropdown = ($elem, freeze=false) ->
  @closeCurrentDropdown(freeze) if @_$dropdown
  @_$dropdown = $elem
  
  $ul = $('> ul', $elem)
  $title = $ul.find('> .mmc-title')
  # Add dropdown title
  if $title.length is 0
    $title = $('<div class="mmc-title"></div>').text($('> a > .mm-text', $elem).text())
    $ul.prepend($title)
  $elem.addClass('mmc-dropdown-open')

  setupScroller = ($w) =>
    # Not IE 8
    if $('html').hasClass('gt-ie8')
      scroller = new IScroll('#mmc-wrapper', { mouseWheel: true })
      if @_scroller and @_scroller isnt null
        scroller.on 'beforeScrollStart', => @_scroller.disable()
        scroller.on 'scrollEnd', => @_scroller.enable()
    # IE 8
    else
      scroller = true
      $w.css
        'overflow-y': 'scroll',
        'overflow-y': 'scroll',
        '-ms-overflow-x': 'hidden',
        'overflow-x': 'hidden'
    $elem.data('scroller', scroller)

  # Initialize dropdown scroller if the main menu is fixed
  if @$body.hasClass('main-menu-fixed')
    w_height = @$window.innerHeight()
    top = $elem.position().top
    min_height = $ul.find('> .mmc-title').outerHeight() + $ul.find('> li').first().outerHeight() * 3

    if (top + min_height) > w_height
      max_height = top - $('#main-navbar').outerHeight()
      $elem.addClass('top')
    else
      max_height = w_height - top - $elem.outerHeight()
    
    # Append wrapper to the dropdown menu
    $w = $('<div id="mmc-wrapper" style="overflow:hidden;position:relative;max-height:' + max_height + 'px;"></div>')
    $w.append($('<div></div>').append($ul.find('> li'))).appendTo($ul)
    setupScroller($w)

    $ul.append($title) if $elem.hasClass('top')
  @freezeDropdown($elem) if freeze

###
 * Close dropdown menu.
 *
 * @param  {Boolean} freeze
###
PixelAdmin.MainMenu.prototype.closeCurrentDropdown = (force=false) ->
  return if not @_$dropdown or (@_$dropdown.hasClass('freeze') and not force)
  $ul = @_$dropdown.find('> ul')

  scroller = @_$dropdown.data('scroller')
  if scroller
    # Remove dropdown scroller
    scroller.destroy() if scroller isnt true
    @_$dropdown.data('scroller', null)

    $ul.append($('#mmc-wrapper > div > li'))
    $('#mmc-wrapper').remove()

  @_scroller.enable() if @_scroller and @_scroller isnt true
  
  @_$dropdown.removeClass('mmc-dropdown-open freeze top')
  @_$dropdown = null

###
 * Freeze dropdown menu.
 *
 * @param  {jQuery Object} $dd
###
PixelAdmin.MainMenu.prototype.freezeDropdown = ($dd) ->
  $dd.addClass('freeze')

###
 * Turn on dropdowns animation.
###
PixelAdmin.MainMenu.prototype.turnOnAnimation = (delayed=false) ->
  @$body.addClass('main-menu-animated')
  if delayed
    $('#main-menu .navigation > li > a > .mm-text').addClass 'no-animation'
    $('#main-menu .navigation > .mm-dropdown > ul').addClass 'no-animation'
    $('#main-menu .menu-content').addClass 'no-animation'
  $('#main-menu .navigation > li > a > .mm-text').addClass 'mmc-dropdown-delay animated fadeIn'
  $('#main-menu .navigation > .mm-dropdown > ul').addClass 'mmc-dropdown-delay animated fadeInLeft'
  $('#main-menu .menu-content').addClass 'animated fadeIn'
  if @$body.hasClass('main-menu-right') or (@$body.hasClass('right-to-left') and not @$body.hasClass('main-menu-right'))
    $('#main-menu .navigation > .mm-dropdown > ul').addClass 'fadeInRight'
  else
    $('#main-menu .navigation > .mm-dropdown > ul').addClass 'fadeInLeft'

###
 * Turn off dropdowns animation.
###
PixelAdmin.MainMenu.prototype.turnOffAnimation = () ->
  @$body.removeClass('main-menu-animated')
  $('#main-menu .navigation > li > a > .mm-text').removeClass 'mmc-dropdown-delay animated fadeIn'
  $('#main-menu .menu-content').removeClass 'animated fadeIn'
  $('#main-menu .navigation > .mm-dropdown > ul').removeClass 'mmc-dropdown-delay animated fadeInLeft fadeInRight'

###
 * Load menu state.
###
PixelAdmin.MainMenu.prototype._getMenuState = () ->
  PixelAdmin.getStoredValue(PixelAdmin.settings.main_menu.store_state_key, null)

###
 * Store menu state.
###
PixelAdmin.MainMenu.prototype._storeMenuState = (is_collapsed) ->
  return unless PixelAdmin.settings.main_menu.store_state
  PixelAdmin.storeValue(PixelAdmin.settings.main_menu.store_state_key, if is_collapsed then 'collapsed' else 'expanded')

# ###
#  * Detecting a change of the window height.
# ###
# PixelAdmin.MainMenu.prototype._watchWindowHeight = ->
#   @_wheight = $(window).innerHeight()
#   checkWindowInnerHeight = =>
#     return if @_wheight is null
#     if @_wheight != $(window).innerHeight()
#       # Redraw menu
#       @$menu.hide(0, => @$menu.show())
#       @$menu.attr('style', '')
#       @_updateScroller()
#     @_wheight = $(window).innerHeight()
#     setTimeout(checkWindowInnerHeight, 100)
#   window.setTimeout(checkWindowInnerHeight, 100)

PixelAdmin.MainMenu.Constructor = PixelAdmin.MainMenu
PixelAdmin.addInitializer ->
  # Initialize plugin.
  PixelAdmin.initPlugin 'main_menu', new PixelAdmin.MainMenu