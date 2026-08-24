<x-layouts.public :title="__('nav.home')">
    <x-slot:head>
        <x-atoms.json-ld type="Person" :profile="$profile" />
    </x-slot:head>

    <x-organisms.sections.hero :profile="$profile" :stats="$stats" />
    <x-organisms.sections.certifications-marquee :certifications="$certifications" />
    <x-organisms.sections.services />
    <x-organisms.sections.portfolio :projects="$projects" />
    <x-organisms.sections.resume-cta :profile="$profile" />
    <x-organisms.sections.skills :skills="$skills" />
    <x-organisms.sections.testimonials />
    <x-organisms.sections.decorative-text :text="$profile?->full_name" />
    <x-organisms.sections.contact-cta :profile="$profile" />
</x-layouts.public>
