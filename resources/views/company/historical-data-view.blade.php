<!doctype html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <meta name="csrf-token" content="{{ csrf_token() }}" />
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/css/bootstrap.min.css"
            integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
        <link rel="stylesheet" href="https://code.jquery.com/ui/1.13.2/themes/base/jquery-ui.css">
        <title>Company :: Historical Data</title>
    </head>

    <body>
        <div class="container">
            <h1>Company historical data view</h1>
            <form id="submitForm" class="card-body">
                <div class="form-row">
                    <div class="col">
                        <input type="text" required class="form-control" id="companySymbol" placeholder="Company Symbol">
                    </div>
                    <div class="col">
                        <input class="form-control" required id="startDate" type="text" placeholder="Start Date">
                    </div>
                    <div class="col">
                        <input class="form-control" required id="endDate" type="text" placeholder="End Date">
                    </div>
                    <div class="col">
                        <input type="email" required class="form-control" id="email" placeholder="Email">
                    </div>
                    <div class="col">
                        <button type="submit" id="subsmitButtonId" class="btn btn-outline-success" style="float: right;">Get Data</button>
                    </div>
                </div>
            </form>

            <div id="data-output-id">

            </div>


            <div id="graphCanvas">
                <canvas id="myChart"></canvas>
            </div>
        </div>

        <script src="https://code.jquery.com/jquery-3.6.0.js"></script>
        <script src="https://code.jquery.com/ui/1.13.2/jquery-ui.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

        <script>
            $( function() {
                $("#startDate").datepicker({
                    dateFormat: 'yy-mm-dd',
                    changeMonth: true,
                    changeYear: true,
                    showAnim: 'slideDown',
                    duration: 'fast',
                    maxDate: 0,
                    onSelect: function(date) {
                        var startDate = $('#startDate').datepicker('getDate');
                        var startDate = new Date(Date.parse(startDate));
                        startDate.setDate(startDate.getDate());
                        var startDate = startDate.toDateString();
                        startDate = new Date(Date.parse(startDate));
                        $('#endDate').datepicker("option", "minDate", startDate);
                    }
                });
                $( '#endDate' ).datepicker({
                    dateFormat: 'yy-mm-dd',
                    changeMonth: true,
                    changeYear: true,
                    showAnim: 'slideDown',
                    duration: 'fast',
                    maxDate: 0,
                });
            });
        </script>

        <script>
            const ctx = document.getElementById('myChart');

            $('#submitForm').on('submit',function(e){
                e.preventDefault();
                $("#graphCanvas").hide();
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': jQuery('meta[name="csrf-token"]').attr('content')
                    }
                });

                loader = '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span><span class="sr-only">Loading...</span>';
                document.getElementById('data-output-id').innerHTML = "";

                $.ajax({
                    url: "{!! route('company.historical.data.search') !!}",
                    type: "POST",
                    data: {company_symbol: $('#companySymbol').val(), start_date: $('#startDate').val(), end_date: $('#endDate').val(), email: $('#email').val()},
                    dataType: "json",
                    beforeSend: function() {
                        $("#subsmitButtonId").prop("disabled",true);
                        $("#subsmitButtonId").html(loader);
                    },
                    success: function (data) {
                        if (data.code == 200){
                            document.getElementById('data-output-id').innerHTML = data.html;
                            if ((data.chartData.labels).length > 0) {
                                $("#graphCanvas").show();
                                chartLoad(data.chartData);
                            }
                        }
                        else if (data.code == 422) {
                            document.getElementById('data-output-id').innerHTML = getErrorHtml(data.errors);
                        }
                        else if (data.code == 500) {
                            document.getElementById('data-output-id').innerHTML = (data.message);
                        }
                    },
                    complete: function (data) {
                        $("#subsmitButtonId").prop("disabled", false);
                        $("#subsmitButtonId").html("Get Data");
                    },
                    fail: function (data) {
                        document.getElementById('data-output-id').innerHTML = "Failed to process the data.";
                        $("#subsmitButtonId").prop("disabled", false);
                        $("#subsmitButtonId").html("Get Data");
                    }
                });
            });

            function getErrorHtml($errors) {
                var errorsHtml = '';
                $.each($errors, function (key, value) {
                    if (value.constructor === Array) {
                        $.each(value, function (i, v) {
                            errorsHtml += '<li class="text-danger">' + v + '</li>';
                        });
                    } else {
                        errorsHtml += '<li class="text-danger">' + value[0] + '</li>';
                    }
                });
                return errorsHtml
            }
        </script>

        <script>
            function chartLoad(chartData) {
                labels = chartData.labels;
                data = {
                    labels: labels,
                    datasets: [
                            {
                                label: 'Open Prices',
                                data: chartData.openPrices,
                            },
                            {
                                label: 'Close prices',
                                data: chartData.closePrices,
                            }
                        ]
                    };
                new Chart(ctx, {
                    type: 'line',
                    data: data,
                    options: {
                        responsive: true,
                        plugins: {
                            legend: {
                                position: 'top',
                            },
                            title: {
                                display: true,
                                text: 'Chart of the Open and Close prices'
                            }
                        }
                    },
                });
            }
        </script>
    </body>
</html>
