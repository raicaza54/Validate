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
ko.applyBindings(new MenuViewModel());