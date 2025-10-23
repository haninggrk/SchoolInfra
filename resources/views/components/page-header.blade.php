@props(['title', 'description' => null, 'backUrl' => null, 'breadcrumbs' => []])

<div class="mb-6">
    <!-- Breadcrumb -->
    @if(count($breadcrumbs) > 0)
        <x-breadcrumb :items="$breadcrumbs" />
    @endif
    
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div class="flex items-start gap-3">
            @if($backUrl)
                <a href="{{ $backUrl }}" 
                   class="mt-1 flex-shrink-0 w-8 h-8 flex items-center justify-center rounded-lg bg-white border border-gray-300 text-gray-600 hover:bg-gray-50 hover:text-gray-900 transition-colors">
                    <i class="fas fa-arrow-left text-sm"></i>
                </a>
            @endif
            
            <div>
                <h1 class="text-xl md:text-2xl font-bold text-gray-900">{{ $title }}</h1>
                @if($description)
                    <p class="text-sm md:text-base text-gray-600 mt-1">{{ $description }}</p>
                @endif
            </div>
        </div>
        
        <!-- Actions slot -->
        @if(isset($actions))
            <div class="flex flex-wrap gap-2">
                {{ $actions }}
            </div>
        @endif
    </div>
</div>

