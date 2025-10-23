@props(['items' => []])

<nav class="flex items-center space-x-2 text-sm mb-4" aria-label="Breadcrumb">
    <a href="{{ route('dashboard') }}" class="text-gray-500 hover:text-gray-700 transition-colors">
        <i class="fas fa-home"></i>
    </a>
    
    @foreach($items as $index => $item)
        <i class="fas fa-chevron-right text-gray-400 text-xs"></i>
        
        @if($loop->last)
            <span class="text-gray-900 font-medium">{{ $item['label'] }}</span>
        @else
            <a href="{{ $item['url'] }}" class="text-gray-500 hover:text-gray-700 transition-colors">
                {{ $item['label'] }}
            </a>
        @endif
    @endforeach
</nav>

