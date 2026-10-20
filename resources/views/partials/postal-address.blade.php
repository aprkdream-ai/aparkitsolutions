@php($a = config('site.contact.address_parts'))
@if (! empty($a['street']) && ! empty($a['locality']))
<address class="postal-address" itemprop="address" itemscope itemtype="https://schema.org/PostalAddress"><span itemprop="streetAddress">{{ $a['street'] }}</span>, <span itemprop="addressLocality">{{ $a['locality'] }}</span>, <span itemprop="addressRegion">{{ $a['region'] }}</span> <span itemprop="postalCode">{{ $a['postal_code'] }}</span>, <span itemprop="addressCountry" content="{{ $a['country'] }}">India</span></address>
@else
<address class="postal-address">{{ config('site.contact.address') }}</address>
@endif
