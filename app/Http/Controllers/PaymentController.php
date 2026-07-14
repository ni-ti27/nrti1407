<?php

namespace App\Http\Controllers;

use App\Models\action_details;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\SubmitRequest;
use App\Models\application_history;
use XMLWriter;
use App\Models\Taluka;
use App\Models\online_payment_details;
use App\Models\Payment_Details;
use App\Models\Rti_Dept_Id;
use App\Models\RtiApplicantRegdata;
use App\Models\dept_officers_master;
// use Illuminate\Contracts\Session\Session;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Session as FacadesSession;
use DateTime;
use Carbon\Carbon;

class PaymentController extends Controller
{
    public function returnPayment($echallanno)
    {
        $paymentDetail = DB::table('payment_details')->where('echallan_no',$echallanno)->first();

        return view('payment-response',compact('paymentDetail'));
    }
    public function initiatePayment(Request $request)
    {
        // dump($request);
        $reg_no = null;
        $payableAmount = null;
        $echallanNumber = null;
        $additionalPaymentFlag = false;
        $serviceCode = 75;
        if(isset($request['challan_no']))
        {
            $reg_no = $request['reg_no'];
            $payableAmount = $request['addl_payment'];
            $echallanNumber = $request['challan_no'];
        }
        else
        {
            $requestData = json_decode($request['reg_no_details'],true);
            if($requestData != null)
            {
                $reg_no = $requestData['reg_no'];
                $payableAmount = $requestData['addl_payment'];
            }
            else
            {
                if(isset($request->reg_no)){
                    $reg_no = $request->reg_no;
                }
                else{
                    $reg_no = session('data')->reg_no;
                }
            }
        }
        $payableAmount = ($payableAmount == null) ? 10 : $payableAmount;
        $payableAmount = (float) $payableAmount;

        if($payableAmount == 10)
        {
            $additionalPaymentFlag = false;
            $serviceCode = 75;
        }
        else
        {
            $additionalPaymentFlag = true;
            $serviceCode = 310;
        }
        // dd($payableAmount,$additionalPaymentFlag,$serviceCode);

        $query = SubmitRequest::where('reg_no',$reg_no)->first();
        // if ($query->count()<= 0)
        //     throw new Exception('We are Not Found any Transactions', 2);
        // foreach ($query as $row) {
            $user_id = $query->id;
            $name = $query->name;
            $phone = $query->mobile_no;
            $email = $query->email_id;
            $address = $query->address_line1.' '.$query->address_line2.' '.$query->address_line3;
            $pincode = $query->pincode;
            $init_date = now()->format("Y-m-d h:i:s");

            if($query->taluka == 0)
            {
                $taluka = 0;
                $taluka_name = 'Others';
            }
            else
            {
                $taluka = $query->taluka ? Taluka::where('id',$query->taluka)->first(): '-';
                $taluka_name = $taluka ? $taluka->name : '-';
            }

        // }

        $xmlArgs = array(
            "MobileNo" => $phone,
            "PartyName" => $name,
            "PartyAddress" => $address,
            "PartyPin" => $pincode,
            "PartyTaluka" => $taluka_name,
            "ChallanCount" => "1",
            "Demand" => 56,
            // "Head1" => '0070608000100',
            "Head1" => '0070601180100',
            "Amount1" => $payableAmount,
            "TotalAmt" => $payableAmount,
            "IPAddress" => '10.162.2.151',
            "PartyRef" => "DIP",
            "OtherDetails" => "DIP Department",
            "Reason" => "RTI Fees"
        );

        $strUtf8_eChallanReq_xml = $this->getPaymentRequestXML($xmlArgs);
        // dd($strUtf8_eChallanReq_xml);
        // Key file path
        // $keyFilePath = storage_path('fisherie.KEY');
        $keyFilePath = storage_path('infonpub.KEY');

        // Reading the key file
        $encryptionKey = file_get_contents($keyFilePath);

        // Encrypting the XML with the key from the department
        $encrypted_eChallanReq = $this->encrypt($strUtf8_eChallanReq_xml, $encryptionKey);
        // dump($encrypted_eChallanReq);
        // dd(trim($encrypted_eChallanReq));
        $url = 'https://echallanws.goa.gov.in/service.asmx/generateEchallanPDFNew';
        // $url = 'https://10.190.22.7/echallanws/service.asmx/generateEchallanPDFNew';

        $data = [
            'demand' => 56,
            // 'demand' => 66,
            'encrypt_XML_data' =>$encrypted_eChallanReq,
            // 'encrypt_XML_data' => '0YhKQU4nVPvzfY0d3RjxYqqkTEi9uaXlwgag7mJXAcpssbCaMZlrU6pC1U2byD/AfGqe6s0zZbNUEkRRFzkE1AuoSaICjnoYdJTBXedW+a9BjRKy1P92sVRsFcIctSGtSzDdOuvNGPsgo50wxRI2N8VmfouSLMpcfuHKSq8S61ZkewVEOlBuDwatY0X5d00pAly+ZlsN9tkJLYcrUZe4cBYDyNYz4moYdNkfYGzYTLpOCp4Jbp7+rHMFv8453U6Xd1BPFaf3jxbes9c8/v5A+Q/hGTvQ9qGn9lap01yHi03n/RJ6t+40VMcE8otLHL0Xd7e3Vbn9FHTUOYFUCGvV8+f3uDlWYWT/9ZMgqHrLKi9H5/HAtLHVuHwmVvxkn7M9yYtM5DOsqXoiFVK4dcwOJY5tIQv4tJJSL4rFBkF3kpOSrIcCdnYtW6gtuwT3GZPXoDKC/0KdoMvP342JSwWnWIdnaJyadd4VluL3AWPUjLvEgPLtAjjrkWRx5iH8jcenbOV72JKO7wOBn9kqY/3MO2X3nHyuoiR+abV6X/tLIVHt/V4vIK7HcjSs7Ac+g7qiPnk3pIGhVkXG1IvE09FjuUGCoDN7MZOjlhrSEG2sBas+6gu1eBbAnqkYNZED4ESDKcorR2RafgfAHqjFOkU+RlKXyx9CiNsvMLJHBOjgJSE=',
            // 'serviceCode' => 297,
            'serviceCode' => $serviceCode,
        ];
        // dd($data);

        $postfields = http_build_query($data);

        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => $postfields,
            CURLOPT_HTTPHEADER => array(
                'Content-Type: application/x-www-form-urlencoded'
            ),
        ));

        $response = curl_exec($curl);

        // dd($response);
        curl_close($curl);

        $plaineno = $echallanNumber;
        if($echallanNumber=='' || $echallanNumber==0)
        {
            $xml = simplexml_load_string($response);
            $xml->registerXPathNamespace('diffgr', 'urn:schemas-microsoft-com:xml-diffgram-v1');

            // Use XPath to access the diffgr:diffgram element
            $diffgram = $xml->xpath('//diffgr:diffgram');

            foreach ($diffgram[0]->NewDataSet->Table1 as $table1) {
                $errorFlag = (string) $table1->errorflag;
                $eno = (string) $table1->eno;

                $plaineno = $this->decrypt($eno, $encryptionKey);

            }
            if ($plaineno == false || !is_numeric($plaineno)) {
                $plaineno = $echallanNumber;
            }
        }

        $plainPaymentReq = "echallanno=". $plaineno . "|totalamt=" . $payableAmount;
        Session::put('PaymenteChallanNumber', $plaineno);
        // dd($plainPaymentReq);
        $encrypted_paymentReq = $this->encrypt($plainPaymentReq, $encryptionKey);
        // dd($encrypted_paymentReq);
        $decryptedText = $this->decrypt($encrypted_paymentReq, $encryptionKey);
        $totalamt = $payableAmount;
        // dd($plainPaymentReq, $encrypted_paymentReq);

        $precordId = $request['pid'];

        $payment ='';
        if($plaineno)
        {
            $payment = Payment_Details::where('echallan_no', $plaineno)
                            ->where('applicant_id', $user_id)
                            ->whereNull('posted_status')
                            ->orderBy('created_at', 'desc')
                            ->first();
        }

        if ($payment) {

            // Update the existing record
            $payment->update([
                'trans_dt'     => $init_date,
                'reg_no'       => $query->reg_no,
                'total_amt'    => $totalamt,
                'demand_id'    => $data['demand'],
                'party_ref_no' => $pincode,
                'party'        => 'DIP',
                'service_code' => $serviceCode,
            ]);
        } else {
            // Create a new record if none exists
            Payment_Details::create([
                'trans_dt'       => $init_date,
                'applicant_id'   => $user_id,
                'reg_no'         => $query->reg_no,
                'echallan_no'    => $plaineno,
                'total_amt'      => $totalamt,
                'demand_id'      => $data['demand'],
                'party_ref_no'   => $pincode,
                'party'          => 'DIP',
                'service_code'   => $serviceCode,
            ]);
        }
        /* if(Payment_Details::where('id', $user_id)->whereNull('posted_status')->exists()){
            Payment_Details::where('id', $user_id)
                            ->update([
                                    'trans_dt' => $init_date,
                                    'applicant_id' => $user_id,
                                    'echallan_no' => $plaineno,
                                    'reg_no' => $query->reg_no,
                                    'total_amt' => $totalamt,
                                    'demand_id' => $data['demand'],
                                    // 'amount' => $totalamt,
                                    'party_ref_no' => $pincode,
                                    'party' => 'DIP',
                                    'service_code' => $serviceCode,
                            ]);
        }else{
            $payment = new Payment_Details;
            $payment->trans_dt= $init_date;
            $payment->reg_no= $query->reg_no;
            $payment->applicant_id= $user_id;
            $payment->echallan_no= $plaineno;
            $payment->total_amt= $totalamt;
            $payment->demand_id= $data['demand'];
            // $payment->amount= $totalamt;
            $payment->party_ref_no= $pincode;
            $payment->party= 'DIP';
            $payment->service_code = $serviceCode;
            $payment->save();
        }*/

        // $payment = PaymentDetail::where('trans_id', $user_id)
        //                         ->update([
        //                             'trans_dt' => $init_date,
        //                             'trans_id' => $user_id,
        //                             'echallan_no' => $plaineno,
        //                         ]);

        $encdata = htmlentities($encrypted_paymentReq);
        $echallanno  = $plaineno;
        $totalAmt = $payableAmount;
        // dd($echallanno);
        // $token_hr= $_SESSION['Token_gbl'];
         return view('payment.payment_echallan', compact('encdata','echallanno','encrypted_paymentReq','totalAmt','name'));
    }

    function getPaymentRequestXML($xmlArgs)
    {
        $xmlWriter = new XMLWriter();
        $xmlWriter->openMemory();
        // $xmlWriter->setIndent(true);
        $xmlWriter->startDocument('1.0', 'UTF-8', 'yes');
        $xmlWriter->startElement('USERDATA');
        $xmlWriter->writeElement('MobileNo', $xmlArgs['MobileNo']);
        $xmlWriter->writeElement('ChallanCount', $xmlArgs['ChallanCount']);
        $xmlWriter->writeElement('Demand', $xmlArgs['Demand']);
        $xmlWriter->writeElement('Head1', $xmlArgs['Head1']);
        $xmlWriter->writeElement('Amount1', $xmlArgs['Amount1']);
        $xmlWriter->writeElement('TotalAmt', $xmlArgs['TotalAmt']);
        $xmlWriter->writeElement('IPAddress', $xmlArgs['IPAddress']);
        $xmlWriter->writeElement('PartyName', $xmlArgs['PartyName']);
        $xmlWriter->writeElement('PartyAddress', $xmlArgs['PartyAddress']);
        $xmlWriter->writeElement('PartyPin', $xmlArgs['PartyPin']);
        $xmlWriter->writeElement('PartyTaluka', $xmlArgs['PartyTaluka']);
        $xmlWriter->writeElement('PartyRef', $xmlArgs['PartyRef']);
        $xmlWriter->writeElement('OtherDetails', $xmlArgs['OtherDetails']);
        $xmlWriter->writeElement('Reason', $xmlArgs['Reason']);
        // $lastError = libxml_get_last_error();
        // var_dump($lastError);
        $xmlWriter->endElement();
        $xmlWriter->endDocument();
        // $status = $xmlWriter->flush();
        // var_dump($status);

        $strUtf8_Req_xml = $xmlWriter->outputMemory();
        return $strUtf8_Req_xml;
        // echo 'XML Content: ' . htmlspecialchars($strUtf8_Req_xml);
    }

    function encrypt($textToEncrypt, $key)
    {
        $cipher = 'aes-128-cbc';

        // Pad the key if necessary
        $key = str_pad($key, 16, "\0");
        $iv = $key;
        // die;
        $encryptedText = openssl_encrypt($textToEncrypt, $cipher, $key, OPENSSL_RAW_DATA, $iv);
        $result = base64_encode($encryptedText);

        return $result;
    }
    // function encrypt($textToEncrypt, $key)
    // {
    //     $cipher = 'aes-128-cbc';

    //     // Generate a random IV
    //     $iv = openssl_random_pseudo_bytes(openssl_cipher_iv_length($cipher));

    //     // Pad the key if necessary
    //     $key = str_pad($key, 16, "\0");

    //     $encryptedText = openssl_encrypt($textToEncrypt, $cipher, $key, OPENSSL_RAW_DATA, $iv);
    //     $result = base64_encode($iv . $encryptedText);

    //     return $result;
    // }

    function decrypt($textToDecrypt, $key)
    {
        $cipher = 'aes-128-cbc';
        $key = str_pad($key, 16, "\0");
        $iv = $key;
        $encryptedText = base64_decode($textToDecrypt);
        if ($encryptedText === false) {
            // Handle decoding error
            echo 'Invalid base64 string';
        } else {

            $decryptedText = openssl_decrypt($encryptedText, $cipher, $key, OPENSSL_RAW_DATA, $iv);
        }

        return $decryptedText;
    }

    function getPaymentVerifyRequestXML($xmlArgs)
    {
        $xmlWriter = new XMLWriter();
        $xmlWriter->openMemory();
        // $xmlWriter->setIndent(true);
        $xmlWriter->startDocument('1.0', 'UTF-8', 'yes');
        $xmlWriter->startElement('Echallan');
        $xmlWriter->writeElement('eChallanNo', $xmlArgs['EchallanNum']);
        $xmlWriter->endElement();
        $xmlWriter->endDocument();
        $strUtf8_Req_xml = $xmlWriter->outputMemory();
        return $strUtf8_Req_xml;
    }

    public function paymentVerify(Request $request,$id)  {
//DD("HI");
        $query = DB::table('submit_requests as sr')
        ->select('sr.id', 'sr.mobile_no as phone','sr.payment_details','sr.name as name','sr.mobile_no as phone','sr.email_id as email','sr.reg_no as reg_no','sr.pincode as pincode','sr.applied_dept','sr.applied_subdept','sr.applied_office','pd.applicant_id as applicant_id','pd.trans_dt','pd.echallan_no as echallan_no','pd.total_amt','pd.id as transaction_id','pd.service_code','pd.status as payment_status')
        ->join('payment__details as pd', 'sr.reg_no' ,'=', 'pd.reg_no')
        ->where('pd.id',$id)
        ->orderby('pd.trans_dt')
        ->first();
        //dd($query);
        // dd($query->toRawSql());
        $status_desc ='-';
        // $echallanno = 202300929824; //Success
        // $echallanno = 202300925761; //Failed
        // $echallanno = 202300921231; //Failed
        $xmlArgs = array(
            "EchallanNum" => $query->echallan_no
        );

        if($query->payment_status == 'Failed' || $query->payment_status == 'Success')
        {
            $cin = $query->echallan_no;
            $amount = $query->total_amt;
            $amount = $query->total_amt;
            $payment_date = $query->trans_dt;
            $status = $query->payment_details;

            if($status == 'Failed')
                $status_code = 'F';
            else if($status == 'Success')
                $status_code = 'S';
            else if($status == 'Pending')
                $status_code = 'P';

            $amount = $query->total_amt;
            $amount = $query->total_amt;
            $newRegNumber = $query->reg_no;

            if($query->service_code == 75)
                $additionalPaymentFlag = false;
            else if($query->service_code == 310)
                $additionalPaymentFlag = true;
            else
                $additionalPaymentFlag = null;

            $deptNum = str_pad($query->applied_dept, 2, '0', STR_PAD_LEFT) . '' . str_pad($query->applied_subdept, 2, '0', STR_PAD_LEFT) . '' . str_pad($query->applied_office, 2, '0', STR_PAD_LEFT);
            $departmentInfo = Rti_Dept_Id::where('dept_id', $deptNum)->first();

            $paidDetails = SubmitRequest::join('payment__details as pd', 'submit_requests.reg_no','=','pd.reg_no')
            ->where('submit_requests.reg_no', $query->reg_no)->where('pd.payment_verified','S')
            ->get();
            // dump('redirecting from here ');

            //dd($status_code);
            return view('payment.payment-verify', compact('cin','amount','payment_date','status_code','status','status_desc','id','query', 'departmentInfo','newRegNumber','paidDetails','additionalPaymentFlag'));
        }

        $status_verify_xml = $this->getPaymentVerifyRequestXML($xmlArgs);
        // $keyFilePath = storage_path('fisherie.KEY');
        $keyFilePath = storage_path('infonpub.KEY');

        // Reading the key file
        $encryptionKey = file_get_contents($keyFilePath);

        $encrypted_verify_req = $this->encrypt($status_verify_xml, $encryptionKey);
        // echo 'XML Content:' . htmlspecialchars($status_verify_xml) . "<br>";die;

        //dd($encrypted_verify_req);
        $url = 'https://echallanws.goa.gov.in/service.asmx/eChallanPaymentStatus';
        $data = array(
            'demandno' => 56,
            'encryptedEchallanno' => $encrypted_verify_req
        );
        $postfields = http_build_query($data);
        // dd($data);
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => $postfields,
            CURLOPT_HTTPHEADER => array(
                'Content-Type: application/x-www-form-urlencoded'
            ),
        ));

        $departmentInfo = null;
        $response = curl_exec($curl);
        // dd($response);
        curl_close($curl);
        $xml = simplexml_load_string($response);
        // echo 'XML Content:' . htmlspecialchars($xml) . "<br>";

        $stringContent = (string)$xml;
        $res_arr = explode("|", $stringContent);

        $deptNum = str_pad($query->applied_dept, 2, '0', STR_PAD_LEFT) . '' . str_pad($query->applied_subdept, 2, '0', STR_PAD_LEFT) . '' . str_pad($query->applied_office, 2, '0', STR_PAD_LEFT);
        $departmentInfo = Rti_Dept_Id::where('dept_id', $deptNum)->first();

        $newRegNumber = $query->reg_no;
        $paidDetails = null;
        $additionalPaymentFlag = false;

        if ($res_arr[0] == 'N') {

            $decryptedText = $this->decrypt($res_arr[2], $encryptionKey);
            // echo 'XML Content:' . htmlspecialchars($decryptedText) . "<br>";die;

            $xml = simplexml_load_string($decryptedText);
            // dd($xml, $query, $decryptedText,$res_arr[2]);

            $bank_ref = (string) $xml->sbiReferenceNo;
            $cin = (string) $xml->echallanNo;
            $amount = (string) $xml->totalAmount;
            $payment_date = (string) $xml->bankReceiveDate;
            /*if($paidDetails != '')
            {
                $payment_date_string=DateTime::createFromFormat('d/m/Y h:i:s A',$payment_date);
                $payment_date_new=$payment_date_string->format('Y-m-d');
                $payment_date=''.$payment_date_new;
            }
            else
            {

                $payment_date = Carbon::now();
                $payment_date->setTimezone('Asia/Kolkata');
                $payment_date_string = (string)$payment_date->format('Y-m-d H:i:s');
            }*/

            $payment_date_obj = DateTime::createFromFormat('d/m/Y h:i:s A', $payment_date);

            if ($payment_date_obj === false) {
                $payment_date_obj = new DateTime('0001-01-01 00:00:00');
            }

            $payment_date = $payment_date_obj->format('Y-m-d');
            $status_code = (string) $xml->status;

            if ($status_code == 'S') {
                $status = 'Success';

                $paymentDetails = new online_payment_details();
                $paymentDetails->applicant_id = $query->id;
                $paymentDetails->reg_no = $query->reg_no;
                $paymentDetails->demand_id = 56;
                $paymentDetails->echallan_no = $cin;
                // $paymentDetails->echallangeneratedon = $query->trans_dt;
                // $paymentDetails->fee_amt = $amount;
                $paymentDetails->total_amt = $amount;
                $paymentDetails->paystat_response_xml = $decryptedText;
                $paymentDetails->status = $status;
                // $paymentDetails->nicencdata = $$res_arr[2];
                $paymentDetails->bank_ref_no = $bank_ref;
                $paymentDetails->bank_rcvd_date = $query->trans_dt;
                // $paymentDetails->treasury_rcvd_date = $payment_date;
                $paymentDetails->payment_verified = $status_code;
                $paymentDetails->payment_verified_date = $payment_date;
                $paymentDetails->updated_by = now(); // Assuming 'updated_date' is a timestamp field
                // dd($paymentDetails);
                $paymentDetails->save();

                $feesfullypaid = 'Y';

                Payment_Details::where('id',$query->transaction_id)
                                ->update([
                                    'bank_recv_dt' => $query->trans_dt,
                                    'bank_recv_tm' => now(),
                                    'party_ref_no' => 'DIP',
                                    'paystat_response_xml' => $decryptedText,
                                    'demand_id' => 56,
                                    'total_amt' => $amount,
                                    'sbi_ref' => $bank_ref,
                                    'bank_ref_no' => $bank_ref,
                                    'bank_rcvd_date' => $query->trans_dt,
                                    'posted_status' => $feesfullypaid,
                                    'posted_date' => $payment_date,
                                    'status' => $status,
                                    'payment_verified' => $status_code,
                                    'payment_verified_date' => $payment_date,
                                    'updated_by' => now(),
                                ]);

                $updateCount = SubmitRequest::where('id', $query->id)->update(['payment_details' => $status, 'created_at'=> now()]);

                if ($updateCount > 0) {
                    $updatedRequest = SubmitRequest::find($query->id);
                    if ($updatedRequest) {
                        if ($departmentInfo) {
                            if($query->service_code == 75)
                            {
                                $nextSequenceValue = DB::select("SELECT NEXTVAL('main_id')")[0]->nextval;
                                $newRegNumber = $updatedRequest->genRegNumber($departmentInfo->pubauthcode, date('m').date('y'), $nextSequenceValue);

                                $updatedRequest->update([
                                    'reg_no' => $newRegNumber
                                ]);

                                $rtiApplicantRegDataUpdate = RtiApplicantRegdata::where('reg_no', $query->reg_no)->update(['reg_no' => $newRegNumber]);

                                $actionDetailsData = action_details::where('reg_no', $query->id)->first();

                                if ($actionDetailsData) {
                                    $actionDetailsData->reg_no = $newRegNumber;
                                    $actionDetailsData->save();
                                }

                                $dataToInsert = [
                                    'reg_no' => $newRegNumber,
                                    'dept_id' => $deptNum,
                                ];

                                $historyData = [
                                    'reg_no' => $newRegNumber,
                                    'changes' => 'RTI APPLICATION SUBMITTED',
                                    'history_field' => json_encode($dataToInsert),
                                    'action_details_id' => $actionDetailsData->id,
                                    'current_dept_id' => $deptNum,
                                ];

                                application_history::create($historyData);

                                Payment_Details::where('id',$query->transaction_id)
                                -> update(['reg_no' => $newRegNumber]);

                                $deptOfficer2Data = dept_officers_master::where('id', $actionDetailsData->assigned_off2)
                                ->where('is_active', 1)->first();

                                $roleName = null;
                                if($deptOfficer2Data->role == 2)
                                    $roleName = 'PIO';
                                else if($deptOfficer2Data->role == 3)
                                    $roleName = 'APIO';

                                // sms for PIO / APIO
                                $var1 ="Sir/Madam";
                                $var2 = "New Request";
                                $var3 = "been received and assigned against your ".$roleName." ID";

                                $pioMsg = "Dear ".$var1.", RTI ".$var2." registration number ".$newRegNumber." has ".$var3." - Dept. of Information and Publicity";
                                $this->_sendSms($deptOfficer2Data->mobile_no,$pioMsg);

                                // sms for Applicant
                                $msg = "Your RTI New Request has been filed vide registration number ".$newRegNumber." - Dept. of Information and Publicity";
                                $this->_sendSms(FacadesSession::get('mobile_no'),$msg);
                            }
                            else if($query->service_code == 310)
                            {
                                $additionalPaymentFlag = true;
                                $newRegNumber = $query->reg_no;

                                $actionDetailsData = action_details::where('reg_no',$newRegNumber)->first();
                                if ($actionDetailsData) {
                                    $actionDetailsData->status_id = 10;
                                    $actionDetailsData->save();
                                }

                                $dataToInsert = [
                                    'reg_no' => $newRegNumber,
                                    'paid_amt' => $amount,
                                    'payment_details_id' => $query->transaction_id,
                                    'dept_id' => $deptNum,
                                ];

                                $historyData = [
                                    'reg_no' => $newRegNumber,
                                    'changes' => 'ADDITIONAL PAYMENT PAID',
                                    'history_field' => json_encode($dataToInsert),
                                    'action_details_id' => $actionDetailsData->id,
                                    'current_dept_id' => $deptNum,
                                ];

                                application_history::create($historyData);

                                $deptOfficer2Data = dept_officers_master::where('id', $actionDetailsData->assigned_off2)
                                ->where('is_active', 1)->first();

                                // sms for PIO / APIO
                                $var1 ="Sir/Madam";
                                $var2 = "Additional Payment for";
                                $var3 = "been received and updated";

                                $pioMsg = "Dear ".$var1.", RTI ".$var2." registration number ".$newRegNumber." has ".$var3." - Dept. of Information and Publicity";
                                $this->_sendSms($deptOfficer2Data->mobile_no,$pioMsg);

                                // sms for Applicant
                                $msg = "Your Additional Payment has been paid for registration number ".$newRegNumber." - Dept. of Information and Publicity";
                                $this->_sendSms(FacadesSession::get('mobile_no'),$msg);
                            }
                        }
                    }
                }
                $paidDetails = SubmitRequest::join('payment__details as pd', 'submit_requests.reg_no','=','pd.reg_no')
                ->where('submit_requests.reg_no', $newRegNumber)->where('pd.payment_verified','S')
                ->get();

            } else if($status_code == 'F') {

                $status = 'Failed';
                $status_code = 'F';


                $paymentDetails = new online_payment_details();
                $paymentDetails->applicant_id = $query->id;
                // $paymentDetails->onlinepayment_id = $query->transaction_id;
                $paymentDetails->reg_no = $query->reg_no;
                $paymentDetails->demand_id = 56;
                $paymentDetails->echallan_no = $cin;
                // $paymentDetails->echallangeneratedon = $query->trans_dt;
                // $paymentDetails->fee_amt = $amount;
                $paymentDetails->total_amt = $amount;
                $paymentDetails->paystat_response_xml = $decryptedText;
                $paymentDetails->status = $status;
                // $paymentDetails->nicencdata = $$res_arr[2];
                $paymentDetails->bank_ref_no = $bank_ref;
                $paymentDetails->bank_rcvd_date = $query->trans_dt;
                // $paymentDetails->treasury_rcvd_date = $payment_date;
                $paymentDetails->payment_verified = $status_code;
                $paymentDetails->payment_verified_date = $payment_date;
                $paymentDetails->updated_by = now(); // Assuming 'updated_date' is a timestamp field

                $paymentDetails->save();

                $feesfullypaid = 'N';

                SubmitRequest::where('id',$query->id)->update(['payment_details' => $status]);

                Payment_Details::where('id',$query->transaction_id)
                ->update([
                    'demand_id' => 56,
                    'total_amt' => $amount,
                    'posted_status' => $feesfullypaid,
                    'posted_date' => $payment_date,
                    'status' => $status,
                    'payment_verified' => $status_code,
                    'updated_by' => now(),
                ]);

            }else if($status_code == 'P' || $status_code == 'G' || $status_code == null) {

                $status = 'Pending';
                $status_code = 'P';

                $paymentDetails = online_payment_details::where('applicant_id', $query->id)
                ->where('echallan_no', $cin)
                ->latest('updated_at')
                ->first();

                if ($paymentDetails) {

                    $paymentDetails->echallan_no = $cin;
                    $paymentDetails->reg_no = $query->reg_no;
                    $paymentDetails->demand_id = 56;
                    $paymentDetails->total_amt = $amount;
                    $paymentDetails->paystat_response_xml = $decryptedText;
                    $paymentDetails->status = $status;
                    $paymentDetails->bank_ref_no = $bank_ref;
                    $paymentDetails->bank_rcvd_date = $query->trans_dt;
                    $paymentDetails->payment_verified = $status_code;
                    $paymentDetails->payment_verified_date = $payment_date;
                    $paymentDetails->updated_by = now();
                    $paymentDetails->save();

                } else {

                    $paymentDetails = new online_payment_details();
                    $paymentDetails->applicant_id = $query->id;
                    // $paymentDetails->onlinepayment_id = $query->transaction_id;
                    $paymentDetails->reg_no = $query->reg_no;
                    $paymentDetails->demand_id = 56;
                    $paymentDetails->echallan_no = $cin;
                    // $paymentDetails->echallangeneratedon = $query->trans_dt;
                    // $paymentDetails->fee_amt = $amount;
                    $paymentDetails->total_amt = $amount;
                    $paymentDetails->paystat_response_xml = $decryptedText;
                    $paymentDetails->status = $status;
                    // $paymentDetails->nicencdata = $$res_arr[2];
                    $paymentDetails->bank_ref_no = $bank_ref;
                    $paymentDetails->bank_rcvd_date = $query->trans_dt;
                    // $paymentDetails->treasury_rcvd_date = $payment_date;
                    $paymentDetails->payment_verified = $status_code;
                    $paymentDetails->payment_verified_date = $payment_date;
                    $paymentDetails->updated_by = now(); // Assuming 'updated_date' is a timestamp field

                    $paymentDetails->save();
                }


                $feesfullypaid = 'P';

                SubmitRequest::where('id',$query->id)->update(['payment_details' => $status]);

                Payment_Details::where('id',$query->transaction_id)
                ->update([
                    'demand_id' => 56,
                    'total_amt' => $amount,
                    'posted_status' => $feesfullypaid,
                    'posted_date' => $payment_date,
                    'status' => $status,
                    'payment_verified' => $status_code,
                    'updated_by' => now(),
                ]);

            }
        } elseif ($res_arr[0] == 'Y') {

            $status = 'Failed';
            $status_desc = $res_arr[1];

            //dd($status_desc);
            $status_code = 'F';
            // $cin = 0;
            // $amount = 10;
            // $payment_date = '01/01/2024';

            $cin = $query->echallan_no == null ? 0 : $query->echallan_no;
            $amount = $query->total_amt;
            $payment_date = $query->trans_dt;
        }
        if(isset($payment_date_string))
        {
            $payment_date = $payment_date_string;
        }

        // dd($request->all());
        if ($request->bsubmit == 'Pay Again') {
            return view('payment.pay-again');
        }

        // dd($cin,$amount,$payment_date,$status_code,$status,$status_desc);
        return view('payment.payment-verify', compact('cin','amount','payment_date','status_code','status','status_desc','id','query', 'departmentInfo','newRegNumber','paidDetails','additionalPaymentFlag'));

    }

    private function _sendSms($mobile,$msg)
    {
        Artisan::call('app:send-sms', [
            'mobile' => $mobile,
            'message' => $msg,
        ]);

        $output = Artisan::output();

        // dd($output);

        return response()->json(['Status' => 200, 'message' => "Sms Sent Successfully"]);
    }

    public function paymentReturn(Request $request)
    {
        $nicencdata = '6XHoj6rjaiBDb4zGcMJFOOhMp43GappYcAe0+zM5G4w5JqxikeoyARN+XBSg49hR';
        // $nicencdata = '6XHoj6rjaiBDb4zGcMJFOAb9Bbu/Yj3QVTrCxJ7d00Q4RrkgS2paVVnS1XKvpmCE';
        if (isset($request->nicencdata)) {
            $nicencdata = $request->nicencdata;
            $keyFilePath = storage_path('infonpub.KEY');
        // $keyFilePath = storage_path('fisherie.KEY');
            $encryptionKey = file_get_contents($keyFilePath);

            $decryptedText = $this->decrypt($nicencdata, $encryptionKey);
            // echo $decryptedText;die;

            $res_arr = explode("|", $decryptedText);
            $res_arr1 = explode("=", $res_arr[0]);
            $echallanno = $res_arr1[1];
            $res_arr2 = explode("=", $res_arr[1]);
            $status_code = $res_arr2[1];
            // dd($res_arr,$res_arr1,$echallanno,$res_arr2,$status_code);
        }


        return redirect()->route('payment-verify',compact('reg_no','appl_id','user_id','transaction_id'));
        // return view('payment.payment-verify', compact('reg_no','appl_id','user_id','transaction_id'));

    }

    public function paymentPending(Request $request) {
        $reg_no = $request->reg_no ? $request->reg_no: '1';
        $appln_reg_id =$request->id_appl_reg ? $request->id_appl_reg : '1';

        if (!empty($reg_no)) {
            $reg_no = $reg_no;//killstring($reg_no);
            $_SESSION['reg_no'] = $reg_no;//killstring($reg_no);
        } else
            $reg_no = $_SESSION['reg_no'];//killstring($_SESSION['reg_no']);

        if (!empty($appln_reg_id)) {
            $appln_reg_id = $appln_reg_id;//killstring($appln_reg_id);
            $_SESSION['id_appl_reg'] = $appln_reg_id;//killstring($appln_reg_id);
        } else
            $appln_reg_id = $_SESSION['id_appl_reg'];//killstring($_SESSION['id_appl_reg']);

        // $statecode_sn =  $_SESSION['state_code'] ? $_SESSION['state_code'] : '1';//killstring($_SESSION['state_code']);
        $date_form = date('d-m-Y');
        $statecode_sn ='-';
        $sqlpay2 = [];
        // $commonFunction = new CommonFunction; // Assuming CommonFunction is a class you have

        $sll = 1;

        return view('payment.payment-pending', compact('reg_no','appln_reg_id','statecode_sn','date_form','sqlpay2','sll'));
    }


    public function ga_sms($mobile, $message)
    {
        $curl = curl_init();

        // $url = 'https://japi.instaalerts.zone/httpapi/QueryStringReceiver?ver=1.0&key=EN3ZlHr62D9xhws1j0Wx1g==&dest=' . $mobile . '&send=GOADOF&text=' . rawurlencode($message);     // working with old senderId & key
        $url = 'https://japi.instaalerts.zone/httpapi/QueryStringReceiver?ver=1.0&key=yyBY673SB67Tz4R4joGgRA==&dest=' . $mobile . '&send=GOADIP&text=' . rawurlencode($message);    // this is not working with current provided senderId & key
        curl_setopt_array($curl, array(
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'GET',
        ));

        $response = curl_exec($curl);


        curl_close($curl);

        $res = explode('&', $response)[1];

        if (explode('=', $res)[1] == 200)
            return true;
        return false;

        // echo $response;

    }

    public function sms(){
        $otp = 111111;
        $mobile = 7559144476;

        // $message = 'OTP for '.$otp.' Registration is '.$mobile.' - Directorate of Fisheries'; //Working
        // $message = 'Your RTI First Appeal has been filed vide registration number '.$mobile.', DIP Goa.';
        $message = 'Dear Sir/Madam, Your RTI First Appeal has been filed vide registration number '.$mobile.', DIP Goa.';

        $this->ga_sms($mobile,$message);
        return true;

    }

    public function handleRedirect(Request $request)
    {
        $echallanNumber = ''; // take from response of the payment
        $submitRequest = session('data');
        // dump(session('PaymenteChallanNumber'));
        $echallanNumber = session('PaymenteChallanNumber');
        Session::forget('PaymenteChallanNumber');

        // dump($submitRequest);
        // dump($submitRequest['id']);
        // $paymentId = Payment_Details::where('applicant_id', $submitRequest['id'])->pluck('id')->orderby('id','desc')->first();

        $paymentId = Payment_Details::where('echallan_no', $echallanNumber)->orderBy('id', 'desc')->pluck('id')->first();

        // dd($paymentId);

        // local
        // $url = "http://127.0.0.1:8000/payment-verify/{$paymentId}";

        // staging
        // $url = "http://10.155.4.170:8000/payment-verify/{$paymentId}";

        // live
        $url = "https://rtionline.goa.gov.in/payment-verify/{$paymentId}";
        return redirect($url);

    }
}
