{{-- ============================================================
     About Us  (DBIM / GIGW 3.0)
     Same look as Search, Contact and Login: grey band outside,
     ONE white card inside, main heading with the blue bar.
     Uses layouts/app.blade.php for <head>, header, <main>, footer.
     Styles: section 50 (frame, heading) + section 54 (About Us) in public/css/dbim.css
     ============================================================ --}}
@extends('layouts.app')

@section('title', 'About Us | RTI Online, Government of Goa')

@section('content')
<div class="dbim-sd">
    <section class="dbim-sd-section" aria-labelledby="aboutHeading">
        <div class="dbim-sd-wrap">
            <div class="dbim-sd-card dbim-ab-card">

                <h1 id="aboutHeading" class="dbim-sd-heading">About Us</h1>

                <section class="dbim-ab-section" aria-labelledby="about-dept">
                    <h2 id="about-dept" class="dbim-ab-heading">Department of Information and Publicity</h2>
                    <p class="dbim-ab-text">
                        The Department of Information and Publicity, Government of Goa, is a nodal agency for
                        dissemination of information on policies and programmes of the government through print,
                        electronic and traditional media. The Department plays a pivotal role in the promotion of
                        schemes and policies that lead to a greater and better understanding between the Government
                        and the people. It also works as a communication medium between the government and media
                        and initiates measures to take the welfare schemes to the doorsteps of the people.
                    </p>
                    <p class="dbim-ab-text">
                        The Department is headed by the Director, who is the Head of Department and assisted by the
                        Joint Director of Information &amp; Publicity, who is the Head of Office.
                    </p>
                    <p class="dbim-ab-text">
                        There are two Information Officers in the department looking after advertisements, films,
                        field publicity, etc. There is a sub-office of the Department at Margao, catering to the
                        publicity needs of the Government in South Goa District.
                    </p>
                </section>

                <section class="dbim-ab-section" aria-labelledby="about-portal">
                    <h2 id="about-portal" class="dbim-ab-heading">RTI Online Portal</h2>
                    <p class="dbim-ab-text">
                        RTI Online Portal implementation is part of the Apex Court Mandate to enable RTI Applicants
                        in e-filing of RTI Applications, First Appeal, and Second Appeal, and also to receive
                        replies in an online manner. The portal facilitates e-filing of RTI Applications, First
                        Appeals and Second Appeals by Indian Citizens for any of the Departments, Offices,
                        Sub-Offices and Public Authorities of the Goa State Government.
                    </p>
                    <p class="dbim-ab-text">
                        The portal manages RTI information at three levels for Public Authorities: Department,
                        Office, and Sub Office.
                    </p>
                </section>

                <section class="dbim-ab-section" aria-labelledby="about-roles">
                    <h2 id="about-roles" class="dbim-ab-heading">Roles on the portal</h2>
                    <p class="dbim-ab-text">The following roles exist on the portal to manage RTI Applications, First Appeals and Second Appeals:</p>
                    <ul class="dbim-ab-list">
                        <li>Nodal Officer (NODAL)</li>
                        <li>Public Information Officer (PIO)</li>
                        <li>Assistant Public Information Officer (APIO)</li>
                        <li>First Appellate Authority (FAA)</li>
                        <li>Clerk (CLERK)</li>
                        <li>State Information Commissioner (SIC)</li>
                    </ul>
                </section>

                <section class="dbim-ab-section" aria-labelledby="about-payment">
                    <h2 id="about-payment" class="dbim-ab-heading">Online payment</h2>
                    <p class="dbim-ab-text">
                        The portal facilitates e-filing of applications online along with the e-Challan Payment
                        Gateway. Payment can be made through:
                    </p>
                    <ul class="dbim-ab-list">
                        <li>Internet Banking</li>
                        <li>Debit / Credit cards (Master / Visa)</li>
                        <li>UPI</li>
                    </ul>
                </section>

                <section class="dbim-ab-section" aria-labelledby="about-support">
                    <div class="dbim-ab-support">
                        <h2 id="about-support" class="dbim-ab-support__title">Support</h2>
                        <p class="dbim-ab-support__text">
                            RTI Support Email Id: @obfuscateEmail('support.rtionline@goa.gov.in')
                            has been shared for user assistance.
                        </p>
                        <p class="dbim-ab-support__text dbim-ab-support__text--last">
                            <strong>Department of Information and Publicity, Panaji, Goa</strong>
                        </p>
                    </div>
                </section>

            </div>
        </div>
    </section>
</div>
@endsection