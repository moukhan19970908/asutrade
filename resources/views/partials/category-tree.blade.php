@foreach($categories as $cat)
    <div class="mb-2">
        <div class="flex items-center justify-between py-1 px-2 rounded hover:bg-gray-50 {{ (isset($category) && ($category->id == $cat->id || $cat->isAncestorOf($category))) ? 'bg-blue-50' : '' }}">
            <a href="{{ route('catalog.category', $cat) }}" 
               class="flex-1 text-gray-700 hover:text-blue-600 {{ (isset($category) && $category->id == $cat->id) ? 'font-bold text-blue-600' : '' }}">
               @if(isset($category) && $category->id == $cat->id)
                   <i class="fas fa-chevron-right text-xs mr-2 text-blue-600"></i>
               @endif
                {{ $cat->name }}
            </a>
            <span class="text-xs text-gray-400 ml-2">({{ $cat->total_products_count }})</span>
        </div>
        
        @if($cat->children->count() > 0)
            <div class="ml-4 border-l border-gray-200 pl-2 mt-1 {{ (isset($category) && ($category->id == $cat->id || $cat->isAncestorOf($category))) ? 'block' : 'hidden' }} category-children">
                @include('partials.category-tree', ['categories' => $cat->children])
            </div>
            <!-- JS to toggle children if needed, but for now we rely on server-side expansion based on active category -->
            <!-- Wait, user wants "dropdown" like behavior? The screenshot shows hierarchy. 
                 Let's make it always expanded or toggleable. 
                 If 'hidden' class is used, it won't show unless active. 
                 Let's make top level always visible, children toggleable?
                 For now, let's remove 'hidden' to show full tree as default or use a simple Alpine/JS if available.
                 Since I don't see Alpine, I'll just leave it open for now or add a toggle button.
            -->
        @endif
    </div>
@endforeach
