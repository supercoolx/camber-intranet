@extends('layouts.admin')

@section('content')

<style>
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
<div class="title">
    <h1 class="text-center font-weight-bold mb-4 mt-4">New transaction</h1>
</div>
<form class="form-horizontal" method="POST" action="{{ url('/admin/transactions') }}" accept-charset="UTF-8" id="transaction_add_form">
    {{ csrf_field() }}
    <div class="row">
        <div class="col-sm-6">
            <div class="input-group">
                <div class="input-group-prepend">
                    <span class="input-group-text input_addon" id="agent_label">Agent</span>
                </div>
                <select name="agent" id="agent" class="form-control" required>
                    <option selected="true" disabled="disabled" class="default_option" value="">Agent</option>
                    @foreach ($agents as $agent)
                    <option value="{{ $agent->id }}">
                        {{ $agent->name }}
                    </option>
                    @endforeach
                </select>
            </div>
        </div>
        <div class="col-sm-6">
            <div class="input-group">
                <div class="input-group-prepend">
                    <span class="input-group-text input_addon" id="type_label">Type</span>
                </div>
                <select name="type" id="type" class="form-control" required>
                    <option selected="true" class="default_option" value="" disabled="disabled">Type</option>
                    <option value="Buy">Buy</option>
                    <option value="Sell">Sell</option>
                    <option value="Expense">Expense</option>
                    <option value="Personal">Personal</option>
                </select>
            </div>
        </div>
    </div>
    <div class="row mt-3"> 
        <div class="col-sm-6">
            <div class="input-group">
                <div class="input-group-prepend">
                    <span class="input-group-text input_addon">Split</span>
                </div>
                <input type="number" name="split" id="split" class="form-control" required>
                <div class="input-group-append">
                    <span class="input-group-text input_addon">%</span>
                </div>
            </div>
        </div>
        <div class="col-sm-6">
            <div class="input-group">
                <div class="input-group-prepend">
                    <span class="input-group-text input_addon">Date</span>
                </div>
                <input type='date' id="closedate" name="closedate" class="form-control" value='{{ date("Y-m-d") }}'>
            </div>    
        </div>
    </div>
    <div class="row mt-3">
        <div class="col-sm-6">
            <div class="input-group">
                <div class="input-group-prepend">
                    <span class="input-group-text input_addon">Address</span>
                </div>
                <input type="text" name="address" class="form-control" id="address" required/>
            </div>
        </div>
        <div class="col-sm-6">
            <div class="input-group">
                <div class="input-group-prepend">
                    <span class="input-group-text input_addon">Price</span>
                </div>
                <input class="form-control" type="number" name="price" id="price" required />
                <div class="input-group-append">
                    <span class="input-group-text input_addon">$</span>
                </div>
            </div>
        </div>
    </div>
    <div class="row mt-3">  
        <div class="col-sm-6">
            <div class="input-group">
                <div class="input-group-prepend">
                    <span class="input-group-text input_addon">CoOP_fee</span>
                </div>
                <input class="form-control" type="number" name="coop_fee" id="coop_fee" required />
                <div class="input-group-append">
                    <span class="input-group-text input_addon">$</span>
                </div>
            </div>
        </div>
        <div class="col-sm-6">
            <div class="input-group">
                <div class="input-group-prepend">
                    <span class="input-group-text input_addon">Referral</span>
                </div>
                <input class="form-control" name="referral" id="referral" type="number" required />
                <div class="input-group-append">
                    <span class="input-group-text input_addon">$</span>
                </div>
            </div>
        </div>
    </div>
    <div class="row mt-3">
        <div class="col-sm-6">
            <div class="input-group">
                <div class="input-group-prepend">
                    <span class="input-group-text input_addon">Expense</span>
                </div>
                <input type="number" id="expense" name="expense" class="form-control" required>
                <div class="input-group-append">
                    <span class="input-group-text input_addon">$</span>
                </div>
            </div>
        </div>
        <div class="col-sm-6">
            <div class="input-group">
                <div class="input-group-prepend">
                    <span class="input-group-text input_addon">Check</span>
                </div>
                <select name="check" id="check" class="form-control"  required>
                    <option value="Open">Open</option>
                    <option value="Received">Received</option>
                    <option value="Deposited">Deposited</option>
                </select>
            </div>
        </div>
    </div>
    <div class="row mt-3">
        <div class="col-12">
            <div class="input-group">
                <div class="input-group-prepend">
                    <span class="input-group-text input_addon">Notes</span>
                </div>
                <input name="notes" id="notes" class="form-control" required/>
            </div>
        </div>
    </div>
   <div class="row mt-3 justify-content-center">
        <div class="col-sm-4 d-flex justify-content-around">
            <div class="align-items-center">
                <button type="submit" class="btn btn-primary">Save</button>
            </div>
            <div class="align-items-center">
                <a class="btn btn-danger" href="{{ url('/admin/transactions') }}">Cancel</a>
            </div>
        </div>
    </div>
</form>
<script>
    let agents = <?php echo json_encode($agents)?>;
    console.log(agents);
    $(document).ready(function() {
        $('.cell').click(function() {
            $('.cell').removeClass('select');
            $(this).addClass('select');
        });
        $('#type').on('change', function() {
            $("#transaction_add_form input").attr("disabled", false)
            if (this.value === "Expense") {
                /*alert(strUser);*/
                $('#price').attr("disabled", true);
                $('#split').attr("disabled", true);
                $('#coop_fee').attr("disabled", true);
                $('#referral').attr("disabled", true);
                $('#check').attr("disabled", true);
                $('#address').val('Expense');
                $('#address').attr("disabled",true);
                $('#price').val(0);
                $('#split').val(0);
                $('#coop_fee').val(0);
                $('#referral').val(0);
                $('#check').val('Open');
               

            } else if (this.value === "Buy" || this.value === "Sell") {
                $("#expense").attr("disabled", true);
                $("#expense").val('');
                $("#check").attr('disabled',false);
                $("#address").val("");
                if(this.value === 'Buy'){
                    $("#check").val("Open");
                }
                else{
                    $("#check").val("Received");
                }
            } else if (this.value === "Personal") {
                /*document.getElementById(input_id).value = document.getElementById(input_id).defaultValue;*/
                $("#split").attr("disabled", true);
                $("#referral").attr("disabled", true);
                $("#coop_fee").attr("disabled", true);
                $("#expense").attr("disabled", true);
                $("#check").val("Deposited");
                $("#check").attr("disabled",false);
                $("#split").val(0);
                $("#referral").val(0);
                $("#coop_fee").val(0);
                $("#expense").val(0);
            }
        })
        $('#agent').on('change',function(){
            const selected_agent = agents.find(element=>{
                return element.id === Number(this.value);
            });
            $('#split').val(selected_agent.split);
        })
    });
</script>
@include('errors.list')

@endsection