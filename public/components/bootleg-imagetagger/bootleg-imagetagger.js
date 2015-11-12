// the semi-colon before function invocation is a safety net against concatenated
// scripts and/or other plugins which may not be closed properly.
;(function ( $, window, document, undefined ) {

    "use strict";

        // undefined is used here as the undefined global variable in ECMAScript 3 is
        // mutable (ie. it can be changed by someone else). undefined isn't really being
        // passed in so we can ensure the value of it is truly undefined. In ES5, undefined
        // can no longer be modified.

        // window and document are passed through as local variable rather than global
        // as this (slightly) quickens the resolution process and can be more efficiently
        // minified (especially when both are regularly referenced in your plugin).

        // Create the defaults once
        var pluginName = "taggableImage";
        var defaults = {
                responsiveTags: true,
                admin: false,
                tags: [],
                onTagUpdate: function(){}
        };

        // The actual plugin constructor
        function Plugin ( element, options ) {
                var self = this;
                var wrapped = $(element).wrap( "<div class='taggable_image_outer'><div class='taggable_image_inner'></div></div>" );

                // jQuery has an extend method which merges the contents of two or
                // more objects, storing the result in the first object. The first object
                // is generally empty as we don't want to alter the default options for
                // future instances of the plugin
                this.settings = $.extend( {}, defaults, options );
                this._defaults = defaults;
                this._name = pluginName;

                //add interface to add/remove tags if admin is enabled
                if(this.settings.admin){
                    this.tag_editor = $('<div class="tag_editor" style="display:none"><table class="table"><tr><td style="width:60px;vertical-align: middle;"><b>URL</b></td><td><input class="form-control" type="text" value="" placeholder="Links to..."></td><td style="width: 181px;"><button type="button" class="btn btn-info image-tag-save"><i class="glyphicon glyphicon-floppy-save"></i> <span>Save</span></button> <button type="button" class="btn btn-danger image-tag-remove"><i class="glyphicon glyphicon-trash"></i> <span>Delete</span></button></td></tr></table></div>');
                    wrapped.parent().parent().append(this.tag_editor);
                }

                this.original = element;
                this.element = wrapped.parent().parent()[0];

                //import all tags passed during instantiation
                if(this.settings.tags){
                    $.each(this.settings.tags, function(index, tag) {
                        var tagElement = $('<a href="'+tag.link+'" class="marquee-tag" data-tagid="'+index+'"></a>').css({
                            'left': tag.left,
                            'top': tag.top,
                            'width':tag.width,
                            'height':tag.height
                        });
                        if(self.settings.admin){
                            tagElement.addClass('tag-preview');
                        }
                        $(self.element).find('.taggable_image_inner').append(tagElement);
                    });
                }
                this.init();
        }

        // Avoid Plugin.prototype conflicts
        $.extend(Plugin.prototype, {
                init: function () {
                    if(this.settings.admin){
                        $(this.element).on('mousedown', 'img', $.proxy(this._createTag, this));
                        $(this.element).on('click', '.marquee-tag', $.proxy(this._editTag, this));
                        $(this.tag_editor).on('click','.image-tag-remove',$.proxy(this._removeTag, this));
                        $(this.tag_editor).on('click','.image-tag-save',$.proxy(this._updateTag, this));
                    }
                },
                _createTag: function(e){
                    $(this.tag_editor).fadeOut();
                    $('.marquee-tag').removeClass('selected');
                    var relX = e.pageX - $(this.original).offset().left;
                    var relY = e.pageY - $(this.original).offset().top;

                    this._initialW = relX;
                    this._initialH = relY;

                    this._marquee = $('<a target="_blank" class="marquee-tag"></a>').css({
                        'left': this.settings.responsiveTags ? relX / this.original.width * 100 + '%' : relX,
                        'top': this.settings.responsiveTags ? relY / this.original.height * 100 + '%' : relY
                    });

                    if(this.settings.admin) this._marquee.addClass('tag-preview');
                    $(this.element).find('.taggable_image_inner').append(this._marquee);

                    $(document).bind("mouseup", $.proxy(this._completeTag, this));
                    $(document).bind("mousemove", $.proxy(this._sizeTag, this));
                    return false;
                },
                _removeTag: function(e){
                    $(this.tag_editor).fadeOut();
                    var tagid = $(e.currentTarget).attr('data-tagid');
                    $('.marquee-tag[data-tagid="'+tagid+'"').remove();
                    delete this.settings.tags[tagid];
                    this.settings.onTagUpdate.call(this);

                },
                _sizeTag: function(e){
                    var relX = e.pageX - $(this.original).offset().left;
                    var relY = e.pageY - $(this.original).offset().top;

                    var w = Math.abs(this._initialW - relX);
                    var h = Math.abs(this._initialH - relY);

                    $(this._marquee).css({
                        'width': this.settings.responsiveTags ? w / this.original.width * 100 + '%' : w,
                        'height': this.settings.responsiveTags ? h / this.original.height * 100 + '%' : h
                    });
                    if (relX <= this._initialW && relY >= this._initialH) {
                        $(this._marquee).css({
                            'left': this.settings.responsiveTags ? relX / this.original.width * 100 + '%' : relX
                        });
                    } else if (relY <= this._initialH && relX >= this._initialW) {
                        $(this._marquee).css({
                            'top': this.settings.responsiveTags ? relY / this.original.height * 100 + '%' : relY
                        });
                    } else if (relY < this._initialH && relX < this._initialW) {
                        $(this._marquee).css({
                            'left': this.settings.responsiveTags ? relX / this.original.width * 100 + '%' : relX,
                            "top": this.settings.responsiveTags ? relY / this.original.height * 100 + '%' : relY
                        });
                    }
                },
                _editTag: function(e){
                    e.preventDefault();

                    var elem = $(e.currentTarget);
                    var tagid = elem.attr('data-tagid');
                    var current_url = this.settings.tags[tagid].link;

                    $('.marquee-tag').removeClass('selected');
                    elem.addClass('selected');

                    $('button', this.tag_editor).attr('data-tagid', tagid);
                    $('input', this.tag_editor).val(current_url);
                    $(this.tag_editor).fadeIn();
                    $('input', this.tag_editor).focus();
                },
                _updateTag: function(e){
                    $(this.tag_editor).fadeOut();
                    $('.marquee-tag').removeClass('selected');

                    var tagid = $(e.currentTarget).attr('data-tagid');
                    this.settings.tags[tagid].link = $('input', this.tag_editor).val();
                    this.settings.onTagUpdate.call(this);
                },
                _completeTag: function(e){
                    var index = this.settings.tags.push({
                        left:this._marquee[0].style.left,
                        top:this._marquee[0].style.top,
                        width:this._marquee[0].style.width,
                        height:this._marquee[0].style.height,
                        link:'',
                    }) - 1;
                    $(document).unbind("mousemove", this._sizeTag);
                    $(document).unbind("mouseup", this._completeTag);

                    $(this._marquee).attr("data-tagid", index);
                    //$(this._marquee).bind("click", $.proxy(this._editTag, this));
                    $(this._marquee).trigger('click');
                    this.settings.onTagUpdate.call(this);
                },
                getTags: function(){
                    var arr = $.grep(this.settings.tags, function(n, i){
                      return (n !== "" && n != null);
                    });

                    return arr;
                },

                //
                // Free resources
                //
                destroy: function() {
                    $("img", this.element).unbind("mousedown", this._createTag);
                    $('.marquee-tag').unbind("click", this._editTag);
                    $(document).unbind("mousemove", this._sizeTag);
                    $(document).unbind("mousemove", this._sizeTag);
                    $(document).unbind("mouseup", this._completeTag);
                    $(this.original).removeData();
                    $(this.element).removeData();
                    $(this.element).replaceWith(this.original);
                }
        });

        // A really lightweight plugin wrapper around the constructor,
        // preventing against multiple instantiations
        $.fn[ pluginName ] = function ( options ) {

                // get the arguments
                var args = $.makeArray(arguments),
                    after = args.slice(1);

                return this.each(function() {
                        var instance = $.data(this, "plugin_" + pluginName);
                        if (instance) {
                            // call a method on the instance
                            if (typeof options == "string") {
                              return instance[options].apply(instance, after);
                            } else {
                            }
                        } else {
                            return $.data( this, "plugin_" + pluginName, new Plugin( this, options ) );
                        }
                });
        };
})( jQuery, window, document );