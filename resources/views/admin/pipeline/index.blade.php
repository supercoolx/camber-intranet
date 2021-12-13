@extends('layouts.admin')

@section('content')
<style>
    .dataTables_wrapper{
        width:-webkit-fill-available;
    }
    .input_addon {
        background: #7a7a7a;
        color: white;
    }

    .form-control:focus {
        border: 1px solid #eb7226;
        -webkit-box-shadow: inset 0 1px 1px rgba(0, 0, 0, 0.075), 0 0 8px rgba(235, 114, 38, 0.6);
        box-shadow: inset 0 1px 1px rgba(0, 0, 0, 0.075), 0 0 8px rgba(235, 114, 38, , 0.6);
    }
    .default_option{
        background-color: #7a7a7a;
        color: white;
    }
    .form-control:disabled {
        background-color: #dddddd
    }
</style>
    <div class="row">
        <div class="col-12 pb-1">
            <h2 class="text-center font-weight-bold">Pipeline</h2>
        </div>
    </div>
    <div class="row my-2 pb-2">
        <div class="col-3">
            <div class="input-group">
                <div class="input-group-prepend">
                    <span class="input-group-text input_addon">Type</span>
                </div>
                <select id="dropdown_type" class="form-control">
                    <option value="">All</option>
                    <option value="Buy & Sell">Buy & Sell</option>
                    <option value="Buy">Buy</option>
                    <option value="Sell">Sell</option>
                    <option value="Expense">Expense</option>
                    <option value="Personal">Personal</option>
                </select>
            </div>
        </div>
        <div class="col-4">
            <div class="input-group">
                <div class="input-group-prepend">
                    <span class="input-group-text input_addon">Agent</span>
                </div>
                <select id="dropdown_agents" class="form-control">
                   <option value="">All</option>
                   @foreach ($agents as $agent)
                        <option value="{{$agent->name}}">
                            {{$agent->name}}
                        </option>
                    @endforeach
                </select>
            </div>
        </div>
        <div class="col-3">
            <div class="input-group">
                <div class="input-group-prepend">
                    <span class="input-group-text input_addon">Year</span>
                </div>
                <select id="dropdown_year" class="form-control">
                    <option value="">All</option>
                    @for($year = 2019; $year< date("Y")+4;$year++)
                        <option value="{{$year}}">{{$year}}</option>
                    @endfor
                </select>
            </div>
        </div>
    </div>
    <div class="row">
        <table class="table table-bordered table-dark table-sm text-center" style="background-color: #7a7a7a;">
            <thead>
                <tr>
                    <th></th>
                    <th>Filtered Total</th>
                    <th>This Page Total</th>
                    <th>All Total</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>Sales</td>
                    <td id="filtered_sales"></td>
                    <td id="page_sales"></td>
                    <td id="all_sales"></td>
                </tr>
                <tr>
                    <td>GCI</td>
                    <td id="filtered_gci"></td>
                    <td id="page_gci"></td>
                    <td id="all_gci"></td>
                </tr>
                <tr>
                    <td>Agent Income</td>
                    <td id="filtered_agent_income"></td>
                    <td id="page_agent_income"></td>
                    <td id="all_agent_income"></td>
                </tr>
                <tr>
                    <td>Camber Income</td>
                    <td id="filtered_camber_income"></td>
                    <td id="page_camber_income"></td>
                    <td id="all_camber_income"></td>
                </tr>
                <tr>
                    <td>CAM</td>
                    <td id="filtered_cam"></td>
                    <td id="page_cam"></td>
                    <td id="all_cam"></td>
                </tr>
            </tbody>
        </table>
    </div>
    <div class="row" style="overflow:auto;">
        <caption>
            <div>
                <p>Total Sales:<span id="sum_total"></span>
            </div>
        </caption>
        <table id="pipeline_table" style="font-size:13px;"  class="table table-striped table-bordered dt-responsive nowrap">
            <thead>
                <tr>
                    <th>Close Date</th>
                    <th>Type</th>
                    <th>Agent</th>
                    <th>Split</th>
                    <th>Address</th>
                    <th>Price</th>
                    <th>
                        <div>CoOp</div>
                        <div>Fee</div>
                    </th>
                    <th>Referral</th>
                    <th>GCI</th>
                    <th>
                        <div>Agent</div>
                        <div>Income</div>
                    </th>
                    <th>
                        <div>Camber</div>
                        <div>Income</div>
                    </th>
                    <th>CAM</th>
                    <th>Check</th>
                    <th>Notes</th>
                </tr>
            </thead>
            <tbody id="tbody">
                @foreach ($pipelines as $pipeline)
                    <tr data-id="{{$pipeline->id}}">
                        <td>{{$pipeline->closedate}}</td>
                        <td>{{$pipeline->type}}</td>
                        <td>{{$pipeline->agent}}</td>
                        <td>{{$pipeline->split}}</td>
                        <td>{{$pipeline->address}}</td>
                        <td>{{$pipeline->price}}</td>
                        <td>{{$pipeline->coop_fee}}</td>
                        <td>{{$pipeline->referral}}</td>
                        <td>{{$pipeline->gci}}</td>
                        <td>{{$pipeline->agent_income}}</td>
                        <td>{{$pipeline->camber_income}}</td>
                        <td>{{$pipeline->cam}}</td>
                        <td>{{$pipeline->check}}</td>
                        <td class="dt-control"></td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
