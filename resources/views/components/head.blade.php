<!-- Start of PWA -->
{{ $slot }}
<link rel="icon" type="image/png" sizes="32x32" href="{{ $base_uri }}/icon-32.png">
<link rel="icon" type="image/png" sizes="16x16" href="{{ $base_uri }}/icon-16.png">
<link rel="manifest" href="{{ $base_uri }}/manifest.json" />
<meta name="application-name" content="{{ $name }}">

<!-- iOS-specific PWA -->

<link rel="apple-touch-icon" sizes="180x180" href="{{ $base_uri }}/icon-180.png">
{{--
<link rel="apple-touch-startup-image" href="/launch.png">
<meta name="apple-mobile-web-app-title" content="{{ $name }}">
<meta name="apple-mobile-web-app-capable" content="yes">
<meta name="apple-mobile-web-app-status-bar-style" content="black">
--}}
<!-- End of PWA -->
