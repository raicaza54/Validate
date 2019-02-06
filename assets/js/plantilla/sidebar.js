$(document).ready(function () {
    $("#sidebar").mCustomScrollbar({
        theme: "minimal"
    });
    $('a,div').popover({trigger:'hover', delay:500});
    $('#sidebarCollapse').on('click', function () {
        $('#sidebar').toggleClass('active');
    });
    $("#sidebar").mCustomScrollbar({
        theme: "minimal"
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