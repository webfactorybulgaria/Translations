@extends('core::admin.master')

@section('title', @$title)

@section('main')

	<div class="btn-toolbar">
	    @include('core::admin._lang-switcher')
	</div>

{!! BootForm::open()->action(route('admin.translations.massStore').'?locale='.$locale)->role('form') !!}
<textarea style="width:100%;max-width:100%;min-height:700px;" name="translations">
@foreach($models as $key => $trans)
{{$key}}={{$trans}}
@endforeach
</textarea>
	<div class="btn-toolbar">
	    <button class="btn-primary btn" value="true" id="exit" name="exit" type="submit">@lang('validation.attributes.save and exit')</button>
	    <button class="btn-default btn" type="submit">@lang('validation.attributes.save')</button>
	</div>
{!! BootForm::close() !!}

@endsection