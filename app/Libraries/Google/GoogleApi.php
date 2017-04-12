<?php

/**
 * Created by PhpStorm.
 * User: ruudnike
 * Date: 2017-03-28
 * Time: 오후 1:35
 */
namespace App\Libraries\Google;
use App\User;
use Google_Client;

class GoogleApi
{
    private $auth_path;
    private $callback;
    private $scope;
    private $client;

    public function init($auth_path, $callback, $scope){
        session_start();
        $this->auth_path = $auth_path;
        $this->callback = $callback;
        $this->scope = $scope;
        return $this;
    }
    public function revoke(){
        return $this->client->revokeToken();
    }
    public function createSpreadSheets($title){
        $service = new \Google_Service_Sheets($this->client);
        $sheets = new \Google_Service_Sheets_Spreadsheet();
        $properties = new \Google_Service_Sheets_SpreadsheetProperties();
        $properties->setTitle($title);
        $sheets->setProperties($properties);

        try{
            $sheets = $service->spreadsheets->create($sheets);
        }catch(\Exception $exception){
            header('Location: '. filter_var($this->callback, FILTER_SANITIZE_URL));
            exit;
        }
        return $sheets;
    }
    public function addSheet($sheetsId, $title){
        $requests = array();
        $requests[] = new \Google_Service_Sheets_Request(array(
                'addSheet'=>array(
                    'properties'=> array(
                        'title'=>$title
                    )
                )
            )
        );

        $response = $this->sheetsBatchUpdate($sheetsId, $requests);
        return $response;
    }
    public function updateSheetName($id, $title){
        $requests = array();
        $requests[] = new \Google_Service_Sheets_Request(array(
                'UpdateSheetPropertiesRequest'=>array(
                    'sheetId'=>$id,
                    'title'=>$title,
                )
            )
        );

        $response = $this->sheetsBatchUpdate($requests);
        return $response;
    }
    private function sheetsBatchUpdate($sheetsId, $requests){
        $service = new \Google_Service_Sheets($this->client);
        $batchUpdateRequest = new \Google_Service_Sheets_BatchUpdateSpreadsheetRequest(
            array(
                'requests'=>$requests
            )
        );
        try{
            $response = $service->spreadsheets->batchUpdate($sheetsId, $batchUpdateRequest);
        }catch(\Google_Service_Exception $exception){
            $code = $exception->getCode();
            switch($code){
                case'401':
                    header('Location: '. filter_var($this->callback, FILTER_SANITIZE_URL));
                    exit;
                default:
                    return $exception->getErrors();

            }
        }

        return $response;
    }
    public function auth(){
        $this->client = new Google_Client();
        $this->client->setAuthConfig($this->auth_path);
        $this->client->addScope($this->scope);

        $access_token = request()->session()->get('access_token');
        request()->session()->set('redirect', url()->current());

        if(isset($access_token) && $access_token){

            $this->client->setAccessToken($access_token);
            return $this;
        }else{
            header('Location: '. filter_var($this->callback, FILTER_SANITIZE_URL));
            exit;
        }

        return $this;
    }
    public function authCallback(){
        $this->client = new Google_Client();
        $this->client->setAuthConfig($this->auth_path);
        $this->client->setRedirectUri($this->callback);
        $this->client->addScope($this->scope);

        if(empty(request()->input('code'))){
            $auth_url = $this->client->createAuthUrl();
            header('Location: '. filter_var($auth_url, FILTER_SANITIZE_URL));
        }else{
            $this->client->authenticate(request()->input('code'));
            request()->session()->set('access_token', $this->client->getAccessToken());
            $redirect = request()->session()->get('redirect');
            request()->session()->set('redirect', NULL);
            /*if(isset($redirect)){
                session_abort();
                header('Location: '. filter_var($redirect, FILTER_SANITIZE_URL));
                exit;
            }*/
        }
    }

    private function border($style = 'SOLID', $width = 1, $color = ['red'=>0, 'green'=>0, 'blue'=>0]){
        $border = [
            'style'=>$style,
            'width'=>$width,
            'color'=>$color,
        ];
        return $border;
    }
    private function borders($style = 'SOLID', $width = 1, $color = ['red'=>0, 'green'=>0, 'blue'=>0]){
        $borders = [
            'top'=>$this->border($style, $width, $color),
            'right'=>$this->border($style, $width, $color),
            'bottom'=>$this->border($style, $width, $color),
            'left'=>$this->border($style, $width, $color),
        ];

        return $borders;
    }
    private function range($sheetId, $startRowIndex = 0, $endRowIndex = 0, $startColumnIndex = 0, $endColumnIndex = 0){
        $range = [
            'sheetId'=>$sheetId,
            'startRowIndex'=>$startRowIndex,
            'endRowIndex'=>$endRowIndex,
            'startColumnIndex'=>$startColumnIndex,
            'endColumnIndex'=>$endColumnIndex,
        ];

        return $range;
    }

