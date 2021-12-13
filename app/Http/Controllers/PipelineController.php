<?php

namespace App\Http\Controllers;
use App\Transaction;
use Illuminate\Contracts\Pipeline\Pipeline;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use PhpParser\Node\Expr\Cast\Object_;
use stdClass;

class PipelineController extends Controller
{
    //
    public function index(){
        $transactions = Transaction::latest()->with('user')->get();
        $sql = "SELECT * FROM users a, (
            SELECT agent FROM transactions GROUP BY agent
            ) b WHERE a.id = b.agent";
        $agents = DB::select($sql);
        $pipelines = array();
        foreach ($transactions as $transaction){
            $pipeline = new stdClass();
            $pipeline->id = $transaction->id;
            $pipeline->type = $transaction->type;
            $pipeline->agent = $transaction->user->name;
            $pipeline->closedate = $transaction->closedate;
            $pipeline->split = $transaction->split;
            $pipeline->address = $transaction->address;
            $pipeline->price = $transaction->price;
            $pipeline->coop_fee = $transaction->coop_fee;
            $pipeline->referral = $transaction->referral;
            $pipeline->check = $transaction->check;
            $pipeline->notes = $transaction->notes;
            $split = $transaction->split/100;
            if($transaction->type === 'Buy'){
                $pipeline->gci = $pipeline->coop_fee - $pipeline->referral;
                $pipeline->agent_income = $pipeline->gci*(1-$split)-350;
                $pipeline->camber_income = round($pipeline->gci*$split*0.85,0,PHP_ROUND_HALF_UP);
                $pipeline->cam = round($pipeline->gci*$split*0.15-50,0,PHP_ROUND_HALF_DOWN);
            }
            if($transaction->type === 'Sell'){
                $pipeline->gci = $pipeline->coop_fee - $pipeline->referral;
                $pipeline->agent_income = $pipeline->gci*(1-$split)-100;
                $pipeline->camber_income = round($pipeline->gci*$split*0.85,0,PHP_ROUND_HALF_UP);
                $pipeline->cam = round($pipeline->gci*$split*0.15-300,0,PHP_ROUND_HALF_DOWN);
            }
            if($transaction->type === 'Personal'){
                $pipeline->gci = 0;
                $pipeline->agent_income = 0;
                $pipeline->camber_income = 1000;
                $pipeline->cam = 0;
            }
            if($transaction->type === 'Expense'){
                $pipeline->gci = 0;
                $pipeline->agent_income = 0;
                $pipeline->camber_income = 0;
                $pipeline->cam = -1*$transaction->expense;
            }
            array_push($pipelines,$pipeline);
        }
        return view('admin.pipeline.index', ['transactions' => $transactions,'agents'=>$agents,'pipelines'=>$pipelines]);
    }
}
