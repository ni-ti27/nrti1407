{{-- ============================================================
     Help  (DBIM / GIGW 3.0)
     Same look as Search, Contact, Login, About Us, Sitemap and Policy:
     grey band outside, ONE white card inside (full width), main heading
     with the blue bar. Tables use the same soft style as the Search results.
     Uses layouts/app.blade.php for <head>, header, <main>, footer.
     Styles: section 50 (frame, heading, table) + section 58 (Help) in public/css/dbim.css
     ============================================================ --}}
@extends('layouts.app')

@section('title', 'Help | RTI Online, Government of Goa')

@section('content')
<div class="dbim-sd">
    <section class="dbim-sd-section" aria-labelledby="helpHeading">
        <div class="dbim-sd-wrap">
            <div class="dbim-sd-card">

                <h1 id="helpHeading" class="dbim-sd-heading">Help</h1>

                {{-- ---------- 1. File formats ---------- --}}
                <section class="dbim-hl-section" aria-labelledby="help-formats">
                    <h2 id="help-formats" class="dbim-hl-heading">Viewing Information in Various File Formats</h2>
                    <p class="dbim-hl-text">
                        Documents on this portal, such as the User Manual, are available as Portable Document
                        Format (PDF) files. To view them properly, your device needs a PDF reader. If your system
                        does not have one, you can download it from the Internet for free. The table below lists
                        the software needed.
                    </p>

                    {{-- scrollable on small screens; focusable so keyboard users can scroll it --}}
                    <div class="dbim-sd-scroll" role="region" tabindex="0" aria-labelledby="help-formats-caption">
                        <table class="dbim-sd-table dbim-hl-table">
                            <caption id="help-formats-caption" class="dbim-sr-only">Software needed to view the file formats used on this portal</caption>
                            <thead>
                                <tr>
                                    <th scope="col">Document Type</th>
                                    <th scope="col">Download</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>PDF content</td>
                                    <td>
                                        <a href="https://get.adobe.com/reader/" target="_blank" rel="noopener noreferrer">Adobe Acrobat Reader<span class="dbim-sr-only"> (opens in a new tab)</span></a>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </section>

                {{-- ---------- 2. Screen readers ---------- --}}
                <section class="dbim-hl-section" aria-labelledby="help-screen-readers">
                    <h2 id="help-screen-readers" class="dbim-hl-heading">Screen Reader Access</h2>
                    <p class="dbim-hl-text">
                        This website is designed to follow the Guidelines for Indian Government Websites (GIGW 3.0)
                        and the Web Content Accessibility Guidelines (WCAG) of the World Wide Web Consortium (W3C).
                        This enables people with visual impairments to use the website with assistive technologies,
                        such as screen readers.
                    </p>
                    <p class="dbim-hl-text">The table below lists some commonly used screen readers.</p>

                    <div class="dbim-sd-scroll" role="region" tabindex="0" aria-labelledby="help-readers-caption">
                        <table class="dbim-sd-table dbim-hl-table">
                            <caption id="help-readers-caption" class="dbim-sr-only">Information related to various screen readers</caption>
                            <thead>
                                <tr>
                                    <th scope="col">Screen Reader</th>
                                    <th scope="col">Website</th>
                                    <th scope="col">Free / Commercial</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>Non Visual Desktop Access (NVDA)</td>
                                    <td><a href="https://www.nvaccess.org/" target="_blank" rel="noopener noreferrer">www.nvaccess.org<span class="dbim-sr-only"> (opens in a new tab)</span></a></td>
                                    <td>Free</td>
                                </tr>
                                <tr>
                                    <td>JAWS</td>
                                    <td><a href="https://www.freedomscientific.com/products/software/jaws/" target="_blank" rel="noopener noreferrer">www.freedomscientific.com<span class="dbim-sr-only"> (opens in a new tab)</span></a></td>
                                    <td>Commercial</td>
                                </tr>
                                <tr>
                                    <td>Supernova</td>
                                    <td><a href="https://yourdolphin.com/" target="_blank" rel="noopener noreferrer">www.yourdolphin.com<span class="dbim-sr-only"> (opens in a new tab)</span></a></td>
                                    <td>Commercial</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </section>

                {{-- ---------- 3. Speech recognition ---------- --}}
                <section class="dbim-hl-section" aria-labelledby="help-speech">
                    <h2 id="help-speech" class="dbim-hl-heading">Speech Recognition Support</h2>
                    <p class="dbim-hl-text">
                        The website is designed to follow the same guidelines so that people with mobility
                        impairments, people with visual impairments and senior citizens can use it with assistive
                        technologies, such as speech recognition software.
                    </p>
                    <p class="dbim-hl-text">The table below lists some speech recognition software.</p>

                    <div class="dbim-sd-scroll" role="region" tabindex="0" aria-labelledby="help-speech-caption">
                        <table class="dbim-sd-table dbim-hl-table">
                            <caption id="help-speech-caption" class="dbim-sr-only">Information related to speech recognition software</caption>
                            <thead>
                                <tr>
                                    <th scope="col">Speech Recognition Software</th>
                                    <th scope="col">Website</th>
                                    <th scope="col">Free / Commercial</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>Dragon Naturally Speaking</td>
                                    <td><a href="https://www.nuance.com/dragon.html" target="_blank" rel="noopener noreferrer">www.nuance.com<span class="dbim-sr-only"> (opens in a new tab)</span></a></td>
                                    <td>Commercial</td>
                                </tr>
                                <tr>
                                    <td>Voice Access in Windows</td>
                                    <td>Built into Windows 11</td>
                                    <td>Free</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </section>

                {{-- ---------- 4. Accessibility statement ---------- --}}
                <section class="dbim-hl-section" aria-labelledby="help-accessibility">
                    <h2 id="help-accessibility" class="dbim-hl-heading">Accessibility Statement</h2>
                    <p class="dbim-hl-text">
                        We are committed to making RTI Online, Government of Goa accessible to all users,
                        irrespective of the device, technology or ability. The portal is built to give its visitors
                        the best possible accessibility and usability. It can be viewed on a variety of devices,
                        such as desktop and laptop computers and web-enabled mobile devices.
                    </p>
                    <p class="dbim-hl-text">
                        We have put in our best efforts to make all information on this portal accessible to people
                        with disabilities. For example, a user with a visual disability can use this portal with
                        assistive technologies, such as screen readers and screen magnifiers. The portal can also
                        be used with the keyboard alone, and the text size and a high-contrast theme can be
                        changed from the header.
                    </p>
                    <p class="dbim-hl-text">
                        We also aim to be standards compliant and to follow the principles of usability and
                        universal design, which should help all visitors of this portal.
                    </p>
                    <p class="dbim-hl-text">
                        Some documents, such as PDF files, may not be fully accessible to assistive technologies.
                        If you need any information in an accessible form, please write to us.
                    </p>
                </section>

            </div>
        </div>
    </section>
</div>
@endsection