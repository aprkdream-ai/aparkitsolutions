<?php

namespace App\Support;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Str;
use Throwable;

/**
 * Self-hosted arithmetic captcha for the contact form.
 *
 * Each rendered form carries its own encrypted challenge (answer + nonce + timestamp), so several
 * open tabs, browser prefetching, or the Back button can't invalidate the question a visitor sees.
 * A challenge is single-use (nonce stored in the cache) and expires after MAX_AGE seconds.
 */
class Captcha
{
    public const MAX_AGE = 7200;

    /** @return array{question: string, token: string, answer: int} */
    public static function make(): array
    {
        if (random_int(0, 1)) {
            [$a, $b] = [random_int(2, 9), random_int(1, 9)];
            [$question, $answer] = ["{$a} + {$b}", $a + $b];
        } else {
            [$a, $b] = [random_int(6, 15), random_int(1, 5)];
            [$question, $answer] = ["{$a} − {$b}", $a - $b];
        }

        $token = Crypt::encryptString(json_encode(['a' => $answer, 'n' => Str::random(20), 't' => now()->getTimestamp()]));

        return compact('question', 'token', 'answer');
    }

    /** Checks the answer against the form's own token and consumes the token (single use). */
    public static function verify(mixed $input, mixed $token): bool
    {
        try {
            $data = json_decode(Crypt::decryptString((string) $token), true, flags: JSON_THROW_ON_ERROR);
        } catch (Throwable) {
            return false;
        }

        if (! isset($data['a'], $data['n'], $data['t']) || now()->getTimestamp() - $data['t'] > self::MAX_AGE) {
            return false;
        }

        // First use wins; a replayed token is rejected even with the right answer.
        if (! Cache::add('captcha:'.$data['n'], 1, self::MAX_AGE)) {
            return false;
        }

        return is_numeric($input) && (int) $input === (int) $data['a'];
    }

    /** Normalises user input: trims spaces and converts Devanagari digits (०-९) to 0-9. */
    public static function normalise(mixed $input): mixed
    {
        if (! is_string($input)) {
            return $input;
        }

        return strtr(preg_replace('/\s+/u', '', $input), array_combine(
            ['०', '१', '२', '३', '४', '५', '६', '७', '८', '९'], range(0, 9)
        ));
    }
}
