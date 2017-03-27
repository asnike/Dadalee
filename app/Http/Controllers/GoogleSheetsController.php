<?php

namespace App\Http\Controllers;


use App\RealEstate;
use App\User;
use Illuminate\Http\Request;
use Sheets;
use Google;
use Google_Client;


class GoogleSheetsController extends Controller
{
    //
    protected $requests;
    public function callback(){

    }
    public function export(Request $request){
        $realestate = RealEstate::with(['earningRate', 'loan'])->findOrFail($request->input('id'));

        $filename = $realestate->building_name.' '.trans('common.earningRateSheet');

        $client = $this->authCheck($request->session()->get('access_token'));
        $result = $this->addSheet($client, $filename, TRUE)->batchUpdate($client);
        $result = $this->addRealEstate($client, $filename, $realestate);

        return response()->json($result);
    }
    public function import(Request $request){
        $client = $this->authCheck($request->session()->get('access_token'));
        if(isset($client)){
            return $this->addSheet($client, 'teee', TRUE)->batchUpdate($client);
        }
    }
    protected function authCheck($access_token){
        $client = new Google_Client();
        $client->setAuthConfig('json/credential.json');
        $client->addScope(\Google_Service_Sheets::SPREADSHEETS);

        if(isset($access_token) && $access_token){
            $client->setAccessToken($access_token);
            return $client;
        }else{
            header('Location: '. filter_var(route('auth2.callback'), FILTER_SANITIZE_URL));
            exit;
        }
    }
    public function authCallback(Request $request){
        $client = new Google_Client();
        $client->setAuthConfig('json/credential.json');
        $client->setRedirectUri(route('auth2.callback'));
        $client->addScope(\Google_Service_Sheets::SPREADSHEETS);

        if(empty($request->input('code'))){
            $auth_url = $client->createAuthUrl();
            header('Location: '. filter_var($auth_url, FILTER_SANITIZE_URL));
        }else{
            $client->authenticate($request->input('code'));
            $request->session()->set('access_token', $client->getAccessToken());
            /*return redirect()->action('GoogleSheetsController@import');*/
        }
    }
    public function linked(){

    }
    public function link(Request $request){
        $sheetsId = $this->getSheetsId();
        if($sheetsId){

        }else{
            $client = $this->authCheck($request->session()->get('access_token'));
            $result = $this->createSpreadSheets($client);
            $user->update([
                'sheets_id'=>$result->spreadsheetId
            ]);
        }
        return view('mypage.sheetslink');
    }

    protected function getData($client, $sheetId){
        $service = new \Google_Service_Sheets($client);
        $range = 'A1';
        $response = $service->spreadsheets_values->get($sheetId, $range);
        return $response;
    }

