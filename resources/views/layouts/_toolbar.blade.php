{{-- Page toolbar: tabs/title and filter controls on the left, secondary actions + primary create action on the right — all on one row.
     Usage:
       @include('layouts._toolbar', ['tabs' => 'user_cluster', 'create_url' => route('user.create'), 'create_label' => __('Add Employee')])
       @include('layouts._toolbar', ['title' => __('Shifts'), 'create_url' => route('shift.create'), 'create_label' => __('Add Shift')])
       @include('layouts._toolbar', ['actions' => [['url' => route('...'), 'label' => __('...'), 'class' => 'btn-info']], 'create_url' => ...])
       @include('layouts._toolbar', ['filter_action' => route('attendance.index'), 'filters' => [
           ['type' => 'select', 'name' => 'status', 'label' => __('Status'), 'options' => ['0' => __('No'), '1' => __('Yes')]],
           ['type' => 'date', 'name' => 'from', 'label' => __('From')],
       ], 'create_url' => ...])
     Filter controls auto-submit on change; a Reset link appears once any filter is set. --}}
@php
    $clusterTabs = [
        'user_cluster' => [
            ['label' => __('Employees'),   'url' => route('user.index'),       'active' => request()->routeIs('user.index')],
            ['label' => __('Offices'),     'url' => route('kantor.index'),     'active' => request()->routeIs('kantor.*')],
            ['label' => __('Departments'), 'url' => route('departemen.index'), 'active' => request()->routeIs('departemen.*')],
            ['label' => __('Groups'),      'url' => route('grup.index'),       'active' => request()->routeIs('grup.*')],
            ['label' => __('Positions'),   'url' => route('jabatan.index'),    'active' => request()->routeIs('jabatan.*')],
            ['label' => __('Archive'),     'url' => route('user.archived'),    'active' => request()->routeIs('user.archived')],
        ],
    ];
    $tabSet = $clusterTabs[$tabs ?? ''] ?? null;
    $filterFields = $filters ?? [];
@endphp

<div class="si-toolbar d-flex align-items-end justify-content-between flex-wrap mb-3" style="gap: .75rem;">
    <div class="d-flex align-items-end flex-wrap" style="gap: .75rem;">
        @if ($tabSet)
            <ul class="nav nav-pills si-tabs">
                @foreach ($tabSet as $tab)
                    <li class="nav-item">
                        <a class="nav-link {{ $tab['active'] ? 'active' : '' }}" href="{{ $tab['url'] }}">{{ $tab['label'] }}</a>
                    </li>
                @endforeach
            </ul>
        @elseif (!empty($title))
            <h5 class="m-0 si-toolbar-title">{{ $title }}</h5>
        @endif

        @if ($filterFields)
            <form method="GET" action="{{ $filter_action }}" class="d-flex align-items-end flex-wrap" style="gap: .75rem;">
                @foreach ($filterFields as $field)
                    <div>
                        <label for="filter-{{ $field['name'] }}" class="mb-1 d-block" style="font-size: 12px; font-weight: 600; color: var(--si-ink-2);">{{ $field['label'] }}</label>
                        @if ($field['type'] === 'select')
                            <select name="{{ $field['name'] }}" id="filter-{{ $field['name'] }}" class="custom-select custom-select-sm w-auto" onchange="this.form.submit()">
                                <option value="">{{ $field['empty'] ?? __('All') }}</option>
                                @foreach ($field['options'] as $value => $label)
                                    <option value="{{ $value }}" {{ request($field['name']) !== null && request($field['name']) === (string) $value ? 'selected' : '' }}>{{ $label }}</option>
                                @endforeach
                            </select>
                        @else
                            <input type="{{ $field['type'] }}" name="{{ $field['name'] }}" id="filter-{{ $field['name'] }}"
                                value="{{ request($field['name']) }}" class="form-control form-control-sm" onchange="this.form.submit()">
                        @endif
                    </div>
                @endforeach
                @if (collect($filterFields)->contains(fn ($field) => filled(request($field['name']))))
                    <div>
                        <a href="{{ $filter_action }}" class="btn btn-sm btn-outline-secondary">{{ __('Reset') }}</a>
                    </div>
                @endif
            </form>
        @endif
    </div>

    <div class="d-flex align-items-center" style="gap: .5rem;">
        @foreach ($actions ?? [] as $action)
            <a href="{{ $action['url'] }}" class="btn btn-sm {{ $action['class'] ?? 'btn-info' }}">{{ $action['label'] }}</a>
        @endforeach
        @if (!empty($create_url))
            <a href="{{ $create_url }}" class="btn btn-primary btn-sm">
                <i class="fas fa-plus mr-1"></i> {{ $create_label ?? __('Add') }}
            </a>
        @endif
    </div>
</div>
