<!-- resources/views/components/nav-menu.blade.php -->
@props(['items'])

<ul class="nav-menu space-y-1">
    @foreach ($items as $item)
        <li class="nav-item">
            <x-nav-link :href="$item['url']" :active="request()->routeIs($item['active'] ?? '')">
                {{ $item['title'] }}
            </x-nav-link>

            @if (isset($item['submenu']))
                <ul class="submenu pl-4 space-y-1">
                    @foreach ($item['submenu'] as $submenu)
                        <li class="submenu-item">
                            <x-nav-link :href="$submenu['url']" :active="request()->routeIs($submenu['active'] ?? '')">
                                {{ $submenu['title'] }}
                            </x-nav-link>
                        </li>
                    @endforeach
                </ul>
            @endif
        </li>
    @endforeach
</ul>
