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
            <h2 class="text-center font-weight-bold">Transactions</h2>
        </div>
    </div>
    <div class="row my-2 pb-2">
        <div class="col-2">
            <a href="{{ url('/admin/transactions/create') }}" class="btn btn-primary">Add New</a>
        </div>
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
    <div class="row" style="overflow:auto">
        <table id="transaction_table"  class="table table-striped table-bordered dt-responsive nowrap">
            <thead>
                <tr>
                    <th>Type</th>
                    <th>Agent</th>
                    <th>Split</th>
                    <th>Close Date</th>
                    <th>Address</th>
                    <th>Price</th>
                    <th>CoOP Fee</th>
                    <th>Referral</th>
                    <th>Expense</th>
                    <th>Check</th>
                    <th>Notes</th>
                </tr>
            </thead>
            <tbody id="tbody">
                @foreach ($transactions as $transaction)
                    <tr data-id="{{$transaction->id}}">
                        <td>{{$transaction->type}}</td>
                        <td>{{$transaction->user->name}}</td>
                        <td>{{$transaction->split}}</td>
                        <td>{{date_format(new DateTime($transaction->closedate), 'm/d/Y')}}</td>
                        <td>{{$transaction->address}}</td>
                        <td>{{$transaction->price}}</td>
                        <td>{{$transaction->coop_fee}}</td>
                        <td>{{$transaction->referral}}</td>
                        <td>{{$transaction->expense}}</td>
                        <td>{{$transaction->check}}</td>
                        <td class="dt-control"></td>
                    </tr>
                @endforeach
            </tbody>
  
        </table>
    </div>
<script src="{{ asset('js/filterDropDown.js')}}"></script>
<script>
    let transactions = @json($transactions);
    function format(notes){
        return '<div>' + notes + '</div>';
    }
    $(function() {
        var table = $("#transaction_table").DataTable({
            
        });
        $('#dropdown_type').on('change', function () {
            var whatsSelected = [];
            if(this.value==='Buy & Sell'){
                whatsSelected.push('(?=.*' + 'Buy' + ')');
                whatsSelected.push('(?=.*' + 'Sell' + ')');
                table.columns(0).search(whatsSelected.join('|'), true, false, true).draw();
            }
            else{
                table.columns(0).search(this.value).draw();
            }
        } );
        $('#dropdown_year').on('change',function(){
            const search_year = this.value.substr(2,2);
            table.columns(3).search(search_year).draw();
        })
        $('#dropdown_agents').on('change',function(){
            table.columns(1).search(this.value).draw();
        })
        $("#transaction_table tbody").on('click', 'td.dt-control', function(){
            var tr = $(this).closest('tr');
            var row = table.row(this);
            let transaction = transactions.find(ele=>{
                return ele.id === Number(tr.attr('data-id'));
            });
            if ( row.child.isShown() ) {
                // This row is already open - close it
                row.child.hide();
                tr.removeClass('shown');
            }
            else {
                // Open this row
                row.child(format(transaction.notes)).show();
                tr.addClass('shown');
            }
        }
        );
    })
</script>
@endsection
