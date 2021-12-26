@php
    $paginatorMenu = getValue('Common.paginate_menu');
    $paginatorMenu = array_combine($paginatorMenu, $paginatorMenu);
@endphp
<div class="text-right custom-paginator">
    <x-forms.select
        onChange="submit()"
        label="表示件数"
        :options="$paginatorMenu"
        divClass="d-inline w-20 float-right"
        name="paginator_menu"
        class="paginate_menu"
        keySelected="{{ Request::get('limit') }}"
        noDefault="true"
    />
</div>

@push('style')
    <style>
        .custom-paginator label {
            margin-right: 10px;
            margin-top: 10px;
        }
    </style>
@endpush
