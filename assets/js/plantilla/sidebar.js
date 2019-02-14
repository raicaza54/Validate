$("#sidebar").mCustomScrollbar({
    theme: "minimal"
});
$('i,a,div').popover({trigger: 'hover', delay: 500});
$('#sidebarCollapse').on('click', function () {
    $('#sidebar').toggleClass('active');
});
$('#sidebarCollapse').on('click', function () {
    // open or close navbar
    $('#sidebar').toggleClass('active');
    // close dropdowns
    $('.collapse.in').toggleClass('in');
    // and also adjust aria-expanded attributes we use for the open/closed arrows
    // in our CSS
    $('a[aria-expanded=true]').attr('aria-expanded', 'false');
});
$("#PrinCrear").addClass('item-disabled');
$("#PrinPropiedades").addClass('');
$("#PrinListar").addClass('');
$("#PrinArchivo").addClass('');
$("#PrinConexion").addClass('item-disabled');
$("#PrinCSV").addClass('');
$("#PrinExcel").addClass('item-disabled');
$("#DatoDuplicar").addClass('item-disabled');
$("#DatoIndices").addClass('item-disabled');
$("#DatoOrdenar").addClass('item-disabled');
$("#DatoBuscar").addClass('');
$("#DatoSiguiente").addClass('');
$("#DatoIr").addClass('');
$("#AnalEjecutar").addClass('');
$("#AnalBenford").addClass('');
$("#AnalOmisiones").addClass('item-disabled');
$("#AnalDuplicados").addClass('item-disabled');
$("#AnalSpider").addClass('');


//    $('#simpleTree').jstree({
//        'core': {
//            'themes': {
//                'responsive': false,
//            },
//            'multiple': false
//        },
//        'types': {
//            'default': {
//                'icon': 'far fa-folder'
//            },
//            'file': {
//                'icon': 'far fa-file-excel'
//            }
//        },
//        'plugins': ['types']
//    });
