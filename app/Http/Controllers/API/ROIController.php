<?php

namespace App\Http\Controllers\API;
use App\Http\Controllers\Controller;
use App\Http\Requests\CreateROIRequest;
use App\Models\ROIHistory;
use App\Models\UserInvestment;
use App\Traits\ApiResponse;

class ROIController extends Controller
{

    public function __construct()
    {
        $this->walletController = new WalletController();
        $this->transactionController = new TransactionController();
    }

    public function index()
    {
        //
    }


    public function store(CreateROIRequest $request)
    {
        $data =  $request->validated();
        $getUserInvestments = UserInvestment::where( 'investment_id', $data['investment_id'] )->get();
        $totalUnitsOwned = $getUserInvestments->sum('units');
            if( $getUserInvestments ){
                $investmentByUser = $getUserInvestments->groupBy('user_id');
                    foreach( $investmentByUser as $investments ){
                        $unitsOwnedByInvestor = $investments->sum('units');
                        $userId = $investments[0]->user_id;
                        $investorUnitPercent = $unitsOwnedByInvestor * 100 / $totalUnitsOwned;
                        $amount = $investorUnitPercent * $data['amount'] / 100;
                        $createROI = ROIHistory::create([
                            'user_id' => $userId,
                            'investment_id' => $data['investment_id'],
                            'amount' => $amount,
                        ]);

                        $addRoiToInvestorWalletBalance = $this->walletController->addRoiToInvestorWallet($userId, $amount);

                        $transactionData = [
                            'user_id' => $userId,
                            'amount' => $amount,
                            'transaction_type' => 'ROI',
                            'type_id' => $createROI->id,
                            'status' => 1
                        ];

                        $createTransactionForTheRoiEvent = $this->transactionController->store($transactionData);
                    }

                 return ApiResponse::successResponse('ROI distributed successfully', 203 );

            } else{
                return ApiResponse::errorResponse( 'No current investors for the selected investment', 400 );
            }
    }

}
