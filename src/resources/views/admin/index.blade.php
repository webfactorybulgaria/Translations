@extends('core::admin.master')

@section('title', trans('translations::global.name'))

@section('main')

<div ng-app="typicms" ng-cloak ng-controller="ListController">

    @include('core::admin._button-create', ['module' => 'translations'])

    <h1>
        <span>@{{ models.length }} @choice('translations::global.translations', 2)</span>
    </h1>

    <div class="btn-toolbar">
        @include('core::admin._lang-switcher')
    </div>

    <div class="table-responsive">

        <table st-persist="translationsTable" st-table="displayedModels" st-safe-src="models" st-order st-filter class="table table-condensed table-main">
            <thead>
                <tr>
                    <th class="delete"></th>
                    <th class="edit"></th>
                    <th st-sort="key" st-sort-default="true" class="key st-sort">Key</th>
                    <th st-sort="translation" class="translation st-sort">Translation</th>
                </tr>
                <tr>
                    <td colspan="2"></td>
                    <td>
                        <input st-search="key" class="form-control input-sm" placeholder="@lang('global.Search')…" type="text">
                    </td>
                    <td>
                        <input st-search="translation" class="form-control input-sm" placeholder="@lang('global.Search')…" type="text">
                    </td>
                </tr>
            </thead>

            <tbody>
                <tr ng-repeat="model in displayedModels">
                    <td typi-btn-delete action="delete(model, model.key)"></td>
                    <td>
                        @include('core::admin._button-edit', ['module' => 'translations'])
                    </td>
                    <td>
                        <span>@{{model.key}}</span>
                    </td>
                    <td>
                        <span edit-translation ng-init="temp = model.translation">@{{model.translation}}</span>
                        <div class="hidden" style="position: relative;">
                            <input key-translation class="form-control input-sm" ng-model="model.translation" placeholder="@lang('label.translation')">
                            <span save-translation ng-click="update(model, model.translation); temp = model.translation" class="fa fa-save" title="Save" style="position: absolute;right: 8px;top: 5px;line-height: 1;font-size: 1.5em;color: #428bca;cursor: pointer;"></span>
                        </div>
                    </td>
                </tr>
            </tbody>
            <tfoot>
                <tr>
                    <td colspan="4" typi-pagination></td>
                </tr>
            </tfoot>
        </table>
    <a href="{{ route('admin::' . $module . '-massEdit').'?locale='.$locale }}">Mass edit translations</a>
    </div>

</div>

@endsection
