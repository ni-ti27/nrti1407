<!DOCTYPE html>
<html>
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Contact Us</title>
    <link href="{{ asset('css/bootstrap.min.css') }}" rel="stylesheet">
</head>

<style>
    .container.mt-5 {
        /* border: 2px solid black; */
        /* border-bottom: none; */
        border-bottom: none;
        margin-top: 0 !important;
    }
</style>
<body>
    @include('UsersView.header')

    <div class="container mt-5">
        <br>
        <h2>RTI Online Frequently Asked Questions</h2>
        <br>
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card">
                    <h5 class="card-header">Department of Information and Publicity</h5>
                    <div class="card-body">
                        <p style="color: red;">Q1: To which Public Authority can I file a request through this portal?</p>
                        <p style="color: black;"> A: An applicant who desires to obtain information under the RTI Act, 2005 can make a request
                            through this RTI Online Portal to the State Departments/Offices and other State Public
                            Authorities mentioned in ONLINE RTI request form.</p>

                        <p style="color: red;">Q2: How do I write my application for seeking the information as per RTI Act 2005?</p>
                        <p style="color: black;"> A: The text of the application may be written in the prescribed column of the RTI request form.
                            At present, the text of the application is confined up to 3000 characters only.
                            In case, the text of an application contains more than 3000 characters, it can be uploaded as a
                            PDF attachment in the "Supporting Document" column of the form.</p>

                        <p style="color: red;">Q3: How do I make the payment for RTI fee?</p>
                        <p style="color: black;"> A: After filling the first page of the RTI request form, a non-BPL applicant has to click on "Make
                            Payment" button for payment of the prescribed RTI fee.</p>
                            <p>The applicant can pay the prescribed RTI fee through eChallan payment gateway through the
                            following modes:
                            <ol>
                            <li>Internet banking.</li>
                            <li> ATM-cum-Debit card.</li>
                            <li>Credit/Debit card of Master/Visa.</li>
                            <li>UPI</li>
                            </ol>
                            It may be noted that no RTI fee is required to be paid by a citizen who is below poverty line, as
                            per RTI Rules, 2012. However, the BPL applicant must attach a copy of the certificate issued by
                            the appropriate government in this regard, along with the application.</p>

                        <p style="color: red;">Q4: Do I get any receipt for online filing of RTI application?</p>
                        <p style="color: black;">A: On submission of an application, a unique registration number will be issued, which may be
                            referred by the applicant for any future reference.
                            It may be noted that the application filed through this RTI Online Portal will reach
                            electronically to the "Nodal Officer" of the said Department/Office and to the SPIO of the
                            concerned Department/Office/Sub-Office.
                            If required the Nodal Officer can transmit the RTI application electronically to the concerned
                            SPIO.</p>

                        <p style="color: red;">Q5: What will happen to my application if I select a wrong Public Authority in the RTI request form?</p>
                        <p style="color: black;">A: In case the RTI application is not meant for the Public authority which has been selected by the applicant, the "Nodal Officer" or “SPIO” of the said public authority would transfer the application electronically to the "Nodal Officer" or “SPIO” of the concerned State Public authority. It may be noted that RTI applications filed through this portal for the Other the Goa State Public Authorities, would be returned, without any refund of fee.</p>

                        <p style="color: red;">Q6: Will I be informed about the additional fee (if any) is required to pay?</p>
                        <p style="color: black;">A: In case additional fee representing the cost is required for providing information, the SPIO will intimate the same, which can be viewed by the applicant through "Application Status" option in the RTI Online Portal and an e-mail alert or SMS or both will also be sent to the applicant for the same. For payment of additional fee online, the applicant needs to use the option ‘ Application Status’ in the RTI Online Portal and on providing the registration number of the request, option for "Make Payment" will be available.</p>

                        <p style="color: red;">Q7: How do I file an appeal with First Appellate Authority?</p>
                        <p style="color: black;">A: For making an appeal to the first Appellate Authority, the applicant has to select the option "Submit First Appeal" in the RTI Online Portal and fill up the form that will appear. The registration number and e-mail ID of the original application is required for filing the first appeal.</p>

                        <p style="color: red;">Q8: Do I need to make any payment for filing an appeal?</p>
                        <p style="color: black;">A: As per RTI Act, no fee has to be paid for first appeal.</p>

                        <p style="color: red;">Q9: Do I get any SMS from RTI Online Portal?</p>
                        <p style="color: black;">A: Though optional, the mobile number can be provided by the applicant/ appellant in order to receive SMS alerts.</p>

                        <p style="color: red;">Q10: What are the reasons for my RTI payment failing?</p>
                        <ol style="color: black;">
                            <li>Customer has set e-Commerce flag as Disabled for Debit / Credit Card.</li>
                            <li>Customer either closed the internet browser or not proceeded further with the transaction resulting into session timeout.</li>
                            <li>Wrong OTP or no OTP entered by customer.</li>
                            <li>Wrong details of cards entered by customers like Card No, CVV, expiry date.</li>
                            <li>Insufficient balance in customer’s account.</li>
                            <li>Customer cancelled the transaction and reinitiated with different mode of payment (say from Debit card to UPI etc.).</li>
                            <li>Customer received collect request for payment in UPI app, but not completed the payment within time limit.</li>
                            <li>Please note, In case of genuine payment failure cases, wherein failure status is received from e-Challan /SBI Payment Gateway. For Failure payments there will be auto reversal. So you may proceed with fresh payments. In case auto reversal not happened within 7 days’ time, you may ask or apply for reversal of amount paid from your Bank (Bank Account from which payment was deducted)</li>
                        </ol>

                    </div>
                </div>
            </div>
        </div>
        <br>
    </div>

    @include('UsersView.footer')
</body>
</html>
