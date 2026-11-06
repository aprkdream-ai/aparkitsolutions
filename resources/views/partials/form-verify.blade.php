<div class="field captcha-field">
    <label for="f-captcha">Security check: what is <strong data-captcha-question>{{ $captcha['question'] }}</strong>? <span class="req" aria-hidden="true">*</span></label>
    <div class="captcha-row">
        <span class="captcha-q" aria-hidden="true" data-captcha-question>{{ $captcha['question'] }} =</span>
        <input class="input captcha-input" id="f-captcha" name="captcha" type="text" inputmode="numeric" pattern="[0-9]*" maxlength="3" autocomplete="off" required data-msg="Please answer the security question."
            @error('captcha') aria-invalid="true" aria-describedby="f-captcha-error" @enderror>
        <button class="captcha-refresh" type="button" data-captcha-refresh="{{ route('contact.captcha') }}" aria-label="Get a new security question"><x-icon name="refresh" /></button>
    </div>
    @error('captcha')<span class="field-error" id="f-captcha-error">{{ $message }}</span>@enderror
</div>

<div class="field">
    <label class="checkbox">
        <input type="checkbox" name="consent" value="1" required data-msg="Please confirm we may use these details to respond to your enquiry." @checked(old('consent')) @error('consent') aria-invalid="true" aria-describedby="f-consent-error" @enderror>
        <span>I agree that {{ config('site.name') }} may use these details to respond to my enquiry, as described in the <a href="{{ route('legal.privacy') }}">Privacy Policy</a>. <span class="req" aria-hidden="true">*</span></span>
    </label>
    @error('consent')<span class="field-error" id="f-consent-error">{{ $message }}</span>@enderror
</div>