    public function addRealEstate($sheetsId, $sheetName, $realestate){
        $sheetName = "'".$sheetName."'";
        $service = new \Google_Service_Sheets($this->client);
        $response = $service->spreadsheets->get($sheetsId, array('includeGridData'=>true));
        $sheets = $response->getSheets();
        $sheetId = $sheets[count($sheets)-1]->getProperties()->getSheetId();
        $batchUpdateRequest = new \Google_Service_Sheets_BatchUpdateSpreadsheetRequest(
            array(
                'requests'=>[
                    new \Google_Service_Sheets_Request([
                        'repeatCell'=>[
                            'range'=>$this->range($sheetId, 0, 25, 0, 2),
                            'cell'=>[
                                'userEnteredFormat'=>[
                                    'borders'=>$this->borders()
                                ]
                            ],
                            'fields'=>'*'
                        ]
                    ]),
                    new \Google_Service_Sheets_Request([
                        'repeatCell'=>[
                            'range'=>$this->range($sheetId, 15, 17, 1, 2),
                            'cell'=>[
                                'userEnteredFormat'=>[
                                    'numberFormat'=>[
                                        'type'=>'PERCENT',
                                    ],
                                    'borders'=>$this->borders()
                                ]
                            ],
                            'fields'=>'*'
                        ]
                    ]),
                    new \Google_Service_Sheets_Request([
                        'repeatCell'=>[
                            'range'=>$this->range($sheetId, 24, 25, 1, 2),
                            'cell'=>[
                                'userEnteredFormat'=>[
                                    'numberFormat'=>[
                                        'type'=>'NUMBER',
                                        'pattern'=>'00.00%'
                                    ],
                                    'borders'=>$this->borders()
                                ]
                            ],
                            'fields'=>'*'
                        ]
                    ]),
                    new \Google_Service_Sheets_Request([
                        'mergeCells'=>[
                            'range'=>$this->range($sheetId, 0, 1, 0, 2),
                            'mergeType'=>'MERGE_ALL'
                        ]
                    ]),
                    new \Google_Service_Sheets_Request([
                        'mergeCells'=>[
                            'range'=>$this->range($sheetId, 6, 7, 0, 2),
                            'mergeType'=>'MERGE_ALL'
                        ]
                    ]),
                    new \Google_Service_Sheets_Request([
                        'mergeCells'=>[
                            'range'=>$this->range($sheetId, 13, 14, 0, 2),
                            'mergeType'=>'MERGE_ALL'
                        ]
                    ]),
                    new \Google_Service_Sheets_Request([
                        'mergeCells'=>[
                            'range'=>$this->range($sheetId, 18, 19, 0, 2),
                            'mergeType'=>'MERGE_ALL'
                        ]
                    ]),
                    new \Google_Service_Sheets_Request([
                        'mergeCells'=>[
                            'range'=>$this->range($sheetId, 21, 22, 0, 2),
                            'mergeType'=>'MERGE_ALL'
                        ]
                    ]),


                    new \Google_Service_Sheets_Request([
                        'repeatCell'=>[
                            'range'=>$this->range($sheetId, 0, 6, 0, 1),
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
                                    'borders'=>$this->borders()
                                ],
                            ],
                            'fields'=>'*'
                        ]
                    ]),

                    new \Google_Service_Sheets_Request([
                        'repeatCell'=>[
                            'range'=>$this->range($sheetId, 6, 13, 0, 1),
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
                                    'borders'=>$this->borders()
                                ],
                            ],
                            'fields'=>'*'
                        ]
                    ]),

                    new \Google_Service_Sheets_Request([
                        'repeatCell'=>[
                            'range'=>$this->range($sheetId, 13, 18, 0, 1),
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
                                    'borders'=>$this->borders()
                                ],
                            ],
                            'fields'=>'*'
                        ]
                    ]),

                    new \Google_Service_Sheets_Request([
                        'repeatCell'=>[
                            'range'=>$this->range($sheetId, 18, 21, 0, 1),
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
                                    'borders'=>$this->borders()
                                ],
                            ],
                            'fields'=>'*'
                        ]
                    ]),
                ]
            )
        );
        $response = $service->spreadsheets->batchUpdate($sheetsId, $batchUpdateRequest);

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
        $response = $service->spreadsheets_values->batchUpdate($sheetsId, $batchUpdateRequest);

        return $response;
    }
}