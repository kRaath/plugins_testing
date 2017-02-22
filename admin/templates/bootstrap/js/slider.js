$(document).ready(function() {
    var anchor = $('#slides');
    anchor.sortable({
        revert: true,
        placeholder: 'placeholder',
        items: 'li.slide:not(.append)',
        stop: function(event, ui) {
            $.map($(this).find('li.slide:not(.append)'), function(element) {
                var id = $(element).attr('id'),
                    sort = $(element).index(),
                    callback;
                ui.item.css('top','0px');
                $('li#'+ id +' input[type=hidden][name=nSort]').val(sort);
                $('li#'+id+' h4 span.number').html(sort);
                
                callback = xajax.callback.create();
                callback.onComplete = function(obj) {
                };
                xajax.call('saveSlide', {
                    parameters: [xajax.getFormValues('slide'+id)], 
                    callback: callback, 
                    context: this
                } );
            });
        }
    });

    anchor.delegate('li.slide:not(.append)','click', bindOverlay);

    anchor.delegate('li.slide form button.slide_delete','click', function() {
        var callback,
            params;
        if(confirm('Wollen Sie den Slide wirklisch löschen?')) {
            $(this).closest('li.slide').children('div.overlay_edit').children('div').children('form').children('input[name=delete]').val(1);
            callback = xajax.callback.create();
            callback.onComplete = function(obj) {
                var slide = obj.context.slide;
                $('li#'+slide.kSlide).remove();
                changeScrollPane(calcScrollPane());
            };
            
            params = xajax.getFormValues('slide'+ $(this).closest('li.slide').attr('id'));
            
            xajax.call('deleteSlide', {
                parameters: [params.kSlider,params.kSlide], 
                callback: callback, 
                context: this
            });
        }
    });

    anchor.delegate('li.slide form button.cancel','click',function() {
        var id = $(this).closest('li.slide').attr('id');
        $('form#slide'+id).get(0).reset();
        hideOverlay(id);
        hideOverlayEdit(id);
    });

    anchor.delegate('li.slide form button[name=save]','click',function() {
        var id = $(this).closest('li.slide').attr('id'),
            callback;
        $('li#'+id+' p.ajax_preloader').fadeIn('slow');
        callback = xajax.callback.create();
        callback.onComplete = function(obj) {
            var slide = obj.context.slide,
                timestamp = new Date().getTime(),
                img;
            if(slide == undefined) {
                $('li#'+id+' div.alert-error').fadeIn().delay(3000).fadeOut('fast');
                $('li#'+id+' p.ajax_preloader').hide();
            } else {
                img = $('li#'+id+' img');
                img.attr('src',slide.cBildAbsolut);
                img.attr('reload',timestamp);
                img.addClass('slide-image');
                $('li#'+id+' span.number').html(slide.nSort);
                $('li#'+id+' h4').html(slide.cTitel);
                $('li#'+id+' p:not(.ajax_preloader)').html(slide.cText);
        
                $('form#slide'+id+' input[name=nSort]').val(slide.nSort);
                $('form#slide'+id+' input[name=cTitel]').val(slide.cTitel);
                $('form#slide'+id+' input[name=cText]').val(slide.Text);
                hideOverlay(id);
                hideOverlayEdit(id);
                $('li#'+id+' p.ajax_preloader').hide();
            }

        };
        xajax.call('saveSlide', {
            parameters: [xajax.getFormValues('slide'+id)], 
            callback: callback, 
            context: this
        } );

    });
    
    anchor.delegate('li.slide form .select_image','click',function() {
        var id = $(this).closest('li.slide').attr('id'),
            shop_url = $('.shop_url').html(),
            kcfinder_path = $('.kcfinder_path').html();
        
        window.KCFinder = {
            callBack: function(url) {
                $('li#'+id+' input[name=cBild]').val(url);
                $('li#'+id+' div.alert-success').fadeIn().delay(3000).fadeOut('fast');
                kcFinder.close();
            }
        };
        var kcFinder = window.open(kcfinder_path+'browse.php?type=Bilder&lang=de', 'kcfinder_textbox',
            'status=0, toolbar=0, location=0, menubar=0, directories=0, ' +
            'resizable=1, scrollbars=0, width=800, height=600,'
            ); 
    });
    
    $('#nAnimationSpeed, #nPauseTime').change(function() {
        var nAnimationSpeed = parseInt($('#nAnimationSpeed').val()),
            nPauseTime = parseInt($('#nPauseTime').val());
        if(nAnimationSpeed > nPauseTime) {
            $('#nAnimationSpeedWarning').show();
            $('#nAnimationSpeed').addClass('nAnimationSpeedWarningBorder');
        } else {
            if($('#nAnimationSpeed').hasClass('nAnimationSpeedWarningBorder')) {
                $('#nAnimationSpeedWarning').hide(); 
                $('#nAnimationSpeed').removeClass('nAnimationSpeedWarningBorder');
            }
        }
    });
    
    $('#append_slide').click(function() {
        if($('form#slide0 input[name=cBild]').val() != '') {
            $('li#0 p.ajax_preloader').show();
            var callback = xajax.callback.create(),
                slide;
            callback.onComplete = function(obj) {
                $('form#slide0 input[name=cTitel]').val('');
                $('form#slide0 textarea').val('');
                $('form#slide0 input[name=cBild]').val('');
                $('form#slide0 input[name=cLink]').val('');
            
                slide = obj.context.slide;
                $('li#0 p.ajax_preloader').hide();
                if(slide == undefined) {
                    $('li#0 div.alert-error').fadeIn().delay(3000).fadeOut('fast');
                } else {
                    addSlide(slide);   
                }
            };
            
            xajax.call('saveSlide', {
                parameters: [xajax.getFormValues('slide'+ $(this).closest('li.slide').attr('id'))], 
                callback: callback, 
                context: this
            });
        } else {
            alert('Sie müssen ein Bild für den neuen Slide auswählen bevor Sie diesen hinzufügen können!');
        }
    });
    
    $('.random_effects').click(function() {
        if($('#cRandomEffects').prop('checked')){
            $('select[name=cSelectedEffects]').attr('disabled',true);
            $('select[name=cAvaibleEffects]').attr('disabled',true);
            $('button.select_add').attr('disabled',true);
            $('button.select_remove').attr('disabled',true);
            $('input[type=hidden][name=cEffects]').attr('disabled',true);
            $('select[name=cSelectedEffects]').html('');
        } else {
            $('select[name=cSelectedEffects]').removeAttr('disabled');
            $('select[name=cAvaibleEffects]').removeAttr('disabled');
            $('button.select_add').removeAttr('disabled');
            $('button.select_remove').removeAttr('disabled');
            $('input[type=hidden][name=cEffects]').removeAttr('disabled');
        }
    });
    
    $('form#slider').submit(function() {
        if( $('.random_effects').prop('checked') !== true){
            var effects = new Array();
            $.each($('select[name=cSelectedEffects] option'), function(index,value) {
                effects[index] = $(this).val();
            });
            $('input[name=cEffects]').val(effects.join(';'));
        }
    });
    
    $('button.select_add').click(function() {
        $.each($('select[name=cAvaibleEffects]').val(), function(index,value) {
            var exists = false,
                html;
            $.each($('select[name=cSelectedEffects] option'), function(element) {
                if($(this).val() == value) {
                    exists = true;
                }
            });
            
            if(exists == false) {
                html = '<option value="'+value+'">'+value+'</option>';
                $('select[name=cSelectedEffects]').append(html);
            } else {
                alert('Der Eintrag mit den Wert "'+value+'" existiert bereits!');
            }
        });
    });
    
    $('button.select_remove').click(function() {
        $.each($('select[name=cSelectedEffects] option:selected'), function(index,value) {
            $(this).remove();
        });
    });

    $("select[name='nSeitenTyp']").change(function () {
        var selected = $("select[name='nSeitenTyp'] option:selected");
        typeChanged($(selected).val());
    }).change();
     
    $("select[name='cKey']").change(function () {
        var selected = $("select[name='cKey'] option:selected");
        keyChanged($(selected).val());
    }).change();
     
    $('.nl').find('a').each(function() {
        var type = $(this).attr('id');
        $(this).click(function() {
            show_simple_search(type);
        });
    });
     
    init_simple_search(function(type, res) {
        $(".nl input[name='" + type + "_key']").val(res.kKey);
        $(".nl input[name='" + type + "_name']").val(res.cName);
    });

});

