@php
    $menuData = json_decode(file_get_contents(resource_path('json/admin-menu.json')), true);
    $currentUrl = request()->path();
    $userRole = auth()->user()->getRoleNames()[0] ?? 'guest'; // Mendapatkan role pengguna yang sedang login
@endphp

<div class="bg-gradient-to-b from-[var(--color-royal-blue-800)] to-[var(--color-royal-blue-900)] text-white w-64 min-h-screen p-4 shadow-xl">
    <div class="flex items-center justify-center mb-8 py-4 border-b border-[var(--color-royal-blue-700)]">
        <i class="fas fa-school text-2xl mr-3 text-[var(--color-royal-blue-300)]"></i>
        <h2 class="text-xl font-bold text-white">SIMONIS Admin</h2>
    </div>
    
    <nav>
        <ul class="space-y-2">
            @foreach ($menuData['menu'] as $item)
                @php
                    // Memeriksa apakah pengguna memiliki akses ke menu ini
                    $hasAccess = isset($item['roles']) && in_array($userRole, $item['roles']);
                    
                    // Jika tidak ada roles yang ditentukan, semua pengguna memiliki akses
                    if (!isset($item['roles'])) {
                        $hasAccess = true;
                    }
                @endphp
                
                @if ($hasAccess)
                <li class="group">
                    @php
                        $isParentActive = false;
                        $urlWithoutSlash = ltrim($item['url'] ?? '', '/');
                        
                        // Check if current item is active
                        $isItemActive = $urlWithoutSlash && str_starts_with($currentUrl, $urlWithoutSlash);
                        
                        // Check if any child is active
                        if (isset($item['children'])) {
                            foreach ($item['children'] as $childItem) {
                                $childUrlWithoutSlash = ltrim($childItem['url'] ?? '', '/');
                                if ($childUrlWithoutSlash && str_starts_with($currentUrl, $childUrlWithoutSlash)) {
                                    $isParentActive = true;
                                    break;
                                }
                                
                                // Check grandchildren if they exist
                                if (isset($childItem['children'])) {
                                    foreach ($childItem['children'] as $grandChild) {
                                        $grandChildUrlWithoutSlash = ltrim($grandChild['url'] ?? '', '/');
                                        if ($grandChildUrlWithoutSlash && str_starts_with($currentUrl, $grandChildUrlWithoutSlash)) {
                                            $isParentActive = true;
                                            break 2;
                                        }
                                    }
                                }
                            }
                        }
                    @endphp
                    
                    @if (isset($item['children']))
                        <div class="flex items-center justify-between p-3 rounded-lg cursor-pointer hover:bg-[var(--color-royal-blue-700)] transition-all duration-200 {{ $isParentActive ? 'bg-[var(--color-royal-blue-600)]' : '' }}">
                            <div class="flex items-center">
                                <div class="bg-[var(--color-royal-blue-500)] p-2 rounded-md mr-3">
                                    <i class="fas fa-{{ $item['icon'] }} text-white"></i>
                                </div>
                                <span class="font-medium">{{ $item['title'] }}</span>
                            </div>
                            <i class="fas fa-chevron-down text-xs transition-transform duration-300 group-hover:rotate-180 {{ $isParentActive ? 'rotate-180' : '' }}"></i>
                        </div>
                        <ul class="mt-2 ml-6 space-y-2 overflow-hidden transition-all duration-300 {{ $isParentActive ? 'max-h-96' : 'max-h-0' }} group-hover:max-h-96">
                            @foreach ($item['children'] as $child)
                                @php
                                    // Memeriksa apakah pengguna memiliki akses ke submenu ini
                                    $hasChildAccess = isset($child['roles']) && in_array($userRole, $child['roles']);
                                    
                                    // Jika tidak ada roles yang ditentukan, semua pengguna memiliki akses
                                    if (!isset($child['roles'])) {
                                        $hasChildAccess = true;
                                    }
                                    
                                    $childUrlWithoutSlash = ltrim($child['url'] ?? '', '/');
                                    $isChildActive = $childUrlWithoutSlash && str_starts_with($currentUrl, $childUrlWithoutSlash);
                                    
                                    // Check if any grandchild is active
                                    $isChildParentActive = false;
                                    if (isset($child['children'])) {
                                        foreach ($child['children'] as $grandChild) {
                                            $grandChildUrlWithoutSlash = ltrim($grandChild['url'] ?? '', '/');
                                            if ($grandChildUrlWithoutSlash && str_starts_with($currentUrl, $grandChildUrlWithoutSlash)) {
                                                $isChildParentActive = true;
                                                break;
                                            }
                                        }
                                    }
                                @endphp
                                
                                @if ($hasChildAccess)
                                <li>
                                    <a href="{{ $child['url'] }}" class="flex items-center p-2 rounded-lg hover:bg-[var(--color-royal-blue-700)] transition-all duration-200 {{ $isChildActive || $isChildParentActive ? 'bg-[var(--color-royal-blue-700)]' : '' }}">
                                        <i class="fas fa-{{ $child['icon'] }} mr-3 w-5 text-center text-[var(--color-royal-blue-300)]"></i>
                                        <span class="text-sm">{{ $child['title'] }}</span>
                                    </a>
                                </li>
                                @endif
                            @endforeach
                        </ul>
                    @else
                        <a href="{{ $item['url'] }}" class="flex items-center p-3 rounded-lg hover:bg-[var(--color-royal-blue-700)] transition-all duration-200 {{ $isItemActive ? 'bg-[var(--color-royal-blue-600)]' : '' }}">
                            <div class="bg-[var(--color-royal-blue-500)] p-2 rounded-md mr-3">
                                <i class="fas fa-{{ $item['icon'] }} text-white"></i>
                            </div>
                            <span class="font-medium">{{ $item['title'] }}</span>
                        </a>
                    @endif
                </li>
                @endif
            @endforeach
        </ul>
    </nav>
    
    <div class="absolute bottom-0 left-0 w-64 p-4 border-t border-[var(--color-royal-blue-700)]">
        <a href="{{ route('logout') }}" 
           onclick="event.preventDefault(); document.getElementById('logout-form').submit();"
           class="flex items-center p-3 rounded-lg hover:bg-red-700/30 transition-all duration-200 text-red-300">
            <div class="bg-red-500/30 p-2 rounded-md mr-3">
                <i class="fas fa-sign-out-alt text-red-300"></i>
            </div>
            <span class="font-medium">Logout</span>
        </a>
    </div>
</div>

<form id="logout-form" action="{{ route('logout') }}" method="POST" class="hidden">
    @csrf
</form>