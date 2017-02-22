/**
 * @copyright (c) JTL-Software-GmbH
 * @license http://jtl-url.de/jtlshoplicense
 */

(function () {
    'use strict';
    
    if (!$.evo) {
        $.evo = { 'showDebug' : false };
    }

    var EvoClass = function (options) {
        this.init(options);
    };

    EvoClass.VERSION = '1.0.0';

    EvoClass.DEFAULTS = { captcha: {} };

    EvoClass.prototype = {

        constructor: EvoClass,

        init: function (options) {
            this.options = $.extend({}, EvoClass.DEFAULTS, options);
        },

        /*
        generateEvoSlider: function () {
            var slideNum = 3,
                containerWidth,
                target,
                slideWidthPercent,
                cellCount,
                item,
                items;
            $('.evo-slider > .carousel-inner').each(function (index, el) {
                containerWidth = $(this).parent().width();
                target = $(this).parent().data('item-width');
                slideNum = 1;
                if (parseFloat(target) > 0) {
                    slideNum = Math.floor(containerWidth / target);
                }
                items = $(this).parent().data('item-count');
                if (parseInt(items) > 0) {
                    slideNum = items;
                }
                //cellWidth = (containerWidth / slideNum) - 1;
                slideWidthPercent = Math.floor(100 / slideNum);
                cellCount = 0;
                item = null;
                items = $(el).find('.product-wrapper');

                items.find('.product-cell').css('margin-bottom', 0);

                $(el).empty();

                items.each(function () {
                    if (cellCount === 0) {
                        item = $('<div class="item"></div>');
                    }

                    // $(this).css('width', cellWidth).appendTo(item);
                    $(this).css('width', slideWidthPercent + '%').appendTo(item);

                    cellCount++;

                    if (cellCount >= slideNum) {
                        $(el).append(item);
                        cellCount = 0;
                    }
                });

                $(el).append(item).children('.item').first().addClass('active');
                $(this).removeClass('hidden');
            });
            
            $('.evo-slider').carousel('cycle');
        },
        */

        generateSlickSlider: function() {
            /*
             * box product slider
             */

            $('.evo-box-slider:not(.slick-initialized)').slick({
                //dots: true,
                arrows: true,
                slidesToShow: 1
            });

            /*
             * responsive slider (content)
             */
            $('.evo-slider:not(.slick-initialized)').slick({
                //dots: true,
                arrows: true,
                slidesToShow: 3,
                responsive: [
                    {
                        breakpoint: 480, // xs
                        settings: {
                            slidesToShow: 1
                        }
                    },
                    {
                        breakpoint: 768, // sm
                        settings: {
                            slidesToShow: 2
                        }
                    },
                    {
                        breakpoint: 992, // md
                        settings: {
                            slidesToShow: 3
                        }
                    }
                ]
            });
        },

        addSliderTouchSupport: function () {
            $('.carousel').each(function () {
                if ($(this).find('.item').length > 1) {
                    $(this).find('.carousel-control').css('display', 'block');
                    $(this).swiperight(function () {
                        $(this).carousel('prev');
                    }).swipeleft(function () {
                        $(this).carousel('next');
                    });
                } else {
                    $(this).find('.carousel-control').css('display', 'none');
                }
            });
        },

        scrollStuff: function() {
            var breakpoint = 0,
                pos,
                sidePanel = $('#sidepanel_left');

            if(sidePanel.length) {
                breakpoint = sidePanel.position().top + sidePanel.hiddenDimension('height');
            }

            pos = breakpoint - $(this).scrollTop();

            if ($(this).scrollTop() > 200 && !$('#to-top').hasClass('active')) {
                $('#to-top').addClass('active');
            } else if($(this).scrollTop() < 200 && $('#to-top').hasClass('active')) {
                $('#to-top').removeClass('active');
            }

            if ($(window).width() > 768) {
                var $document = $(document),
                    $element = $('.navbar-fixed-top'),
                    className = 'nav-closed';

                $document.scroll(function() {
                    $element.toggleClass(className, $document.scrollTop() >= 150);
                });

            }
        },

        productTabs: function() {
            var tabAnchor = $('#article-tabs');
            if (tabAnchor && tabAnchor.hasClass('tab-content')) {
                var items = '<ul id="article-tabs-list" class="nav nav-tabs">';

                $('.panel-heading[data-parent="#article-tabs"]').each(function () {
                    var href = $(this).attr('data-target'),
                        aria = href.replace('#', ''),
                        title = $(this).text();
                    items += '<li role="presentation" class="' + aria + '-list"><a href="' + href + '" aria-controls="' + aria + '" role="tab" data-toggle="tab">' + title + '</a></li>';
                    $(this).remove();
                });

                $('#article-tabs.tab-content').before(items + '</ul>');

                $('#article-tabs-list li:first,#article-tabs.tab-content div:first').addClass('active');

                $('#article-tabs-list').on('click', 'a', function (e) {
                    e.preventDefault();
                    $(this).tab('show');
                    if ($(e.target).attr('aria-controls') === 'tab-preisverlauf' && typeof window.priceHistoryChart !== 'undefined' && window.priceHistoryChart === null) {
                        window.priceHistoryChart = new Chart(window.ctx).Bar(window.chartData, {
                            responsive:      true,
                            scaleBeginAtZero: false,
                            tooltipTemplate: "<%if (label){%><%=label%> - <%}%><%= parseFloat(value).toFixed(2).replace('.', ',') %> " + window.chartDataCurrency
                        });
                    }
                });

                if (window.location.hash) {
                    $('#article-tabs-list').find('a[href="' + window.location.hash + '"]').tab('show');
                }
            }
        },

        autoheight: function() {
            $('.row-eq-height').each(function(i, e) {
                $(e).find('[class*="col-"] > *').responsiveEqualHeightGrid();
            });
        },
        
        tooltips: function() {
            $('[data-toggle="tooltip"]').tooltip();
        },

        imagebox: function() {
            $('.image-box').each(function(i, item) {
                var box = $(this),
                    img = box.find('img'),
                    src = img.data('src');
                box.addClass('loading');
                    
                if (src && src.length > 0) {
                    //if (src === 'gfx/keinBild.gif') {
                    //    box.removeClass('loading')
                    //        .addClass('none');
                    //    box.parent().find('.overlay-img').remove();
                    //} else {
                        var padding = $(window).height() / 2,
                            square = box.width() + 'px';
                        $(img).lazy(padding, function() {
                            $(this).load(function() {
                                img.css('max-height', square);
                                box.css('line-height', square)
                                    .css('max-height', square)
                                    .removeClass('loading')
                                    .addClass('loaded');
                            }).error(function() {
                                box.removeClass('loading')
                                    .addClass('error');
                            });
                        });
                    //}
                }
            });
        },

        bootlint: function() {
            (function(){
                var p = window.alert;
                var s = document.createElement("script");
                window.alert = function() {
                    console.info(arguments);
                };
                s.onload = function() {
                    bootlint.showLintReportForCurrentDocument([]);
                    window.alert = p;
                };
                s.src = "https://maxcdn.bootstrapcdn.com/bootlint/latest/bootlint.min.js";
                document.body.appendChild(s);
            })();
        },
        
        showNotify: function(options) {
            eModal.alert({
                size: 'lg',
                buttons: false,
                title: options.title, 
                message: options.text,
                onShown: function() {
                    $.evo.extended().generateSlickSlider();
                }
            });
        },
        
        renderCaptcha: function(parameters) {
            if (typeof parameters != 'undefined') {
                this.options.captcha = 
                    $.extend({}, this.options.captcha, parameters);
            }

            if (typeof grecaptcha == 'undefined' && !this.options.captcha.loaded) {
                this.options.captcha.loaded = true;
                $.getScript("https://www.google.com/recaptcha/api.js?render=explicit&onload=g_recaptcha_callback");
            }
            else {
                $('.g-recaptcha').each(function(index, item) {
                    parameters = $.extend({}, $(item).data(), parameters);
                    try {
                        grecaptcha.render(item, parameters);
                    }
                    catch(e) { }
                });
            }
        },
        
        popupDep: function() {
            $('.popup-dep').click(function(e) {
                var id    = '#popup' + $(this).attr('id'),
                    title = $(this).attr('title'),
                    html  = $(id).html();
                eModal.alert({
                    message: html,
                    title: title,
                    onShown:function () {
                        //the modal just copies all the html.. so we got duplicate IDs which confuses recaptcha
                        var recaptcha = $('.tmp-modal-content .g-recaptcha');
                        if (recaptcha.length === 1) {
                            var siteKey = recaptcha.data('sitekey'),
                                newRecaptcha = $('<div />');
                            if (typeof  siteKey !== 'undefined') {
                                //create empty recapcha div, give it a unique id and delete the old one
                                newRecaptcha.attr('id', 'popup-recaptcha').addClass('g-recaptcha');
                                recaptcha.replaceWith(newRecaptcha);
                                grecaptcha.render('popup-recaptcha', {
                                    'sitekey' : siteKey
                                });
                            }
                        }
                    }
                });
                return false;
            });
        },

        preventDropdownToggle: function() {
            $('a.dropdown-toggle').click(function(e){
                var elem = e.target;
                if (elem.getAttribute('aria-expanded') == 'true' && elem.getAttribute('href') != '#') {
                    window.location.href = elem.getAttribute('href');
                    e.preventDefault();
                }
            });
        },

        register: function() {
            this.addSliderTouchSupport();
            this.productTabs();
            // this.generateEvoSlider();
            this.generateSlickSlider();
            $('.nav-pills, .nav-tabs').tabdrop();
            this.autoheight();
            this.tooltips();
            this.imagebox();
            this.renderCaptcha();
            this.popupDep();
            this.preventDropdownToggle();
        },
        
        loadContent: function(url, callback, error, animation) {
            var that = this;
            var $wrapper = $('#result-wrapper');
            if (animation) {
                $wrapper.addClass('loading');
            }

            $.ajax(url, {data: 'isAjax'}).done(function(html) {
                var $data = $(html);
                if (animation) {
                    $data.addClass('loading');
                }
                $wrapper.replaceWith($data);
                $wrapper = $data;
                if (typeof callback == 'function') {
                    callback();
                }
            })
            .fail(function() {
                if (typeof error == 'function') {
                    error();
                }
            })
            .always(function() {
                $wrapper.removeClass('loading');
                that.trigger('contentLoaded');
            });
        },
        
        spinner: function() {
            var opts = {
              lines: 12             // The number of lines to draw
            , length: 7             // The length of each line
            , width: 5              // The line thickness
            , radius: 10            // The radius of the inner circle
            , scale: 2.0            // Scales overall size of the spinner
            , corners: 1            // Roundness (0..1)
            , color: '#000'         // #rgb or #rrggbb
            , opacity: 1/4          // Opacity of the lines
            , rotate: 0             // Rotation offset
            , direction: 1          // 1: clockwise, -1: counterclockwise
            , speed: 1              // Rounds per second
            , trail: 100            // Afterglow percentage
            , fps: 20               // Frames per second when using setTimeout()
            , zIndex: 2e9           // Use a high z-index by default
            , className: 'spinner'  // CSS class to assign to the element
            , top: '50%'            // center vertically
            , left: '50%'           // center horizontally
            , shadow: false         // Whether to render a shadow
            , hwaccel: false        // Whether to use hardware acceleration (might be buggy)
            , position: 'absolute'  // Element positioning
            }
            var target = document.getElementsByClassName('product-offer')[0];
            var elem = new Spinner(opts).spin(target);
            return elem;
        },

        trigger: function(event, args) {
            $(document).trigger('evo:' + event, args);
        }
    };

    $(window).on('load', function () {
        $.evo.extended().register();
    });

    $(window).on('resize', function () {
        $.evo.extended().autoheight();
    });

    // PLUGIN DEFINITION
    // =================

    $.evo.extended = function(option) {
        return new EvoClass(option);
    };
    
    $.evo.error = function() {
        if (console && console.error) {
            console.error(arguments);
        }
    }

})(jQuery);

function g_recaptcha_callback() {
    $.evo.extended().renderCaptcha();
}