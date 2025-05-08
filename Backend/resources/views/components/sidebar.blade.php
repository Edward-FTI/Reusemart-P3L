<button class="btn" type="button" data-bs-toggle="offcanvas" data-bs-target="#offcanvasWithBothOptions"
    aria-controls="offcanvasWithBothOptions">
    <img src="https://img.icons8.com/?size=100&id=8113&format=png&color=FFFFFF" alt="" width="30" height="24"
        class="d-inline-block align-text-top">
</button>

<div class="offcanvas offcanvas-start bg-success" data-bs-scroll="true" tabindex="-1" id="offcanvasWithBothOptions"
    aria-labelledby="offcanvasWithBothOptionsLabel">
    <div class="offcanvas-header">
        <div class="d-flex align-items-center fw-bold fs-5 text-white">
            <img src="{{ 'asset/logo.png' }}" alt="" width="50" class="d-inline-block">
            Reusemart
        </div>
        <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
    </div>

    <div class="offcanvas-body">
        {{-- link sidebar --}}
        <x-link-sidebar></x-link-sidebar>
    </div>
</div>