<script src="{{ asset('js/filterDropDown.js')}}"></script>
<script>
    let pipelines = @json($pipelines);
    function format(notes){
        return '<div>' + notes + '</div>';
    }
    $(function() {
        var table = $("#pipeline_table").DataTable({
            "footerCallback": function ( row, data, start, end, display ) {
                var api = this.api(), data;
                // Remove the formatting to get integer data for summation
                var intVal = function ( i ) {
                    return typeof i === 'string' ?
                        i.replace(/[\$,]/g, '')*1 :
                        typeof i === 'number' ?
                            i : 0;
                };
    
                // Total over all pages
                sales_total = api
                    .column( 5 )
                    .data()
                    .reduce( function (a, b) {
                        return intVal(a) + intVal(b);
                    }, 0 );
    
                // Total over this page
                sales_pageTotal = api
                    .column( 5, { page: 'current'} )
                    .data()
                    .reduce( function (a, b) {
                        return intVal(a) + intVal(b);
                    }, 0 );
           
                // Total filtered rows on the selected column (code part added)
                var salesFiltered = display.map(el => data[el][5]).reduce((a, b) => intVal(a) + intVal(b), 0 );
                let gci_total = api
                    .column( 8 )
                    .data()
                    .reduce( function (a, b) {
                        return intVal(a) + intVal(b);
                    }, 0 );
    
                // Total over this page
                let gci_pageTotal = api
                    .column( 8, { page: 'current'} )
                    .data()
                    .reduce( function (a, b) {
                        return intVal(a) + intVal(b);
                    }, 0 );
                var gciFiltered = display.map(el => data[el][8]).reduce((a, b) => intVal(a) + intVal(b), 0 );
                let agent_income_total = api
                    .column( 9 )
                    .data()
                    .reduce( function (a, b) {
                        return intVal(a) + intVal(b);
                    }, 0 );
    
                // Total over this page
                let agent_income_pageTotal = api
                    .column( 9, { page: 'current'} )
                    .data()
                    .reduce( function (a, b) {
                        return intVal(a) + intVal(b);
                    }, 0 );
                // Total filtered rows on the selected column (code part added)
                var agent_incomeFiltered = display.map(el => data[el][9]).reduce((a, b) => intVal(a) + intVal(b), 0 );
                let camber_income_total = api
                    .column( 11 )
                    .data()
                    .reduce( function (a, b) {
                        return intVal(a) + intVal(b);
                    }, 0 );
    
                // Total over this page
                let camber_income_pageTotal = api
                    .column( 11, { page: 'current'} )
                    .data()
                    .reduce( function (a, b) {
                        return intVal(a) + intVal(b);
                    }, 0 );
                // Total filtered rows on the selected column (code part added)
                var camber_incomeFiltered = display.map(el => data[el][11]).reduce((a, b) => intVal(a) + intVal(b), 0 );
                let cam_total = api
                    .column( 10 )
                    .data()
                    .reduce( function (a, b) {
                        return intVal(a) + intVal(b);
                    }, 0 );
    
                // Total over this page
                let cam_pageTotal = api
                    .column( 10, { page: 'current'} )
                    .data()
                    .reduce( function (a, b) {
                        return intVal(a) + intVal(b);
                    }, 0 );
                // Total filtered rows on the selected column (code part added)
                var camFiltered = display.map(el => data[el][10]).reduce((a, b) => intVal(a) + intVal(b), 0 );
                $('#all_sales').html(sales_total);
                $('#filtered_sales').html(salesFiltered);
                $('#page_sales').html(sales_pageTotal);
                $('#all_gci').html(gci_total);
                $('#filtered_gci').html(gciFiltered);
                $('#page_gci').html(gci_pageTotal);
                $('#all_agent_income').html(agent_income_total);
                $('#filtered_agent_income').html(agent_incomeFiltered);
                $('#page_agent_income').html(agent_income_pageTotal);
                $('#all_camber_income').html(camber_income_total);
                $('#filtered_camber_income').html(camber_incomeFiltered);
                $('#page_camber_income').html(camber_income_pageTotal);
                $('#all_cam').html(cam_total);
                $('#filtered_cam').html(camFiltered);
                $('#page_cam').html(cam_pageTotal);
            }
        });
        $('#dropdown_type').on('change', function () {
            var whatsSelected = [];
            if(this.value==='Buy & Sell'){  
                whatsSelected.push('(?=.*' + 'Buy' + ')');
                whatsSelected.push('(?=.*' + 'Sell' + ')');
                table.columns(1).search(whatsSelected.join('|'), true, false, true).draw();
            }
            else{
                table.columns(1).search(this.value).draw();
            }
        });
        $('#dropdown_year').on('change',function(){
            table.columns(0).search(this.value).draw();
        })
        $('#dropdown_agents').on('change',function(){
            table.columns(2).search(this.value).draw();
        })
        $("#pipeline_table tbody").on('click', 'td.dt-control', function(){
            var tr = $(this).closest('tr');
            var row = table.row(this);
            let pipeline = pipelines.find(ele=>{
                return ele.id === Number(tr.attr('data-id'));
            });
            if ( row.child.isShown() ) {
                // This row is already open - close it
                row.child.hide();
                tr.removeClass('shown');
            }
            else {
                // Open this row
                row.child(format(pipeline.notes)).show();
                tr.addClass('shown');
            }
        }
        );
    })
</script>
@endsection
