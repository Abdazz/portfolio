<x-layouts.public :title="__('nav.home')">
    <x-slot:head>
        <x-atoms.json-ld type="Person" :profile="$profile" />
    </x-slot:head>

    <x-organisms.sections.hero :profile="$profile" :stats="$stats" />
    <x-organisms.sections.about :profile="$profile" :stats="$stats" />
    <x-organisms.sections.services />
    <x-organisms.sections.portfolio :projects="$projects" />
    <x-organisms.sections.experience :experiences="$experiences" :educations="$educations" />
    <x-organisms.sections.certifications-grid :certifications="$certifications" />
    <x-organisms.sections.testimonials />
    <x-organisms.sections.blog />
    <x-organisms.sections.contact-cta :profile="$profile" />
</x-layouts.public>
