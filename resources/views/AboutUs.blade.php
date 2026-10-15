{{-- ============================================================
     About Us  (DBIM / GIGW 3.0)
     Uses layouts/app.blade.php for <head>, header, <main>, footer.
     ============================================================ --}}
@extends('layouts.app')

@section('title', 'About Us | RTI Online, Government of Goa')

@section('content')
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-12 col-lg-9">

            <div class="card dbim-form-card">

                <h1 class="card-header h4 mb-0">About Us</h1>
                <div class="card-body dbim-about">

                    <section aria-labelledby="about-dept">
                        <h2 id="about-dept" class="h3">Department of Information and Publicity</h2>
                        <p>
                            The Department of Information and Publicity, Government of Goa, is a nodal agency for
                            dissemination of information on policies and programmes of the government through print,
                            electronic and traditional media. The Department plays a pivotal role in the promotion of
                            schemes and policies that lead to a greater and better understanding between the Government
                            and the people. It also works as a communication medium between the government and media
                            and initiates measures to take the welfare schemes to the doorsteps of the people.
                        </p>
                        <p>
                            The Department is headed by the Director, who is the Head of Department and assisted by the
                            Joint Director of Information &amp; Publicity, who is the Head of Office.
                        </p>
                        <p>
                            There are two Information Officers in the department looking after advertisements, films,
                            field publicity, etc. There is a sub-office of the Department at Margao, catering to the
                            publicity needs of the Government in South Goa District.
                        </p>
                    </section>

                    <section aria-labelledby="about-portal">
                        <h2 id="about-portal" class="h3">RTI Online Portal</h2>
                        <p>
                            RTI Online Portal implementation is part of the Apex Court Mandate to enable RTI Applicants
                            in e-filing of RTI Applications, First Appeal, and Second Appeal, and also to receive
                            replies in an online manner. The portal facilitates e-filing of RTI Applications, First
                            Appeals and Second Appeals by Indian Citizens for any of the Departments, Offices,
                            Sub-Offices and Public Authorities of the Goa State Government.
                        </p>
                        <p>
                            The portal manages RTI information at three levels for Public Authorities: Department,
                            Office, and Sub Office.
                        </p>
                    </section>

                    <section aria-labelledby="about-roles">
                        <h2 id="about-roles" class="h3">Roles on the portal</h2>
                        <p>The following roles exist on the portal to manage RTI Applications, First Appeals and Second Appeals:</p>
                        <ul class="dbim-role-list">
                            <li>Nodal Officer (NODAL)</li>
                            <li>Public Information Officer (PIO)</li>
                            <li>Assistant Public Information Officer (APIO)</li>
                            <li>First Appellate Authority (FAA)</li>
                            <li>Clerk (CLERK)</li>
                            <li>State Information Commissioner (SIC)</li>
                        </ul>
                    </section>

                    <section aria-labelledby="about-payment">
                        <h2 id="about-payment" class="h3">Online payment</h2>
                        <p>
                            The portal facilitates e-filing of applications online along with the e-Challan Payment
                            Gateway. Payment can be made through:
                        </p>
                        <ul class="dbim-role-list">
                            <li>Internet Banking</li>
                            <li>Debit / Credit cards (Master / Visa)</li>
                            <li>UPI</li>
                        </ul>
                    </section>

                    <section aria-labelledby="about-support">
                        <div class="dbim-support">
                            <h2 id="about-support" class="h3">Support</h2>
                            <p>
                                RTI Support Email Id: @obfuscateEmail('support.rtionline@goa.gov.in')
                                has been shared for user assistance.
                            </p>
                            <p class="mb-0">
                                <strong>Department of Information and Publicity, Panaji, Goa</strong>
                            </p>
                        </div>
                    </section>

                </div>
            </div>

        </div>
    </div>
</div>
@endsection