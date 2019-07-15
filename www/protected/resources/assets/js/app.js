'use strict';

var myappUtil = {
    lodash: _
};

jQuery(document).ready(function(){
    $.fn.dataTable.ext.errMode = 'throw';
});

myappUtil.isInIframe = function() {
    try {
        return window.self !== window.top;
    } catch (e) {
        return true;
    }
}

myappUtil.dataTableDoRowDelete = function (dataTable, options) {
    // ensure options is an object
    if (typeof (options) !== 'object' || options === null) {
        options = {};
    }

    if(confirm(myappUtil.determineOption(options['confirmMessage'], 'The record will be deleted. Are you sure?'))) {
        jQuery.ajax({
            headers: {
                'X-CSRF-TOKEN' : jQuery('meta[name="csrf-token"]').attr('content')
            },
            url: options.url,
            method: 'DELETE',
            success: function (response, textStatus, jqXHR ) {
                // console.log(response);
                if(response['#status'] === 'success') {
                    myappUtil.showAlert({ status : 'success', message : response['#message'], timeout: 5000});
                    dataTable.ajax.reload(null, false);
                } else {
                    myappUtil.showAlert({ status : 'warning', message : response['#message']});
                }
            },
            error: function(jqXHR, textStatus, errorThrown ) {
                console.error(errorThrown);
                console.error(textStatus);
                myappUtil.showAlert({ status : 'danger', message : '['+errorThrown+'] ' + textStatus});
            }
        });
    }
    return false;
};

myappUtil.showAlert = function (options) {
    if (typeof (options) === 'object' || options !== null) {

        // default values
        if (!options.target) {
            options.target = {
                selector: '.page-header:first-child', // element select
                position: 'after' // before, after, prepend, append
            }
        }

        if (!options.status) {
            options.status = 'warning';
        }

        var $alertHtml = jQuery('<div class="alert alert-' + options.status + ' alert-dismissible" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>'
          + options.message
          + '</div>');

        var $target = jQuery(options.target.selector);

        if ($target) {
            switch (options.target.position) {
                case 'before':
                    $target.before($alertHtml);
                    break;
                case 'after':
                    $target.after($alertHtml);
                    break;
                case 'prepend':
                    $target.prepend($alertHtml);
                    break;
                case 'append':
                    $target.append($alertHtml);
                    break;
            }
        }

        if (options.timeout > 0) {
            setTimeout(function() {
                $alertHtml.fadeOut( "slow", function() {
                    jQuery(this).remove();
                });
            }, options.timeout);
        }
    }
}

// helper to get option setting or default setting
myappUtil.determineOption = function(option, defaultValue) {
    var expectedType = typeof (defaultValue),
        result = defaultValue;

    if (option && typeof (option) === expectedType) {
        result = option;
    }

    return result;
};

myappUtil.formatUnixTimestamp = function (ts) {
    var result = '&nbsp;';
    ts = parseInt(ts);
    if (ts > 0) {
        var date = new Date(ts*1000),
        y = date.getFullYear(),
        m = date.getMonth()+1,
        d = date.getDate();

        result = (d > 9 ? d : '0'+d) + '/' + (m > 9 ? m : '0' +m) + '/' + y;
    }

    return result;
};

myappUtil.generateQueryString = function (data) {
    var result;

    result = jQuery.param(data);

    return result;
}

// generate absolute url
myappUtil.generateUrl = function (uri) {
    var result;

    result = myappProperties.baseUrl + '/' + uri;

    return result;
}

// load tooltip content
myappUtil.loadTooltip = function (selector) {
    var $elem = $(selector);
    var pageId = $elem.data('pageid');
    var tooltipId = $elem.data('tooltipid');
    var $content = $('<div class="app-tooltip-content"></div>');
    var $title = $('<div class="title"></div>');
    var $description = $('<div class="description"></div>');
    var $editable = $('<a class="editable" href="' + myappUtil.generateUrl('tooltip/view/' + pageId + '/' + tooltipId) + '" target="_blank">Go to update the tooltip</a>');
    
    $.ajax({
        url: myappUtil.generateUrl('/ajax/tooltip/' + pageId + '/' + tooltipId),
        success: function(data, textStatus, jqXHR) {
            $elem.append($content);
            $content.append($title).append($description);
            if (myappProperties.isAdmin) {
                $content.append($editable);
            }
            
            $title.append(data.title);
            $description.append(data.description);
            
            $elem.addClass('loaded');
        },
        error: function (jqXHR, textStatus, errorThrown) {
            $elem.append($content);
            $content.append($title).append($description);

            switch (jqXHR.status) {
                case 404:
                    $title.append('Tooltip undefined');
                    if (myappProperties.isAdmin) {
                        $description.append('<a href="' + myappUtil.generateUrl('tooltip/view/' + pageId + '/' + tooltipId) + '" target="_blank">Go to add the tooltip</a>');
                    }
                    $elem.addClass('loaded');
                    break;
                default:
                    console.error(jqXHR);
                    console.error(textStatus + ':' + errorThrown);
                    break;
            }
        }
    });
}

// handle file form control for on file input changed
myappUtil.handleFileInputChange = function (fileInput) {
    console.error(fileInput.files[0].name);
}