@if ($paginator->hasPages())
    <div class="pagination-container flex justify-end items-center my-4">
        {{ $paginator->links() }}
    </div>
@endif
