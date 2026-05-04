/**
 * Controlador Principal
 *
 * Copyright   (c) 2019 Kevin Giovanni Enriquez Cordovez
 *  
 * @author     GEO INFORMATIC SOLUTIONS SAS
 * @author     Kevin Giovanni Enriquez Cordovez - kevin.g.enriquez.c@gmail.com
 * @version    0.0.1
 * @LastUpdate 2018-02-13
 */
var GLOBAL = GLOBAL || {};
GLOBAL.empresaId = null;
GLOBAL.folderId = null;
GLOBAL.folderPath = 'Debe seleccionar un destino';
GLOBAL.resultId = null;
GLOBAL.resultPath = 'Debe seleccionar un destino';
GLOBAL.campoBenford = null;
GLOBAL.archivoId = 0;
GLOBAL.exploradorId = 0;
GLOBAL.archivoTipo = null;
GLOBAL.archivoFormato = null;
GLOBAL.archivoNombre = null;
GLOBAL.pdfBenford = null;
GLOBAL.pdfSpider = null;
GLOBAL.pdfManipulacion = null;
GLOBAL.pdfConfianza = null;
GLOBAL.pdflistasControl = null;
GLOBAL.pdfcondicionCuenta = null;
GLOBAL.pdfDictamen = null;
GLOBAL.pdfMaterialidad = null;
GLOBAL.table = null;
GLOBAL.computed = {
    removeItemFromArr: function ( arr, item ) {
        return arr.filter( e => e !== item );
    },
    valuesSelect: function (idSelect) {
        var mySelections = [];
        $('#' + idSelect + ' option').each(function(i) {
                if (this.selected == true) {
                        mySelections.push(this.value);
                }
        });        
	return mySelections;        
    },
    isset: function () {
        var a = arguments
        var l = a.length
        var i = 0
        var undef

        if (l === 0) {
            throw new Error('Empty isset')
        }
        while (i !== l) {
            if (a[i] === undef || a[i] === null) {
                return false
            }
            i++
        }
        return true
    },    
    selectNode: function() {
        var ref = $('#carpetasTree').jstree(true), sel = ref.get_selected();
        //if(!sel.length) { return false; }
        var jsn = sel.length, j = 0;
        act = sel[(jsn - 1)];
        $('#explorador a[href="#tabexplorador"]').tab('show');
        if(act != GLOBAL.exploradorId){
            GLOBAL.computed.closeall().then(function () {
                $('#carpetasTree').jstree('select_node', GLOBAL.exploradorId);
            }).then(function () {
                if(sel.length > 0){
                    $.each(sel, function (key, value) {
                        if(j < jsn){
                            $('#carpetasTree').jstree('deselect_node', value);
                        }
                        j++;
                    });
                }                
            });
        }
        return false;
    },
    selectNodeId: function(id) {
        var ref = $('#carpetasTree').jstree(true), sel = ref.get_selected();
        //if(!sel.length) { return false; }
        var jsn = sel.length, j = 0;
        act = sel[(jsn - 1)];
        $('#explorador a[href="#tabexplorador"]').tab('show');
        GLOBAL.computed.restaurar();
        if(act != id){
            GLOBAL.computed.closeall().then(function () {
                $('#carpetasTree').jstree('select_node', id);
            }).then(function () {
                if(sel.length > 0){
                    $.each(sel, function (key, value) {
                        if(j < jsn){
                            $('#carpetasTree').jstree('deselect_node', value);
                        }
                        j++;
                    });
                }                
            });
        }
        return false;
    },
    closeall: function (){
    var deferred = $.Deferred();
    $("#carpetasTree").jstree('close_all');
    deferred.resolve();
    return deferred.promise();        
    },
    unserialize: function (data) {
      var $global = (typeof window !== 'undefined' ? window : global)
      var utf8Overhead = function (str) {
        var s = str.length
        for (var i = str.length - 1; i >= 0; i--) {
          var code = str.charCodeAt(i)
          if (code > 0x7f && code <= 0x7ff) {
            s++
          } else if (code > 0x7ff && code <= 0xffff) {
            s += 2
          }
          // trail surrogate
          if (code >= 0xDC00 && code <= 0xDFFF) {
            i--
          }
        }
        return s - 1
      }
      var error = function (type,
        msg, filename, line) {
        throw new $global[type](msg, filename, line)
      }
      var readUntil = function (data, offset, stopchr) {
        var i = 2
        var buf = []
        var chr = data.slice(offset, offset + 1)

        while (chr !== stopchr) {
          if ((i + offset) > data.length) {
            error('Error', 'Invalid')
          }
          buf.push(chr)
          chr = data.slice(offset + (i - 1), offset + i)
          i += 1
        }
        return [buf.length, buf.join('')]
      }
      var readChrs = function (data, offset, length) {
        var i, chr, buf

        buf = []
        for (i = 0; i < length; i++) {
          chr = data.slice(offset + (i - 1), offset + i)
          buf.push(chr)
          length -= utf8Overhead(chr)
        }
        return [buf.length, buf.join('')]
      }
      function _unserialize (data, offset) {
        var dtype
        var dataoffset
        var keyandchrs
        var keys
        var contig
        var length
        var array
        var readdata
        var readData
        var ccount
        var stringlength
        var i
        var key
        var kprops
        var kchrs
        var vprops
        var vchrs
        var value
        var chrs = 0
        var typeconvert = function (x) {
          return x
        }

        if (!offset) {
          offset = 0
        }
        dtype = (data.slice(offset, offset + 1)).toLowerCase()

        dataoffset = offset + 2

        switch (dtype) {
          case 'i':
            typeconvert = function (x) {
              return parseInt(x, 10)
            }
            readData = readUntil(data, dataoffset, ';')
            chrs = readData[0]
            readdata = readData[1]
            dataoffset += chrs + 1
            break
          case 'b':
            typeconvert = function (x) {
              return parseInt(x, 10) !== 0
            }
            readData = readUntil(data, dataoffset, ';')
            chrs = readData[0]
            readdata = readData[1]
            dataoffset += chrs + 1
            break
          case 'd':
            typeconvert = function (x) {
              return parseFloat(x)
            }
            readData = readUntil(data, dataoffset, ';')
            chrs = readData[0]
            readdata = readData[1]
            dataoffset += chrs + 1
            break
          case 'n':
            readdata = null
            break
          case 's':
            ccount = readUntil(data, dataoffset, ':')
            chrs = ccount[0]
            stringlength = ccount[1]
            dataoffset += chrs + 2

            readData = readChrs(data, dataoffset + 1, parseInt(stringlength, 10))
            chrs = readData[0]
            readdata = readData[1]
            dataoffset += chrs + 2
            if (chrs !== parseInt(stringlength, 10) && chrs !== readdata.length) {
              error('SyntaxError', 'String length mismatch')
            }
            break
          case 'a':
            readdata = {}

            keyandchrs = readUntil(data, dataoffset, ':')
            chrs = keyandchrs[0]
            keys = keyandchrs[1]
            dataoffset += chrs + 2

            length = parseInt(keys, 10)
            contig = true

            for (i = 0; i < length; i++) {
              kprops = _unserialize(data, dataoffset)
              kchrs = kprops[1]
              key = kprops[2]
              dataoffset += kchrs

              vprops = _unserialize(data, dataoffset)
              vchrs = vprops[1]
              value = vprops[2]
              dataoffset += vchrs

              if (key !== i) {
                contig = false
              }

              readdata[key] = value
            }

            if (contig) {
              array = new Array(length)
              for (i = 0; i < length; i++) {
                array[i] = readdata[i]
              }
              readdata = array
            }

            dataoffset += 1
            break
          default:
            error('SyntaxError', 'Unknown / Unhandled data type(s): ' + dtype)
            break
        }
        return [dtype, dataoffset - offset, typeconvert(readdata)]
      }

      return _unserialize((data + ''), 0)[2]
    },
    basename: function(path, suffix) {
        var b = path
        var lastChar = b.charAt(b.length - 1)
        if (lastChar === '/' || lastChar === '\\') {
            b = b.slice(0, -1)
        }
        b = b.replace(/^.*[/\\]/g, '')
        if (typeof suffix === 'string' && b.substr(b.length - suffix.length) === suffix) {
            b = b.substr(0, b.length - suffix.length)
        }
        return b
    },    
    AjaxDownloader: function(options) {
        var settings = $.extend(true, {}, {
            data    : $.ajaxSetup()["data"] || {},
            url     : $.ajaxSetup()["url"]
        }, options);
        var form = $("<form>", {
            action  : settings.url,
            method  : "GET",
            target  : "AjaxDownloaderIFrame",
        }).appendTo("body");
        $.each(settings.data, function(key, val){
            $("<input>", {
                type    : "hidden",
                name    : key,
                value   : (typeof val == "object") ? JSON.stringify(val) : val
            }).appendTo(form);
        });
        form.submit();
        form.remove();        
    },
    initializeModal: function(modal, bodyClear = true, size = false) {
        if(size){
            modal.find('div.modal-dialog').removeClass(['modal-sm', 'modal-xl', 'modal-lg']).addClass(size);
        }else{
            modal.find('div.modal-dialog').removeClass(['modal-sm', 'modal-xl']).addClass('modal-lg');
        }
        modal.find('.btn-primary').show();
        modal.find('.btn-link').show();
        modal.find('.btn-primary').prop('disabled', false);
        modal.find('.btn-link').prop('disabled', false);
        modal.find('.btn-primary').attr('onclick','');
        modal.find('.btn-link').attr('onclick','');
        modal.find('#btn-extra').empty();
        modal.find('#btn-extra').hide();
        modal.find('#btn-extra1').empty();
        modal.find('#btn-extra1').hide();
        if(bodyClear == true){
            modal.find('.modal-body').html('<div class="text-center">Procesando...</div>');
        }
    },
    initialize: function() {
        GLOBAL.folderId = null;
        GLOBAL.folderPath = 'Debe seleccionar un destino';
        GLOBAL.resultId = null;
        GLOBAL.resultPath = 'Debe seleccionar un destino';
        GLOBAL.campoBenford = null;
        GLOBAL.archivoId = 0;
        GLOBAL.archivoTipo = null;
        GLOBAL.archivoNombre = null;
        GLOBAL.exploradorId = 0;
    },
    initializePdf: function(tipo) {
        let e = '';
        for (const property in Object.entries(GLOBAL)) {
            e = Object.entries(GLOBAL)[property][0];
            if(e.indexOf('pdf') == 0){
                if('pdf' + tipo.toLowerCase() != e.toLowerCase()) eval("GLOBAL." + e + " = null");
            }
        }
    },
    validaFileName: function(fileUpload, allowedFiles = null) {
        if(allowedFiles == null){
            var allowedFiles = [".doc", ".docx", ".pdf"];
        }
        var regex = new RegExp("([a-zA-Z0-9\s_\\.\-:])+(" + allowedFiles.join('|') + ")$");
        if (!regex.test(fileUpload.toLowerCase())) {
            return false;
        }
        return true;                
    },
    isNull: function(obj) {
        return obj == null || obj <= 0 || obj == '';
    },
    is_numeric: function (mixedVar) { // eslint-disable-line camelcase
        var whitespace = [
          ' ',
          '\n',
          '\r',
          '\t',
          '\f',
          '\x0b',
          '\xa0',
          '\u2000',
          '\u2001',
          '\u2002',
          '\u2003',
          '\u2004',
          '\u2005',
          '\u2006',
          '\u2007',
          '\u2008',
          '\u2009',
          '\u200a',
          '\u200b',
          '\u2028',
          '\u2029',
          '\u3000'
        ].join('')

        // @todo: Break this up using many single conditions with early returns
        return (typeof mixedVar === 'number' ||
          (typeof mixedVar === 'string' &&
          whitespace.indexOf(mixedVar.slice(-1)) === -1)) &&
          mixedVar !== '' &&
          !isNaN(mixedVar)
    },    
    array_key_exists: function(key, array) {
        if (!array || (array.constructor !== Array && array.constructor !== Object)) {
            return false
        }
        return key in array
    },
    uniqint: function () {
        var hexString = new Date().getTime();
        var hexInt = (hexString + '').replace(/[^a-f0-9]/gi, '');
        return parseInt(hexInt, 16);
    },    
    uniqid: function () {
        var ts=String(new Date().getTime()), i = 0, out = '';
        for(i=0;i<ts.length;i+=2) {        
           out+=Number(ts.substr(i, 2)).toString(36);    
        }
        return ('d'+out);
    },    
    secure: function() {
        $.ajaxSetup({data: {'A4d6ebb02e86d4': Cookies.get('A4d6ebb02e86d4')}});
    },
    ventana: function () {

    },
    number_format: function(number, decimals, dec_point, thousands_sep) {
        number = (number + '').replace(/[^0-9+\-Ee.]/g, '');
        var n = !isFinite(+number) ? 0 : +number,
            prec = !isFinite(+decimals) ? 0 : Math.abs(decimals),
            sep = (typeof thousands_sep === 'undefined') ? '.' : thousands_sep,
            dec = (typeof dec_point === 'undefined') ? ',' : dec_point,
            s = '',
            toFixedFix = function (n, prec) {
                var k = Math.pow(10, prec);
                return '' + Math.round(n * k) / k;
            };
        s = (prec ? toFixedFix(n, prec) : '' + Math.round(n)).split('.');
        if (s[0].length > 3) {
            s[0] = s[0].replace(/\B(?=(?:\d{3})+(?!\d))/g, sep);
        }
        if ((s[1] || '').length < prec) {
            s[1] = s[1] || '';
            s[1] += new Array(prec - s[1].length + 1).join('0');
        }
        return s.join(dec);
    },
    dtable: function() {
        if(GLOBAL.table){
            GLOBAL.table.columns.adjust().draw();
        }
    },
    billboard: function () {
        if(GLOBAL.computed.array_key_exists(1,BENFORD.chart)){
            BENFORD.chart[1].resize();
        }
        if(GLOBAL.computed.array_key_exists(2,BENFORD.chart)){
            BENFORD.chart[2].resize();
        }
        if(GLOBAL.computed.array_key_exists(12,BENFORD.chart)){
            BENFORD.chart[12].resize();
        }
    },
    spider: function() {
        var outerContent = $('#content').find('.scrollTable');
        var innerContent = $('#content').find('.scrollTable #body-spider');
        if(GLOBAL.computed.isset(innerContent)){
            outerContent.scrollLeft( (innerContent.width() - outerContent.width()) / 2);
            outerContent.scrollTop( (innerContent.height() - outerContent.height()) / 2);            
        }
    },
    maximizar: function (callback) {
        $('#content').addClass('position-absolute maximizar');
        $('#content').find('#maximizar').hide();
        $('#content').find('#restaurar').show();
        if(typeof callback === 'function'){
            callback();
        }
    },
    restaurar: function (callback) {
        $('#content').removeClass('position-relative maximizar');
        $('#content').find('#restaurar').hide();
        $('#content').find('#maximizar').show();
        if(typeof callback === 'function'){
            callback();
        }
    },
    isValid: function (fname) {
        var rg1 = /[^A-Za-z0-9\s-_]+/;
        var rg2 = /^(nul|prn|con|pdf|lpt[0-9]|com[0-9])(\.|$)/i; // forbidden file names
        return !rg1.test(fname) && !rg2.test(fname);
    },
    toast: function(mensaje, status = 200, heading = 'default') {
        var bgColor = '';
        if(status == 500){
            bgColor = '#e53935';
        }else{
            switch (heading) {
                case 'info':
                    bgColor = '#1e88e5';
                    break;
                case 'success':
                    bgColor = '#43a047';
                    break;
                case 'error':
                    bgColor = '#e53935';
                    break;
                default:
                    bgColor = '#444';
                    break;
            }            
        }        
        $.toast({
            text: mensaje,
            position: 'botton-left',
            stack: 3,
            hideAfter: ((status == 200) ? 8000 : 8000),
            showHideTransition: 'fade',
            allowToastClose: true,
            loader: false,
            bgColor: bgColor,
        });        
    }
}
GLOBAL.componets = {
    ventanaModal: function () {
        $('body').append(``);
    },
    procesando:function(e = 'show') {
        if(e == 'show'){
            $('#notificacion').removeClass('d-none').addClass('d-block');
        }else{
            $('#notificacion').removeClass('d-block').addClass('d-none');
        }
    },
    loader: function() {
        $('#content').html(`
            <div class="loader">
                <div class="d-flex justify-content-center selec-empresa">
                    <div class="spinner-border" role="status">
                        <span class="sr-only">Loading...</span>
                    </div>
                </div>
            </div>`);
    },
    editarSummer: function(e) {
        $.ajaxSetup({async:false});
        $(e).summernote({
            height: (e.clientHeight),
            lang: 'es-ES',
            focus: true,
            shortcuts: false,
            styleTags: ['p', {style : 'line-height: 1'}],
            toolbar: [
                ['font', ['bold', 'underline', 'italic', 'strikethrough', 'clear']],
                ['para', ['ul', 'ol', 'paragraph']],
                ['table', ['table','undo','redo']],
                ['view', ['codeview']],
                ['custom1', ['salvar']],
                ['custom2', ['cerrar']]
            ],
            buttons: {
                salvar: function () {
                    var ui = $.summernote.ui;
                    var button = ui.button({
                        contents: '<i class="fas fa-save"/> Salvar',
                        tooltip: 'Salvar Párrafo',
                        click: function (event) {
                            $.when().then(function () {
                                $(e).summernote('destroy');
                            }).then(function () {
                                if(e.innerHTML == '<p><br></p>'){
                                    e.innerHTML = '';
                                }
                            });
                        }
                    });
                    return button.render();                    
                },
                cerrar: function () {
                    var ui = $.summernote.ui;
                    var button = ui.button({
                        contents: '<i class="fas fa-times"/> Cerrar',
                        tooltip: 'Cerrar',
                        click: function (event) {
                            $.when().then(function () {
                                $(e).summernote('reset');
                            }).then(function () {
                                $(e).summernote('destroy');
                            }).then(function () {
                                if(e.innerHTML == '<p><br></p>'){
                                    e.innerHTML = '';
                                }
                            });
                        }
                    });
                    return button.render();                    
                },
            },
            cleaner: {
                  action: 'both', // both|button|paste 'button' only cleans via toolbar button, 'paste' only clean when pasting content, both does both options.
                  newline: '<br/>', // Summernote's default is to use '<p><br></p>'
                  notStyle: 'position:absolute;top:0;left:0;right:0', // Position of Notification
                  keepHtml: false, // Remove all Html formats
                  keepOnlyTags: ['<span>', '<p>', '<br>', '<br/>', '<ul>', '<li>', '<b>', '<strong>','<i>', '<a>'], // If keepHtml is true, remove all tags except these
                  keepClasses: false, // Remove Classes
                  badTags: ['style', 'script', 'applet', 'embed', 'noframes', 'noscript', 'html'], // Remove full tags with contents
                  badAttributes: ['style', 'start'], // Remove attributes from remaining tags
                  limitChars: 2500, // 0/false|# 0/false disables option
                  limitDisplay: 'both', // text|html|both
                  limitStop: true // true/false
            }
        });
        $('div.summernote').each(function (k,x) {
            if(($(x).attr('name') != $(e).attr('name')) && ($(x).css('display') == 'none')){
                $.when().then(function () {
                    $(x).summernote('destroy');
                }).then(function () {
                    if(x.innerHTML == '<p><br></p>'){
                        x.innerHTML = '';
                    }
                });                
            }
        });
        $.ajaxSetup({async:true});
    }    
}
