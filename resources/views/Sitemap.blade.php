{{-- ============================================================
     Sitemap  (DBIM / GIGW 3.0)
     Uses layouts/app.blade.php for <head>, header, <main>, footer.
     Styles: .dbim-form-card, .dbim-about (sections 16, 17) and
             .dbim-sitemap (section 20).

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
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-12 col-lg-9">

            <div class="card dbim-form-card">
                <h1 class="card-header h4 mb-0">Sitemap</h1>
                <div class="card-body dbim-about dbim-sitemap">

                    <p>
                        This page lists all the pages of RTI Online, Government of Goa, grouped by topic.
                        Select a link to open the page.
                    </p>

                    @foreach($sitemap as $group)
                        <section aria-labelledby="{{ $group['id'] }}">
                            <h2 id="{{ $group['id'] }}" class="h3">{{ $group['title'] }}</h2>
                            <ul class="dbim-sitemap__list">
                                @foreach($group['links'] as $link)
                                    <li>
                                        <a href="{{ $link['url'] }}"@if(!empty($link['download'])) download @endif>{{ $link['label'] }}</a>
                                        @if(!empty($link['children']))
                                            <ul class="dbim-sitemap__list dbim-sitemap__list--nested">
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
    </div>
</div>
@endsection