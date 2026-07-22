@props(['profile' => null])

@php
    $contactItems = collect([
        [
            'icon' => 'flaticon-phone-call',
            'label' => __('contact.phone'),
            'value' => $profile?->phone,
            'href' => $profile?->phone ? 'tel:'.preg_replace('/\s+/', '', $profile->phone) : null,
        ],
        [
            'icon' => 'flaticon-mail-inbox-app',
            'label' => __('contact.email'),
            'value' => $profile?->email,
            'href' => $profile?->email ? 'mailto:'.$profile->email : null,
        ],
        [
            'icon' => 'flaticon-location',
            'label' => __('contact.location'),
            'value' => $profile?->location,
            'href' => null,
        ],
    ])->filter(fn ($item) => filled($item['value']))->values();
@endphp

<section id="contact" class="bg-surface-deep py-[60px] md:py-20 lg:py-[100px] dark:bg-surface-deep">
    <div class="mx-auto max-w-7xl px-6 lg:px-8">
        <div class="flex flex-col-reverse gap-x-6 gap-y-10 md:grid md:grid-cols-12 md:items-center">
            <div class="md:col-span-7 md:col-start-1 lg:col-span-6">
                <div class="rounded-[15px] bg-surface px-[15px] py-[30px] md:px-5 lg:px-[30px] lg:py-10 xl:px-10 dark:bg-surface-muted" data-reveal>
                    <div class="mb-[25px] text-center">
                        <h2 class="gradient-text mb-[15px] text-3xl font-semibold md:text-[35px] lg:text-[40px] xl:text-[45px] xl:leading-[1.2]">
                            {{ __('home.contact_title') }}
                        </h2>
                        <p class="text-text">{{ __('home.contact_lead') }}</p>
                    </div>

                    <livewire:contact-form />
                </div>
            </div>

            @if ($contactItems->isNotEmpty())
                <div class="md:col-span-5 md:col-start-8">
                    <ul class="flex flex-col gap-y-10">
                        @foreach ($contactItems as $item)
                            <li class="flex flex-wrap items-center gap-[25px]" data-reveal>
                                <div class="flex h-[50px] w-[50px] flex-col items-center justify-center rounded-full gradient-primary text-xl leading-none text-white">
                                    <i class="{{ $item['icon'] }} mt-1 leading-none" aria-hidden="true"></i>
                                </div>
                                <div>
                                    <p class="mb-1 text-text">{{ $item['label'] }}</p>
                                    @if ($item['href'])
                                        <a href="{{ $item['href'] }}"
                                           class="text-lg font-medium text-text transition-colors hover:text-accent lg:text-xl">
                                            {{ $item['value'] }}
                                        </a>
                                    @else
                                        <p class="text-lg font-medium text-text lg:text-xl">{{ $item['value'] }}</p>
                                    @endif
                                </div>
                            </li>
                        @endforeach
                    </ul>
                </div>
            @endif
        </div>
    </div>
</section>
