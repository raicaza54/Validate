$(document).ready(function () {
    var chart = bb.generate({
        data: {
            columns: [
                ["data2", 30, 20, 50, 40, 60, 50, 60, 70, 80],
                ["data1", 200, 130, 90, 240, 130, 220, 100, 120, 140],
            ],
            type: "bar",
            types: {
                data1: "line",
                data2: "bar",
            }
        },
        bindto: "#CombinationChart"
    });
    $('#example').DataTable();
});
