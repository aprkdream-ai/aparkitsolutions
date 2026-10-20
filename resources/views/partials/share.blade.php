@php($shareUrl = urlencode(($seo ?? null)?->canonical ?? url()->current()))
@php($shareTitle = urlencode(($seo ?? null)?->title ?? config('site.name')))
<div class="share {{ $class ?? '' }}">
    <span>Share:</span>
    <a href="https://www.linkedin.com/sharing/share-offsite/?url={{ $shareUrl }}" target="_blank" rel="noopener nofollow"><x-icon name="LinkedIn" /><span class="visually-hidden">Share this page on LinkedIn</span></a>
    <a href="https://www.facebook.com/sharer/sharer.php?u={{ $shareUrl }}" target="_blank" rel="noopener nofollow"><x-icon name="Facebook" /><span class="visually-hidden">Share this page on Facebook</span></a>
    <a href="https://wa.me/?text={{ $shareTitle }}%20{{ $shareUrl }}" target="_blank" rel="noopener nofollow"><x-icon name="whatsapp" /><span class="visually-hidden">Share this page on WhatsApp</span></a>
</div>
