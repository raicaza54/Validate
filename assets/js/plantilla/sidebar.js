$(document).ready(function () {
    $("#sidebar").mCustomScrollbar({
        theme: "minimal"
    });
    $('i,a,div').popover({trigger:'hover', delay:500});
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
    $('#simpleTree').jstree({
        'core': {
            'themes': {
                'responsive': false,
            },
            'multiple': false
        },
        'types': {
            'default': {
                'icon': 'far fa-folder'
            },
            'file': {
                'icon': 'far fa-file-excel'
            }
        },
        'plugins': ['types']
    });
});
function MenuViewModel() {
    /*Menu Principal*/
    this.PrinCrear = 'item-disabled';
    this.PrinPropiedades = '';
    this.PrinListar = '';
    this.PrinArchivo = '';
    this.PrinConexion = 'item-disabled';
    this.PrinCSV = '';
    this.PrinExcel = 'item-disabled';
    /*Menu Datos*/
    this.DatoDuplicar = 'item-disabled';
    this.DatoIndices = 'item-disabled';
    this.DatoOrdenar = 'item-disabled';
    this.DatoBuscar = '';
    this.DatoSiguiente = '';
    this.DatoIr = '';
    /*Menu Analisis*/
    this.AnalEjecutar = '';
    this.AnalBenford = '';
    this.AnalOmisiones = 'item-disabled';
    this.AnalDuplicados = 'item-disabled';
    this.AnalSpider = '';
}
//ko.applyBindings(new MenuViewModel());
