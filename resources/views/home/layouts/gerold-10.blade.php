<x-layouts.public :title="__('nav.home')">
    <x-slot:head>
        <x-atoms.json-ld type="Person" :profile="$profile" />
    </x-slot:head>

    <x-organisms.sections.hero :profile="$profile" />
    <x-organisms.sections.contact-cta :profile="$profile" />
    <x-organisms.sections.certifications-marquee :certifications="$certifications" />
    <x-organisms.sections.about :profile="$profile" :stats="$stats" />
    <x-organisms.sections.portfolio :projects="$projects" />
    <x-organisms.sections.testimonials />
    <x-organisms.sections.skills :skills="$skills" />
    <x-organisms.sections.blog />
</x-layouts.public>
