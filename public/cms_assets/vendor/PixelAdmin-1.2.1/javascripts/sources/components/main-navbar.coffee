# -------------------------------------------------------------------
# components / main-navbar.coffee
#

###
 * Class that provides the top navbar functionality.
 *
 * @class MainNavbar
###

PixelAdmin.MainNavbar = ->
  @_scroller = null
  @_wheight = null
  @

###
 * Initialize plugin.
###
PixelAdmin.MainNavbar.prototype.init = ->
  @$navbar                = $('#main-navbar')
  @$header                = @$navbar.find('.navbar-header')
  @$toggle                = @$navbar.find('.navbar-toggle:first')
  @$collapse              = $('#main-navbar-collapse')
  @$collapse_div          = @$collapse.find('> div')
  is_mobile               = false

  $(window).on 'pa.screen.small pa.screen.tablet', =>
    # Attach scroller on a small screens and a tablets
    if @_scroller is null and @$navbar.css('position') is 'fixed'
      @_setupScroller()
    is_mobile = true
  .on 'pa.screen.desktop', =>
    # Detach scroller on a desktops
    @_removeScroller() if @_scroller isnt null
    is_mobile = false

  @$navbar.on 'click', '.nav-icon-btn.dropdown > .dropdown-toggle', (e) ->
    if is_mobile
      e.preventDefault()
      e.stopPropagation()
      document.location.href = $(@).attr('href')
      false

###
 * Attach scroller to navbar collapse.
###
PixelAdmin.MainNavbar.prototype._setupScroller = ->
  return if @_scroller isnt null
  
  if $('html').hasClass('gt-ie8')
    # Initialize IScroll
    @_scroller = new IScroll '#' + @$collapse.attr('id'),
      scrollbars: true
      mouseWheel: true
      preventDefault: false
    
    # Add callbacks
    @$navbar.on('mousedown.mn_collapse', $.proxy(@_mousedownCallback, @))
            .on('mousemove.mn_collapse', $.proxy(@_mousemoveCallback, @))
            .on('mouseup.mn_collapse',   $.proxy(@_mouseupCallback, @))
            .on('touchstart.mn_collapse touchmove.mn_collapse', (e) -> e.preventDefault())
  else
    @_scroller = true

  # Add callbacks
  @$navbar.on('shown.bs.collapse.mn_collapse',  $.proxy((=> @_updateCollapseHeight(); @_watchWindowHeight();), @))
          .on('hidden.bs.collapse.mn_collapse', $.proxy((=> @_wheight = null), @))
          .on('shown.bs.dropdown.mn_collapse',  $.proxy(@_updateCollapseHeight, @))
          .on('hidden.bs.dropdown.mn_collapse', $.proxy(@_updateCollapseHeight, @))
  @_updateCollapseHeight()

###
 * Detach scroller from navbar collapse.
###
PixelAdmin.MainNavbar.prototype._removeScroller = ->
  return if @_scroller is null
  @_wheight = null
  if @_scroller isnt true
    # Destroy IScroll instance
    @_scroller.destroy()

    # Remove callbacks
    @$navbar.off('mousedown.mn_collapse')
            .off('mousemove.mn_collapse')
            .off('mouseup.mn_collapse')
            .off('touchstart.mn_collapse touchmove.mn_collapse')

  @_scroller = null
  # Remove callbacks
  @$navbar.off('shown.bs.collapse.mn_collapse')
          .off('hidden.bs.collapse.mn_collapse')
          .off('shown.bs.dropdown.mn_collapse')
          .off('hidden.bs.dropdown.mn_collapse')
  @$collapse.attr('style', '')

###
 * Mousedown callback.
 *
 * @param  {Event} e
###
PixelAdmin.MainNavbar.prototype._mousedownCallback = (e) ->
  return if $(e.target).is('input') # Do not prevent event when target is a Input
  @_isMousePressed = true;
  e.preventDefault()

###
 * Mousemove callback.
 *
 * @param  {Event} e
###
PixelAdmin.MainNavbar.prototype._mousemoveCallback = (e) ->
  e.preventDefault() if @_isMousePressed

###
 * Mouseup callback.
 *
 * @param  {Event} e
###
PixelAdmin.MainNavbar.prototype._mouseupCallback = (e) ->
  if @_isMousePressed
    @_isMousePressed = false
    e.preventDefault()

###
 * Update height of the collapse container and refresh scroller.
###
PixelAdmin.MainNavbar.prototype._updateCollapseHeight = ->
  return if @_scroller is null
  w_height = $(window).innerHeight()
  h_height = @$header.outerHeight()
  # If current height of the navbar is bigger than height of the window 
  if (h_height + @$collapse_div.outerHeight()) > w_height
    # Set new navbar height
    @$collapse.css('height', w_height - h_height)
    if @_scroller isnt true
      @_scroller.refresh()
    else
      @$collapse.css('overflow', 'scroll')
  else
    # Reset navbar height
    @$collapse.attr('style', '')
    @_scroller.refresh() if @_scroller isnt true

###
 * Detecting a change of the window height.
###
PixelAdmin.MainNavbar.prototype._watchWindowHeight = ->
  @_wheight = $(window).innerHeight()
  checkWindowInnerHeight = =>
    return if @_wheight is null
    @_updateCollapseHeight() if @_wheight != $(window).innerHeight()
    @_wheight = $(window).innerHeight()
    setTimeout(checkWindowInnerHeight, 100)
  window.setTimeout(checkWindowInnerHeight, 100)


PixelAdmin.MainNavbar.Constructor = PixelAdmin.MainNavbar
PixelAdmin.addInitializer ->
  # Initialize plugin.
  PixelAdmin.initPlugin 'main_navbar', new PixelAdmin.MainNavbar