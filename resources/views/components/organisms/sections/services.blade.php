@props(['items' => null])
@php($items = $items ?? collect())
@if ($items->isNotEmpty())
    {{-- Reserved slot: filled by the future module sub-project. --}}
@endif
