<div class="row justify-content-center">
    <div style="text-align:center">
        <h3 style="font-size:18; margin-top:-20px">BEFORE THE GOA STATE INFORMATION COMMISSION</h3>
        <p style="font-size:15; margin-top:-20px">Kamat Towers, Seventh Floor, Patto Plaza, Panaji Goa</p>
        <p style="font-size:20" style="font-size:15; margin-top:-20px">Website : <u>www.gsic.goa.gov.in</u> &nbsp; &nbsp; Email: <u>spio-gsic-goa@nic.in</u></p>
        <p style="font-size:20" style="font-size:15; margin-top:-16px">Tel Nos. 0832-2437108, 2437880</p>
    </div>
    <hr>
    <p style="text-align:right; margin-top:5px"> <u><b>Appeal No. {{ $saDetails->sa_reg_no }}</b></u></p>
    <div style="text-align:left">
        <p style="margin-top:0px"> {{ $saDetails->name }} </p>

        @if($applicantAddress == null)
            <p style="margin-top:-15px"> r/o. {{ $saDetails->address_line1 }} </p>
            <p style="text-align:right; margin-top:-32px"> <b>-----------------Appellant</b></p>
            <p style="margin-top:-15px"> {{ $saDetails->address_line2 }} </p>
            <p style="margin-top:-15px"> {{ $saDetails->address_line3 }} </p>
            <p style="margin-top:-15px"> {{ $saDetails->pincode }} </p>
        @else
            <p style="margin-top:-15px"> r/o. {!! nl2br(e($applicantAddress)) !!} </p>
        @endif


        <p style="margin-top:0px"><b><b>&nbsp;&nbsp;&nbsp; V/s</b></p>

        <p style="margin-top:0px"> {{ $pio->name }} </p>
        @if($pioAddress == null)
            <p style="margin-top:-15px"> r/o. {{ $pio->address1 }} </p>
            <p style="text-align:right; margin-top:-32px"> <b>---------------Respondent</b></p>
            <p style="margin-top:-15px"> {{ $pio->address2 }} </p>
            <p style="margin-top:-15px"> {{ $pio->address3 }} </p>
            <p style="margin-top:-15px"> {{ $pio->pincode }} </p>
        @else
            <p style="margin-top:-15px"> r/o. {!! nl2br(e($pioAddress)) !!} </p>
        @endif
    </div>
    <hr style="border: none; border-top: 2px dotted black; margin: 20px 0;">
    <h3 style="text-align:center; font-size:15; margin-top:-14px"><b><u>NOTICE</u></b></h3>

    <div style="text-align:left">
        <p style="margin-top:0px"> To, </p>
        <p style="margin-top:-15px"> {{ $saDetails->name }} </p>

        @if($applicantAddress == null)
            <p style="margin-top:-15px"> r/o. {{ $saDetails->address_line1 }} </p>
            <p style="margin-top:-15px"> {{ $saDetails->address_line2 }} </p>
            <p style="margin-top:-15px"> {{ $saDetails->address_line3 }} </p>
            <p style="margin-top:-15px"> {{ $saDetails->pincode }}. </p>
        @else
            <p style="margin-top:-15px"> r/o. {!! nl2br(e($applicantAddress)) !!} </p>
        @endif
    </div>

    <div class="text-align:center">
        <p style="margin-top:-5px">&nbsp;&nbsp;&nbsp;WHEREAS the above named appellent has filed the above second appeal under Right to Information Act, 2005 (Copy of the appeal memo may be collected from the Office of this Commission of the working day).</p>
        <br>
        <p style="margin-top:-20px">&nbsp;&nbsp;&nbsp;The above appeal is posted by the Commission before the State Information Commissioner on {{ $dateNow }}.</p>
        <br>
        <p style="margin-top:-20px">&nbsp;&nbsp;&nbsp;In case of your failure to attend the hearing on the sate fixed as above, the matter shall be heard in your absence and the orders passed by the Commission shall be binding and executable against you, which may please be noted.</p>
        <p>&nbsp;&nbsp;&nbsp;Given under my hand and seal on {{ $dateNow }}.</p>
    </div>

    <div class="text-align:left">
        <p style="text-align:right; margin-top:5px"> <u><b>By Order of Commission,</b></u></p>
        <br>
        <p style="text-align:right; margin-top:5px"> <u><b>( {{ $currentOfficer->name }} )</b></u></p>
        <p style="text-align:right; margin-top:5px"> <u><b>Under Sceretary cum Registrar, GSIC</b></u></p>

    </div>

    <div style="page-break-before: always;"></div>

    <div class="text-align:left">
        <p style="margin-top:0px"> To, </p>

        <p style="margin-top:-15px"> {{ $pio->name }} </p>

        @if($pioAddress == null)
            <p style="margin-top:-15px"> r/o. {{ $pio->address1 }} </p>
            <p style="margin-top:-15px"> {{ $pio->address2 }} </p>
            <p style="margin-top:-15px"> {{ $pio->address3 }} </p>
            <p style="margin-top:-15px"> {{ $pio->pincode }} </p>
        @else
            <p style="margin-top:-15px"> r/o. {!! nl2br(e($pioAddress)) !!} </p>
        @endif
    </div>
    <br>
    <br>
    <div class="text-align:left">
        <p style="margin-top:0px"><b> NOTE: </b></p>
        <p style="margin-top:0px">(i)</p>
        <p class="text-align:right" style="margin-top:-40px; margin-left:30px">
            In case of change of PIO either due to transfer or otherwise, the present PIO shall notify the concerned PIO about the date fixed for hearing and file copy of such notice alongwith his/her acknowlegment before this Commission. The present PIO shall also file a memo of the name and present place of posting of the then PIO.
        </p>
        <p style="margin-top:0px">(ii)</p>
        <p class="text-align:right" style="margin-top:-40px; margin-left:30px">
            In case the then PIO has retired from services, present PIO shall submit to the Comission the date of retirement along with present postal address of the then PIO on or before the next date.
        </p>
    </div>
</div>