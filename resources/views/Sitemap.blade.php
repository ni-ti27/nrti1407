{{-- ============================================================
     Sitemap  (DBIM / GIGW 3.0)
     Same look as Search, Contact, Login, About Us and Related Links:
     grey band outside, ONE white card inside, main heading with the blue bar.
     Uses layouts/app.blade.php for <head>, header, <main>, footer.
     Styles: section 50 (frame, heading) + section 56 (Sitemap) in public/css/dbim.css

     To add or change a link, edit the $sitemap array below only.
     TODO: every 'url' is a placeholder path. Replace it with your real
     path or route, for example  route('your.route.name').
     'children' is optional and gives a nested list under a link.
     ============================================================ --}}
@php
    $sitemap = [
        [
            'id'    => 'sitemap-home',
            'title' => 'Home',
            'links' => [
                ['label' => 'Home', 'url' => url('/')],
            ],
        ],
        [
            'id'    => 'sitemap-applicant',
            'title' => 'Applicant',
            'links' => [
                ['label' => 'Rti New Request',  'url' => url('/registerDetailsWithOtp')],
                ['label' => 'First Appeal',  'url' => url('/registerDetailsWithOtp')],
                ['label' => 'Second Appeal', 'url' => url('/registerDetailsWithOtp')],

            ],
        ],
        [
            'id'    => 'sitemap-publicauthorities',
            'title' => 'Public Authorities',
            'links' => [
                ['label' => 'Public Authorities',  'url' => url('/searchDepartment')],

            ],
        ],


        [
            'id'    => 'sitemap-department',
            'title' => 'Department',
            'links' => [
                ['label' => 'Department Login',  'url' => url('/login')],
            ],
        ],
          [
            'id'    => 'sitemap-contactus',
            'title' => 'Contact Us',
            'links' => [
                ['label' => 'Contact Us',  'url' => url('/contactUs')],
            ],
        ],
        [
            'id'    => 'sitemap-information',
            'title' => 'Information',
            'links' => [
                ['label' => 'About Us', 'url' => url('/AboutUs')],
                ['label' => 'FAQ',      'url' => url('/FAQ')],
                ['label' => 'Help',     'url' => url('/Help')],
                ['label' => 'Policy',   'url' => url('/Policy')],

            ],
        ],
        [
            'id'    => 'sitemap-downloads',
            'title' => 'Downloads',
            'links' => [
                ['label' => 'User Manual (PDF)', 'url' => asset('Applicant_Module_User_Manual.pdf'), 'download' => true],
            ],
        ],
    ];
@endphp
@extends('layouts.app')

@section('title', 'Sitemap | RTI Online, Government of Goa')

@section('content')
<div class="dbim-sd">
    <section class="dbim-sd-section" aria-labelledby="sitemapHeading">
        <div class="dbim-sd-wrap">
            <div class="dbim-sd-card dbim-sm-card">

                <h1 id="sitemapHeading" class="dbim-sd-heading">Sitemap</h1>

                <p class="dbim-sm-intro">
                    This page lists all the pages of RTI Online, Government of Goa, grouped by topic.
                    Select a link to open the page.
                </p>

                <hr class="dbim-sd-divider">

                <div class="dbim-sm-grid">
                    @foreach($sitemap as $group)
                        <section class="dbim-sm-group" aria-labelledby="{{ $group['id'] }}">
                            <h2 id="{{ $group['id'] }}" class="dbim-sm-group__title">{{ $group['title'] }}</h2>
                            <ul class="dbim-sm-list">
                                @foreach($group['links'] as $link)
                                    <li>
                                        <a href="{{ $link['url'] }}"@if(!empty($link['download'])) download @endif>{{ $link['label'] }}</a>
                                        @if(!empty($link['children']))
                                            <ul class="dbim-sm-list dbim-sm-list--nested">
                                                @foreach($link['children'] as $child)
                                                    <li><a href="{{ $child['url'] }}">{{ $child['label'] }}</a></li>
                                                @endforeach
                                            </ul>
                                        @endif
                                    </li>
                                @endforeach
                            </ul>
                        </section>
                    @endforeach
                </div>

            </div>
        </div>
    </section>
</div>
@endsection