<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    @include('global.seo')

    @include('global.include_top')

</head>

<body>
    <!-- Header Area Start -->
    @include('global.header')
    <!-- Header Area End   -->

    <!-- Body Area Start   -->
    @yield('content')
    <!-- Body Area End   -->

    <!-- Footer  Area Start   -->
    @include('global.footer')
    <!-- Footer Area End   -->

    <!-- Bottom Area Start -->
    @include('global.include_bottom')
    <!-- Bottom Area End   -->

 
<!--Start of Tawk.to Script-->
<script type="text/javascript">
var Tawk_API=Tawk_API||{}, Tawk_LoadStart=new Date();
(function(){
var s1=document.createElement("script"),s0=document.getElementsByTagName("script")[0];
s1.async=true;
s1.src='https://embed.tawk.to/6673d494dd590416e257fac1/1i0q7cpap';
s1.charset='UTF-8';
s1.setAttribute('crossorigin','*');
s0.parentNode.insertBefore(s1,s0);
})();
</script>
<!--End of Tawk.to Script-->

</body>

</html>