<!DOCTYPE html>
<html ng-app="PmApp" lang="en">
<head>
	@include('includes.head')
	@yield('styles')
</head>
<body>
	@yield('content')
	@include('includes.footer')
	@yield('scripts')
</body>
</html>