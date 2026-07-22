<x-layouts.public :title="__('projects.title')">

    <x-molecules.page-breadcrumb :title="__('projects.title')" />

    <section class="py-[60px] md:py-20 lg:py-[100px]">
        <div class="mx-auto max-w-7xl px-6 lg:px-8">
            <livewire:projects.project-filters />
        </div>
    </section>

</x-layouts.public>
