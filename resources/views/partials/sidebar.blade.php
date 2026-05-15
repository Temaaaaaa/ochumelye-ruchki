<ul class="menu">
    @foreach ($menuCreativityTypes as $menuType)
        <li>
            <a
                href="{{ route('types.show', $menuType) }}"
                class="{{ isset($activeTypeId) && $activeTypeId === $menuType->id ? 'is-active' : '' }}"
            >
                {{ $menuType->title }}
            </a>
        </li>
    @endforeach
</ul>
