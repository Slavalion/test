@props(['url'])
<tr>
<td class="header">
<a href="{{ $url }}" style="display: inline-block;">
@if (trim($slot) === 'mpb ')
<img src="https://laravel.com/img/notification-logo.png" class="logo" alt="Laravel Logo">
@else
<img src="{{ $url }}/images/LogoWhite.svg" class="logo" alt="Mpb top">
@endif
</a>
</td>
</tr>