    protected function createSpreadSheets($client){
        $service = new \Google_Service_Sheets($client);
        $sheets = new \Google_Service_Sheets_Spreadsheet();
        $properties = new \Google_Service_Sheets_SpreadsheetProperties();
        $properties->setTitle('Dadalee Sheets');
        $sheets->setProperties($properties);

        try{
            $sheets = $service->spreadsheets->create($sheets);
        }catch(\Exception $exception){
            header('Location: '. filter_var(route('auth2.callback'), FILTER_SANITIZE_URL));
            exit;
        }
        $sheet_properties = new \Google_Service_Sheets_SheetProperties();
        $sheet_properties->setTitle('list');
        $sheets->getSheets()[0]->setProperties($sheet_properties);

        return $sheets;
    }
    protected function addSheet($client, $title, $initRequest = FALSE){
        if($initRequest) $this->requests = array();
        $this->requests[] = new \Google_Service_Sheets_Request(array(
                'addSheet'=>array(
                    'properties'=> array(
                        'title'=>$title
                    )
                )
            )
        );
        return $this;
    }
    protected function addRealEstate($client, $sheetName, $realestate){
        $sheetName = "'".$sheetName."'";
        $service = new \Google_Service_Sheets($client);

        $response = $service->spreadsheets->get($this->getSheetsId(), array('includeGridData'=>true));
        $sheets = $response->getSheets();
        $sheetId = $sheets[count($sheets)-1]->getProperties()->getSheetId();
        $batchUpdateRequest = new \Google_Service_Sheets_BatchUpdateSpreadsheetRequest(
            array(
                'requests'=>[
                    new \Google_Service_Sheets_Request([
                        'repeatCell'=>[
                            'range'=>[
                                'sheetId'=>$sheetId,
                                'startRowIndex'=>'0',
                                'endRowIndex'=>'25',
                                'startColumnIndex'=>'0',
                                'endColumnIndex'=>'2',
                            ],
                            'cell'=>[
                                'userEnteredFormat'=>[
                                    'borders'=>[
                                        'top'=>[
                                            'style'=>'SOLID',
                                            'width'=>1,
                                            'color'=>[
                                                'red'=>0,
                                                'green'=>0,
                                                'blue'=>0,
                                                'alpha'=>1,
                                            ]
                                        ],
                                        'right'=>[
                                            'style'=>'SOLID',
                                            'width'=>1,
                                            'color'=>[
                                                'red'=>0,
                                                'green'=>0,
                                                'blue'=>0,
                                                'alpha'=>1,
                                            ]
                                        ],
                                        'bottom'=>[
                                            'style'=>'SOLID',
                                            'width'=>1,
                                            'color'=>[
                                                'red'=>0,
                                                'green'=>0,
                                                'blue'=>0,
                                                'alpha'=>1,
                                            ]
                                        ],
                                        'left'=>[
                                            'style'=>'SOLID',
                                            'width'=>1,
                                            'color'=>[
                                                'red'=>0,
                                                'green'=>0,
                                                'blue'=>0,
                                                'alpha'=>1,
                                            ]
                                        ]
                                    ]
                                ]
                            ],
                            'fields'=>'*'
                        ]
                    ]),
                    new \Google_Service_Sheets_Request([
                        'repeatCell'=>[
                            'range'=>[
                                'sheetId'=>$sheetId,
                                'startRowIndex'=>'15',
                                'endRowIndex'=>'17',
                                'startColumnIndex'=>'1',
                                'endColumnIndex'=>'2',
                            ],
                            'cell'=>[
                                'userEnteredFormat'=>[
                                    'numberFormat'=>[
                                        'type'=>'PERCENT'
                                    ],
                                    'borders'=>[
                                        'top'=>[
                                            'style'=>'SOLID',
                                            'width'=>1,
                                            'color'=>[
                                                'red'=>0,
                                                'green'=>0,
                                                'blue'=>0,
                                                'alpha'=>1,
                                            ]
                                        ],
                                        'right'=>[
                                            'style'=>'SOLID',
                                            'width'=>1,
                                            'color'=>[
                                                'red'=>0,
                                                'green'=>0,
                                                'blue'=>0,
                                                'alpha'=>1,
                                            ]
                                        ],
                                        'bottom'=>[
                                            'style'=>'SOLID',
                                            'width'=>1,
                                            'color'=>[
                                                'red'=>0,
                                                'green'=>0,
                                                'blue'=>0,
                                                'alpha'=>1,
                                            ]
                                        ],
                                        'left'=>[
                                            'style'=>'SOLID',
                                            'width'=>1,
                                            'color'=>[
                                                'red'=>0,
                                                'green'=>0,
                                                'blue'=>0,
                                                'alpha'=>1,
                                            ]
                                        ]
                                    ]
                                ]
                            ],
                            'fields'=>'*'
                        ]
                    ]),
                    new \Google_Service_Sheets_Request([
                        'repeatCell'=>[
                            'range'=>[
                                'sheetId'=>$sheetId,
                                'startRowIndex'=>'24',
                                'endRowIndex'=>'25',
                                'startColumnIndex'=>'1',
                                'endColumnIndex'=>'2',
                            ],
                            'cell'=>[
                                'userEnteredFormat'=>[
                                    'numberFormat'=>[
                                        'type'=>'PERCENT'
                                    ],
                                    'borders'=>[
                                        'top'=>[
                                            'style'=>'SOLID',
                                            'width'=>1,
                                            'color'=>[
                                                'red'=>0,
                                                'green'=>0,
                                                'blue'=>0,
                                                'alpha'=>1,
                                            ]
                                        ],
                                        'right'=>[
                                            'style'=>'SOLID',
                                            'width'=>1,
                                            'color'=>[
                                                'red'=>0,
                                                'green'=>0,
                                                'blue'=>0,
                                                'alpha'=>1,
                                            ]
                                        ],
                                        'bottom'=>[
                                            'style'=>'SOLID',
                                            'width'=>1,
                                            'color'=>[
                                                'red'=>0,
                                                'green'=>0,
                                                'blue'=>0,
                                                'alpha'=>1,
                                            ]
                                        ],
                                        'left'=>[
                                            'style'=>'SOLID',
                                            'width'=>1,
                                            'color'=>[
                                                'red'=>0,
                                                'green'=>0,
                                                'blue'=>0,
                                                'alpha'=>1,
                                            ]
                                        ]
                                    ]
                                ]
                            ],
                            'fields'=>'*'
                        ]
                    ]),
                    new \Google_Service_Sheets_Request([
                        'mergeCells'=>[
                            'range'=>[
                                'sheetId'=>$sheetId,
                                'startRowIndex'=>'0',
                                'endRowIndex'=>'1',
                                'startColumnIndex'=>'0',
                                'endColumnIndex'=>'2',
                            ],
                            'mergeType'=>'MERGE_ALL'
                        ]
                    ]),
                    new \Google_Service_Sheets_Request([
                        'mergeCells'=>[
                            'range'=>[
                                'sheetId'=>$sheetId,
                                'startRowIndex'=>'6',
                                'endRowIndex'=>'7',
                                'startColumnIndex'=>'0',
                                'endColumnIndex'=>'2',
                            ],
                            'mergeType'=>'MERGE_ALL'
                        ]
                    ]),
                    new \Google_Service_Sheets_Request([
                        'mergeCells'=>[
                            'range'=>[
                                'sheetId'=>$sheetId,
                                'startRowIndex'=>'13',
                                'endRowIndex'=>'14',
                                'startColumnIndex'=>'0',
                                'endColumnIndex'=>'2',
                            ],
                            'mergeType'=>'MERGE_ALL'
                        ]
                    ]),
                    new \Google_Service_Sheets_Request([
                        'mergeCells'=>[
                            'range'=>[
                                'sheetId'=>$sheetId,
                                'startRowIndex'=>'18',
                                'endRowIndex'=>'19',
                                'startColumnIndex'=>'0',
                                'endColumnIndex'=>'2',
                            ],
                            'mergeType'=>'MERGE_ALL'
                        ]
                    ]),
                    new \Google_Service_Sheets_Request([
                        'mergeCells'=>[
                            'range'=>[
                                'sheetId'=>$sheetId,
                                'startRowIndex'=>'21',
                                'endRowIndex'=>'22',
                                'startColumnIndex'=>'0',
                                'endColumnIndex'=>'2',
                            ],
                            'mergeType'=>'MERGE_ALL'
                        ]
                    ]),


                    new \Google_Service_Sheets_Request([
                        'repeatCell'=>[
                            'range'=>[
                                'sheetId'=>$sheetId,
                                'startRowIndex'=>'0',
                                'endRowIndex'=>'6',
                                'startColumnIndex'=>'0',
                                'endColumnIndex'=>'1',
                            ],
                            'cell'=>[
                                'userEnteredFormat'=>[
                                    'horizontalAlignment'=>'CENTER',
                                    'backgroundColor'=>[
                                        'red'=>0.23,
                                        'green'=>0.52,
                                        'blue'=>0.77
                                    ],
                                    'textFormat'=>[
                                        'foregroundColor'=>[
                                            'red'=>'1',
                                            'green'=>'1',
                                            'blue'=>'1'
                                        ],
                                    ],
                                    'borders'=>[
                                        'top'=>[
                                            'style'=>'SOLID',
                                            'width'=>1,
                                            'color'=>[
                                                'red'=>0,
                                                'green'=>0,
                                                'blue'=>0,
                                                'alpha'=>1,
                                            ]
                                        ],
                                        'right'=>[
                                            'style'=>'SOLID',
                                            'width'=>1,
                                            'color'=>[
                                                'red'=>0,
                                                'green'=>0,
                                                'blue'=>0,
                                                'alpha'=>1,
                                            ]
                                        ],
                                        'bottom'=>[
                                            'style'=>'SOLID',
                                            'width'=>1,
                                            'color'=>[
                                                'red'=>0,
                                                'green'=>0,
                                                'blue'=>0,
                                                'alpha'=>1,
                                            ]
                                        ],
                                        'left'=>[
                                            'style'=>'SOLID',
                                            'width'=>1,
                                            'color'=>[
                                                'red'=>0,
                                                'green'=>0,
                                                'blue'=>0,
                                                'alpha'=>1,
                                            ]
                                        ]
                                    ]
                                ],
                            ],
                            'fields'=>'*'
                        ]
                    ]),

                    new \Google_Service_Sheets_Request([
                        'repeatCell'=>[
                            'range'=>[
                                'sheetId'=>$sheetId,
                                'startRowIndex'=>'6',
                                'endRowIndex'=>'13',
                                'startColumnIndex'=>'0',
                                'endColumnIndex'=>'1',
                            ],
                            'cell'=>[
                                'userEnteredFormat'=>[
                                    'horizontalAlignment'=>'CENTER',
                                    'backgroundColor'=>[
                                        'red'=>0.6,
                                        'green'=>0,
                                        'blue'=>0
                                    ],
                                    'textFormat'=>[
                                        'foregroundColor'=>[
                                            'red'=>'1',
                                            'green'=>'1',
                                            'blue'=>'1'
                                        ],
                                    ],
                                    'borders'=>[
                                        'top'=>[
                                            'style'=>'SOLID',
                                            'width'=>1,
                                            'color'=>[
                                                'red'=>0,
                                                'green'=>0,
                                                'blue'=>0,
                                                'alpha'=>1,
                                            ]
                                        ],
                                        'right'=>[
                                            'style'=>'SOLID',
                                            'width'=>1,
                                            'color'=>[
                                                'red'=>0,
                                                'green'=>0,
                                                'blue'=>0,
                                                'alpha'=>1,
                                            ]
                                        ],
                                        'bottom'=>[
                                            'style'=>'SOLID',
                                            'width'=>1,
                                            'color'=>[
                                                'red'=>0,
                                                'green'=>0,
                                                'blue'=>0,
                                                'alpha'=>1,
                                            ]
                                        ],
                                        'left'=>[
                                            'style'=>'SOLID',
                                            'width'=>1,
                                            'color'=>[
                                                'red'=>0,
                                                'green'=>0,
                                                'blue'=>0,
                                                'alpha'=>1,
                                            ]
                                        ]
                                    ]
                                ],
                            ],
                            'fields'=>'*'
                        ]
                    ]),

                    new \Google_Service_Sheets_Request([
                        'repeatCell'=>[
                            'range'=>[
                                'sheetId'=>$sheetId,
                                'startRowIndex'=>'13',
                                'endRowIndex'=>'18',
                                'startColumnIndex'=>'0',
                                'endColumnIndex'=>'1',
                            ],
                            'cell'=>[
                                'userEnteredFormat'=>[
                                    'horizontalAlignment'=>'CENTER',
                                    'backgroundColor'=>[
                                        'red'=>0.74,
                                        'green'=>0.56,
                                        'blue'=>0
                                    ],
                                    'textFormat'=>[
                                        'foregroundColor'=>[
                                            'red'=>'1',
                                            'green'=>'1',
                                            'blue'=>'1'
                                        ],
                                    ],
                                    'borders'=>[
                                        'top'=>[
                                            'style'=>'SOLID',
                                            'width'=>1,
                                            'color'=>[
                                                'red'=>0,
                                                'green'=>0,
                                                'blue'=>0,
                                                'alpha'=>1,
                                            ]
                                        ],
                                        'right'=>[
                                            'style'=>'SOLID',
                                            'width'=>1,
                                            'color'=>[
                                                'red'=>0,
                                                'green'=>0,
                                                'blue'=>0,
                                                'alpha'=>1,
                                            ]
                                        ],
                                        'bottom'=>[
                                            'style'=>'SOLID',
                                            'width'=>1,
                                            'color'=>[
                                                'red'=>0,
                                                'green'=>0,
                                                'blue'=>0,
                                                'alpha'=>1,
                                            ]
                                        ],
                                        'left'=>[
                                            'style'=>'SOLID',
                                            'width'=>1,
                                            'color'=>[
                                                'red'=>0,
                                                'green'=>0,
                                                'blue'=>0,
                                                'alpha'=>1,
                                            ]
                                        ]
                                    ]
                                ],
                            ],
                            'fields'=>'*'
                        ]
                    ]),

                    new \Google_Service_Sheets_Request([
                        'repeatCell'=>[
                            'range'=>[
                                'sheetId'=>$sheetId,
                                'startRowIndex'=>'18',
                                'endRowIndex'=>'21',
                                'startColumnIndex'=>'0',
                                'endColumnIndex'=>'1',
                            ],
                            'cell'=>[
                                'userEnteredFormat'=>[
                                    'horizontalAlignment'=>'CENTER',
                                    'backgroundColor'=>[
                                        'red'=>0.41,
                                        'green'=>0.65,
                                        'blue'=>0.3
                                    ],
                                    'textFormat'=>[
                                        'foregroundColor'=>[
                                            'red'=>'1',
                                            'green'=>'1',
                                            'blue'=>'1'
                                        ],
                                    ],
                                    'borders'=>[
                                        'top'=>[
                                            'style'=>'SOLID',
                                            'width'=>1,
                                            'color'=>[
                                                'red'=>0,
                                                'green'=>0,
                                                'blue'=>0,
                                                'alpha'=>1,
                                            ]
                                        ],
                                        'right'=>[
                                            'style'=>'SOLID',
                                            'width'=>1,
                                            'color'=>[
                                                'red'=>0,
                                                'green'=>0,
                                                'blue'=>0,
                                                'alpha'=>1,
                                            ]
                                        ],
                                        'bottom'=>[
                                            'style'=>'SOLID',
                                            'width'=>1,
                                            'color'=>[
                                                'red'=>0,
                                                'green'=>0,
                                                'blue'=>0,
                                                'alpha'=>1,
                                            ]
                                        ],
                                        'left'=>[
                                            'style'=>'SOLID',
                                            'width'=>1,
                                            'color'=>[
                                                'red'=>0,
                                                'green'=>0,
                                                'blue'=>0,
                                                'alpha'=>1,
                                            ]
                                        ]
                                    ]
                                ],
                            ],
                            'fields'=>'*'
                        ]
                    ]),
                ]
            )
        );
        $response = $service->spreadsheets->batchUpdate($this->getSheetsId(), $batchUpdateRequest);

        $batchUpdateRequest = new \Google_Service_Sheets_BatchUpdateValuesRequest(
            [
                'data'=>[
                    'range'=>$sheetName.'!A1',
                    'values'=>[
                        array(trans('common.basicInfo')),
                        [trans('common.address'), $realestate->address],
                        [trans('common.floor'), $realestate->floor],
                        [trans('common.completed_at'), $realestate->completed_at],
                        [trans('common.exclusiveSize'), $realestate->exclusive_size.'('.$realestate->exclusive_size/3.3.trans('common.p').')'],
                        [trans('common.memo'), $realestate->memo],
                        [trans('common.spend')],
                        [trans('common.tradePrice'), $realestate->earningRate?$realestate->earningRate->price:''],
                        [trans('common.tax'), $realestate->earningRate?$realestate->earningRate->tax:''],
                        [trans('common.mediation_cost'), $realestate->earningRate?$realestate->earningRate->mediation_cost:''],
                        [trans('common.judicial_cost'), $realestate->earningRate?$realestate->earningRate->judicial_cost:''],
                        [trans('common.etc_cost'), $realestate->earningRate?$realestate->earningRate->etc_cost:''],
                        [trans('common.sumTotal'), '=SUM('.$sheetName.'!B8:B12)'],
                        [trans('common.loan')],
                        [trans('common.loanAmount'), $realestate->loan?$realestate->loan->amount:''],
                        [trans('common.loanInterestRate'), $realestate->loan?(float)$realestate->loan->interest_rate/100:''],
                        [trans('common.repayCommission'), $realestate->loan?(float)$realestate->loan->repay_commission/100:''],
                        [trans('common.loanInterestAmount'), '=('.$sheetName.'!B15*'.$sheetName.'!B16)/12'],
                        [trans('common.import')],
                        [trans('common.deposit'), $realestate->earningRate?$realestate->earningRate->deposit:''],
                        [trans('common.monthlyFee'), $realestate->earningRate?$realestate->earningRate->monthlyfee:''],
                        [trans('common.conclusion')],
                        [trans('common.actualInvestment'), '=('.$sheetName.'!B13-'.$sheetName.'!B15-'.$sheetName.'!B20)'],
                        [trans('common.actualEarning'), '=('.$sheetName.'!B21-'.$sheetName.'!B18)'],
                        [trans('common.earningRateYear'), '=('.$sheetName.'!B24*12/'.$sheetName.'!B23)'],
                    ],
                ],
                'valueInputOption'=>'USER_ENTERED'
            ]
        );
        $response = $service->spreadsheets_values->batchUpdate($this->getSheetsId(), $batchUpdateRequest);

        return $response;
    }
    protected function batchUpdate($client){

        $service = new \Google_Service_Sheets($client);
        $batchUpdateRequest = new \Google_Service_Sheets_BatchUpdateSpreadsheetRequest(
            array(
                'requests'=>$this->requests
            )
        );
        $sheetsId = $this->getSheetsId();
        try{
            $response = $service->spreadsheets->batchUpdate($sheetsId, $batchUpdateRequest);
        }catch(\Google_Service_Exception $exception){
            $code = $exception->getCode();
            switch($code){
                case'401':
                    header('Location: '. filter_var(route('auth2.callback'), FILTER_SANITIZE_URL));
                    exit;
                default:
                    /*dd($exception->getErrors());*/
                    return view('errors.googlesheet')->with('errors', $exception->getErrors());

            }
        }

        return $response;
    }
    protected function getSheetsId(){
        try {
            $user = User::findOrFail(auth()->id());
        }catch(\Exception $exception){
            $errors = array(array('message'=>'test'));
            return view('errors.googlesheet')->with('errors', $errors);
        }
        return $user->sheetsId();
    }
}
