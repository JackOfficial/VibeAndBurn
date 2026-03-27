<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\sharedlink;
use App\Models\bonus;
use App\Models\User;
use App\Models\wallet;
use Illuminate\Support\Facades\Auth;

class sharedlinkController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
      
     $sharedlinks = bonus::with('user')->latest()->paginate(25);

    $bonusesCounter = bonus::count();
    
    // Sum the 'bonus' column
    $bonusesTotal = bonus::sum('bonus');

    return view('admin.bonuses.index', compact(
        'sharedlinks', 
        'bonusesCounter', 
        'bonusesTotal'
    ));
    }
    
    public function bonus(Request $request, $id)
    {
      
    //   $sharedlinks = bonus::join('users', 'bonuses.user_id', '=', 'users.id')
    //     ->select('bonuses.*', 'users.name', 'users.email')
    //      ->orderBy('bonuses.id', 'DESC')
    //      ->get();
         
        $linkOwner = bonus::where('user_id', $id)->value('bonus');
        
        echo $linkOwner;
        
         
    //       $bonusesCounter = bonus::count();
          
    //          $bonusesTotal = bonus::sum('bonus');
    //   return view('admin.manage.bonuses', compact('sharedlinks', 'bonusesCounter', 'bonusesTotal'));
    }
    
    public function cleambonus()
    {
      $userID = Auth::user()->id;
      
      $bonus = bonus::where('user_id', '=', $userID)->update([
          'status' => 1
          ]);
          
          if($bonus){
          return redirect()->back()->with('cleamBonusSuccess', 'Your Bonus has been claimed. Wait') ;
          }
        
      
    }
    
     public function mybonus()
    {
        if(Auth::check()){
       $userID = Auth::user()->id;
       $countBonus = bonus::where('user_id', $userID)->count();
      if($countBonus > 0){
          $mybonus = bonus::where('user_id', $userID)->value('bonus');
      }
      else{
          $mybonus = 0;
      }
    $walletCounter = wallet::where('user_id', $userID)->count();
    if($walletCounter == 0){
      $wallet = 0; 
    }
    else{
      $wallet = wallet::where('user_id', '=', $userID)->value('money');
    } 
    return view('mybonus', compact('mybonus', 'wallet'));
        }
        else{
           return redirect('login');
        }
    }
    

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, $id)
    { 
       $request->Session()->put('sharedLinkID', $id);
       return redirect('/');
    }
    
     public function referral(Request $request, $id)
    { 
        $linkOwner = User::findOrFail($id);
       $referral = sharedlink::join('users', 'sharedlinks.user_id', '=', 'users.id')
        ->select('sharedlinks.*', 'users.name', 'users.email')
        ->where('sharedlinks.descendant', $id)
        ->orderBy('sharedlinks.id', 'DESC')
        ->get();
        
        $referralsCount = sharedlink::join('users', 'sharedlinks.user_id', '=', 'users.id')
        ->select('sharedlinks.*', 'users.name', 'users.email')
        ->where('sharedlinks.descendant', $id)
        ->count();
        
         $referralSum = sharedlink::join('users', 'sharedlinks.user_id', '=', 'users.id')
        ->select('sharedlinks.*', 'users.name', 'users.email')
        ->where('sharedlinks.descendant', $id)
        ->sum('amount');
        
        return view('admin.manage.invitee', compact('referral', 'linkOwner', 'referralsCount', 'referralSum'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $linkOwner = bonus::where('user_id', $id)->get();
        return view('admin.edit.offer-bonus', compact('linkOwner'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'bonus' => 'required',
            'offer' => 'required',
            'remaining' => 'required',
            ]);
            
        $bonus = $request->bonus;
        $offer = $request->offer;
        $remaining = $request->remaining;
        
        $result = $bonus - $offer;
        
      $offerBonus = bonus::where('user_id', $id)->update([
          'bonus' => $result
          ]);
      if($offerBonus){
        $wallet = wallet::where('user_id', '=', $id)->count();  
        if($wallet == 0){
        wallet::create([
            'user_id' => $id,
            'money' => $offer,
        ]); 
       }
       else{
        $mywallet = wallet::where('user_id', '=', $id)->sum('money');
        wallet::where('user_id', '=', $id)->update([
            'money' => $mywallet + $offer,
        ]);
       }
       
       return redirect()->back()->with('offerBonusSuccess', 'Bonus has been offered');
       
      }  
      else{
        return redirect()->back()->with('offerBonusFail', 'Bonus could not be offered');
      }
       
        
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
