@extends('layouts.app')

@section('title', 'GateForestTrip - Petualangan Alam Terpercaya')

@section('content')
<x-hero-section :highlight-paket="$highlightPaket" :landing-stats="$landingStats" />
<x-trip-of-month :trip-of-the-month="$tripOfTheMonth" :featured-pakets="$featuredPakets" />
<x-adventure-catalog :pakets="$catalogPakets" />
<x-newsletter-section />
@endsection
