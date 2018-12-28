{{-- !!! This template must not have leading empty indents !!! --}}
{{-- @formatter:off --}}

@component('mail::message')

{{-- Title --}}
# {{ $title }}

{{-- Body --}}
{{ $body }}

{{-- Table --}}
@if($table !== null)
@component('mail::table')
{{ $table }}
@endcomponent
@endif

{{-- Panel --}}
@if($panel !== null)
@component('mail::panel')
{{ $panel }}
@endcomponent
@endif

{{-- Button --}}
@if($button !== null)
@component('mail::button', ['url' => $button['url']])
{{ $button['text'] }}
@endcomponent
@endif

{{-- Good bye --}}
Thanks,<br>
{{ config('app.name') }}

@endcomponent