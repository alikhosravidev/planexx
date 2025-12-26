@props([
    'name',
    'label' => 'تاریخ',
    'required' => false,
    'placeholder' => 'انتخاب تاریخ',
    'value' => null,
    'min' => null,
    'max' => null,
    'gregorian' => true,
    'format' => 'YYYY-0M-0D',
    'showTime' => false,
    'timeOnly' => false,
    'hourStep' => 1,
    'minuteStep' => 5,
    'minTime' => null,
    'maxTime' => null,
])

@php
    $dataAttribute = $timeOnly ? 'data-timepicker' : ($showTime ? 'data-datetimepicker' : 'data-datepicker');
    $defaultFormat = $timeOnly ? '0h:0m' : ($showTime ? 'YYYY-0M-0D 0h:0m' : 'YYYY-0M-0D');
    $actualFormat = $format !== 'YYYY-0M-0D' ? $format : $defaultFormat;
    $defaultPlaceholder = $timeOnly ? 'انتخاب زمان' : ($showTime ? 'انتخاب تاریخ و زمان' : 'انتخاب تاریخ');
    $actualPlaceholder = $placeholder !== 'انتخاب تاریخ' ? $placeholder : $defaultPlaceholder;
@endphp

<div class="">
    <div class="border border-border-medium rounded-xl overflow-hidden focus-within:border-primary focus-within:shadow-focus transition-all duration-200">
        <div class="flex items-stretch">
            <label for="{{ $name }}" {{ $attributes->merge(['class' => 'bg-bg-label border-l border-border-light px-lg py-3.5 text-sm text-text-secondary flex items-center leading-normal']) }}>
                {{ $label }} @if($required)<span class="text-red-500 mr-1">*</span>@endif
            </label>
            <input
                type="text"
                name="{{ $name }}"
                id="{{ $name }}"
                {{ $dataAttribute }}
                data-datepicker-format="{{ $actualFormat }}"
                data-datepicker-gregorian="{{ $gregorian ? 'true' : 'false' }}"
                @if($showTime) data-datepicker-time="true" @endif
                @if($timeOnly) data-datepicker-time-only="true" @endif
                @if($hourStep !== 1) data-datepicker-hour-step="{{ $hourStep }}" @endif
                @if($minuteStep !== 5) data-datepicker-minute-step="{{ $minuteStep }}" @endif
                @if($value) data-datepicker-value="{{ $value }}" @endif
                @if($min) data-datepicker-min="{{ $min }}" @endif
                @if($max) data-datepicker-max="{{ $max }}" @endif
                @if($minTime) data-datepicker-min-time="{{ $minTime }}" @endif
                @if($maxTime) data-datepicker-max-time="{{ $maxTime }}" @endif
                @if($required) required @endif
                placeholder="{{ $actualPlaceholder }}"
                @if($value) value="{{ $value }}" @endif
                readonly
                {{ $attributes->merge(['class' => 'flex-1 px-lg py-3.5 text-base text-text-primary outline-none bg-transparent leading-normal cursor-pointer']) }}
            />
        </div>
    </div>
    @error($name)
        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
    @enderror
</div>
