$("#sidebar").mCustomScrollbar({
    theme: "minimal"
});
$('a').popover();
$('div').popover({trigger: 'hover', delay: 500});
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
$("#mn-crear").addClass('');
$("#mn-propiedades").addClass('item-disabled');
$("#mn-listar").addClass('');
$("#mn-archivo").addClass('');
$("#mn-conexion").addClass('item-disabled');
$("#mn-CSV").addClass('item-disabled');
$("#mn-excel").addClass('item-disabled');
$("#mn-duplicar").addClass('item-disabled');
$("#mn-indices").addClass('item-disabled');
$("#mn-ordenar").addClass('item-disabled');
$("#mn-filtrar").addClass('item-disabled');
$("#mn-siguiente").addClass('item-disabled');
$("#mn-datoIr").addClass('');
$("#mn-ejecutar").addClass('item-disabled');
$("#mn-benford").addClass('');
$("#mn-spider").addClass('');
$("#mn-manipulacion").addClass('item-disabled');
$("#mn-materialidad").addClass('item-disabled');
$("#mn-dictamen").addClass('item-disabled');
$("#mn-papeles").addClass('item-disabled');
$("#mn-marcas").addClass('item-disabled');
$("#mn-analisis").addClass('item-disabled');