function typeChanged(type) {
    $('.custom').hide();
    $('#type' + type).show();

    if (type != 2) {
        $('select[name="cKey"]').val('');
        $('.nl .key').hide();
        $('.nl input[type="text"], .nl input[type="hidden"]').each(function() {
            $(this).val('');
        });
    }
}

function keyChanged(key) {
    $('.key[id!="key'+key+'"]').find('input').each(function() {
        $(this).val('');
    });

    $('.key').hide();
    $('#key' + key).show();
}

function hideOverlayEdit(id) {
    $('li#'+id).find('div.overlay_edit').fadeOut('fast');
}

function hideOverlay(id) {
    var elem = $('li#'+id);
    elem.removeClass('active').addClass('inactive');
    elem.find('div.overlay').fadeOut('fast');
    hideOverlayEdit(id);
}

//
$(function() {
    $("#tableSlide tbody ").sortable({
        containerSelector: 'table',
        itemPath: '> tbody',
        itemSelector: 'tr',
        opacity : '0',
        axis : "y",
        cursor: "move",
        cursorAt : {top: 5},
        stop : function(item) { 
            sortSlide();
        }
    });
});

function select_image( key ) {
    var id = key,
        shop_url = $('.shop_url').html(),
        kcfinder_path = $('.kcfinder_path').html();
    
    window.KCFinder = {
        callBack: function(url) {
            $('#img'+id).attr('src', url);
            $('input[name="aSlide\['+id+'\]\[cBild\]"]').val(url);
            kcFinder.close();
        }
    };
    var kcFinder = window.open(kcfinder_path+'browse.php?type=Bilder&lang=de', 'kcfinder_textbox','status=0, toolbar=0, location=0, menubar=0, directories=0, resizable=1, scrollbars=0, width=800, height=600,');
}

var count = 0;
function addSlide(slide) {
    var new_slide = $('#newSlide').html();
    new_slide = new_slide.replace(/NEU/g, "neu"+count);
    $('#tableSlide tbody').append( new_slide );
    count++;
    sortSlide();
}
    
function sortSlide() {
    $("input[name*='\[nSort\]']").each(function(index) {
    $(this).val(index+1);
    });
}
//

function bindOverlay() {
    if($(this).find('div.overlay_edit').css('display') === 'none') {
        $(this).find('div.overlay').fadeIn('fast');
        $(this).find('div.overlay_edit').fadeIn('fast');
    }  
}