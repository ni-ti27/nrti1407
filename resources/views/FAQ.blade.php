{{-- ============================================================
     RTI Online - Frequently Asked Questions
     (DBIM / GIGW 3.0, keyboard + screen reader friendly)
     Same card as the Officers Login page (dbim-login__*), wide version.
     Uses layouts/app.blade.php for <head>, header, <main>, footer.
     No inline styles. Questions use the Blue colour group (not red:
     red is reserved for errors in the DBIM functional palette).
     Styles: dbim.css sections 12, 27, 29 and 35
     ============================================================ --}}
@extends('layouts.app')

@section('title', 'Frequently Asked Questions | RTI Online, Government of Goa')

@section('content')
<div class="dbim-login dbim-login--wide">
    <section class="dbim-login__card" aria-labelledby="faqHeading">

        <div class="dbim-login__head">
            <h1 id="faqHeading" class="dbim-login__title">RTI Online Frequently Asked Questions</h1>
        </div>

        <div class="dbim-login__body">

            <h2 class="dbim-faq__dept">Department of Information and Publicity</h2>

            {{-- Jump links: quick keyboard access to each question --}}
            <nav aria-labelledby="faqIndexHeading" class="dbim-faq__index">
                <h3 id="faqIndexHeading" class="h6 fw-bold">Questions on this page</h3>
                <ol class="mb-0">
                    <li><a href="#faq-1">To which Public Authority can I file a request through this portal?</a></li>
                    <li><a href="#faq-2">How do I write my application for seeking the information as per RTI Act 2005?</a></li>
                    <li><a href="#faq-3">How do I make the payment for RTI fee?</a></li>
                    <li><a href="#faq-4">Do I get any receipt for online filing of RTI application?</a></li>
                    <li><a href="#faq-5">What will happen to my application if I select a wrong Public Authority in the RTI request form?</a></li>
                    <li><a href="#faq-6">Will I be informed about the additional fee (if any) is required to pay?</a></li>
                    <li><a href="#faq-7">How do I file an appeal with First Appellate Authority?</a></li>
                    <li><a href="#faq-8">Do I need to make any payment for filing an appeal?</a></li>
                    <li><a href="#faq-9">Do I get any SMS from RTI Online Portal?</a></li>
                    <li><a href="#faq-10">What are the reasons for my RTI payment failing?</a></li>
                </ol>
            </nav>

            <section class="dbim-faq-item" aria-labelledby="faq-1">
                <h3 id="faq-1" class="h6 fw-bold dbim-faq-question">Q1: To which Public Authority can I file a request through this portal?</h3>
                <p><span class="visually-hidden">Answer: </span>An applicant who desires to obtain information under the RTI Act, 2005 can make a request
                    through this RTI Online Portal to the State Departments/Offices and other State Public
                    Authorities mentioned in ONLINE RTI request form.</p>
            </section>

            <section class="dbim-faq-item" aria-labelledby="faq-2">
                <h3 id="faq-2" class="h6 fw-bold dbim-faq-question">Q2: How do I write my application for seeking the information as per RTI Act 2005?</h3>
                <p><span class="visually-hidden">Answer: </span>The text of the application may be written in the prescribed column of the RTI request form.
                    At present, the text of the application is confined up to 3000 characters only.
                    In case, the text of an application contains more than 3000 characters, it can be uploaded as a
                    PDF attachment in the "Supporting Document" column of the form.</p>
            </section>

            <section class="dbim-faq-item" aria-labelledby="faq-3">
                <h3 id="faq-3" class="h6 fw-bold dbim-faq-question">Q3: How do I make the payment for RTI fee?</h3>
                <p><span class="visually-hidden">Answer: </span>After filling the first page of the RTI request form, a non-BPL applicant has to click on "Make
                    Payment" button for payment of the prescribed RTI fee.</p>
                <p>The applicant can pay the prescribed RTI fee through eChallan payment gateway through the
                    following modes:</p>
                <ol>
                    <li>Internet banking.</li>
                    <li>ATM-cum-Debit card.</li>
                    <li>Credit/Debit card of Master/Visa.</li>
                    <li>UPI</li>
                </ol>
                <p>It may be noted that no RTI fee is required to be paid by a citizen who is below poverty line, as
                    per RTI Rules, 2012. However, the BPL applicant must attach a copy of the certificate issued by
                    the appropriate government in this regard, along with the application.</p>
            </section>

            <section class="dbim-faq-item" aria-labelledby="faq-4">
                <h3 id="faq-4" class="h6 fw-bold dbim-faq-question">Q4: Do I get any receipt for online filing of RTI application?</h3>
                <p><span class="visually-hidden">Answer: </span>On submission of an application, a unique registration number will be issued, which may be
                    referred by the applicant for any future reference.
                    It may be noted that the application filed through this RTI Online Portal will reach
                    electronically to the "Nodal Officer" of the said Department/Office and to the SPIO of the
                    concerned Department/Office/Sub-Office.
                    If required the Nodal Officer can transmit the RTI application electronically to the concerned
                    SPIO.</p>
            </section>

            <section class="dbim-faq-item" aria-labelledby="faq-5">
                <h3 id="faq-5" class="h6 fw-bold dbim-faq-question">Q5: What will happen to my application if I select a wrong Public Authority in the RTI request form?</h3>
                <p><span class="visually-hidden">Answer: </span>In case the RTI application is not meant for the Public authority which has been selected by the applicant, the "Nodal Officer" or “SPIO” of the said public authority would transfer the application electronically to the "Nodal Officer" or “SPIO” of the concerned State Public authority. It may be noted that RTI applications filed through this portal for the Other the Goa State Public Authorities, would be returned, without any refund of fee.</p>
            </section>

            <section class="dbim-faq-item" aria-labelledby="faq-6">
                <h3 id="faq-6" class="h6 fw-bold dbim-faq-question">Q6: Will I be informed about the additional fee (if any) is required to pay?</h3>
                <p><span class="visually-hidden">Answer: </span>In case additional fee representing the cost is required for providing information, the SPIO will intimate the same, which can be viewed by the applicant through "Application Status" option in the RTI Online Portal and an e-mail alert or SMS or both will also be sent to the applicant for the same. For payment of additional fee online, the applicant needs to use the option ‘ Application Status’ in the RTI Online Portal and on providing the registration number of the request, option for "Make Payment" will be available.</p>
            </section>

            <section class="dbim-faq-item" aria-labelledby="faq-7">
                <h3 id="faq-7" class="h6 fw-bold dbim-faq-question">Q7: How do I file an appeal with First Appellate Authority?</h3>
                <p><span class="visually-hidden">Answer: </span>For making an appeal to the first Appellate Authority, the applicant has to select the option "Submit First Appeal" in the RTI Online Portal and fill up the form that will appear. The registration number and e-mail ID of the original application is required for filing the first appeal.</p>
            </section>

            <section class="dbim-faq-item" aria-labelledby="faq-8">
                <h3 id="faq-8" class="h6 fw-bold dbim-faq-question">Q8: Do I need to make any payment for filing an appeal?</h3>
                <p><span class="visually-hidden">Answer: </span>As per RTI Act, no fee has to be paid for first appeal.</p>
            </section>

            <section class="dbim-faq-item" aria-labelledby="faq-9">
                <h3 id="faq-9" class="h6 fw-bold dbim-faq-question">Q9: Do I get any SMS from RTI Online Portal?</h3>
                <p><span class="visually-hidden">Answer: </span>Though optional, the mobile number can be provided by the applicant/ appellant in order to receive SMS alerts.</p>
            </section>

            <section class="dbim-faq-item" aria-labelledby="faq-10">
                <h3 id="faq-10" class="h6 fw-bold dbim-faq-question">Q10: What are the reasons for my RTI payment failing?</h3>
                <ol class="mb-0">
                    <li>Customer has set e-Commerce flag as Disabled for Debit / Credit Card.</li>
                    <li>Customer either closed the internet browser or not proceeded further with the transaction resulting into session timeout.</li>
                    <li>Wrong OTP or no OTP entered by customer.</li>
                    <li>Wrong details of cards entered by customers like Card No, CVV, expiry date.</li>
                    <li>Insufficient balance in customer’s account.</li>
                    <li>Customer cancelled the transaction and reinitiated with different mode of payment (say from Debit card to UPI etc.).</li>
                    <li>Customer received collect request for payment in UPI app, but not completed the payment within time limit.</li>
                    <li>Please note, In case of genuine payment failure cases, wherein failure status is received from e-Challan /SBI Payment Gateway. For Failure payments there will be auto reversal. So you may proceed with fresh payments. In case auto reversal not happened within 7 days’ time, you may ask or apply for reversal of amount paid from your Bank (Bank Account from which payment was deducted)</li>
                </ol>
            </section>

        </div>
    </section>
</div>
@endsection