{{-- ============================================================
     Captcha  (DBIM / GIGW 3.0, keyboard accessible)
     Include INSIDE the form, in place of another field:
         @include('partials.captcha')
     Styles: dbim.css section 19.
     Errors: same pattern as the other fields (inline .invalid-feedback,
     aria-invalid, aria-describedby). Hints are in the placeholder.
     When the captcha expires the image is hidden and the input and audio
     button are disabled, until the user reloads it.
     ============================================================ --}}
<div class="mb-3" id="captcha-container">
    <label for="captcha_input" class="form-label fw-bold">
        Captcha <span class="dbim-required" aria-hidden="true">*</span>
    </label>

    <div class="dbim-captcha__challenge">
        <div class="dbim-captcha__image">
            <img id="img" width="200" height="60"
                 alt="Captcha image. Type the characters shown. An audio option is available.">
        </div>

        <button type="button" id="play-audio" class="dbim-captcha__btn"
                title="Play audio captcha" aria-label="Play audio captcha">
            <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32 " viewBox="0 0 66 66" fill="none" style="display: flex; justify-content: center;">
                <g clip-path="url(#clip0_13_127)">
                    <path d="M65.5 30.9687H57.375V22.8437H53.3125V30.9687H45.1875V35.0312H53.3125V43.1563H57.375V35.0312H65.5V30.9687ZM37.0625 61.4375C36.7941 61.4375 36.5284 61.3842 36.2808 61.2806C36.0333 61.1771 35.8087 61.0253 35.6203 60.8342L20.132 45.1875H6.59375C6.05538 45.1875 5.53903 44.9738 5.15816 44.5933C4.77728 44.2128 4.56304 43.6967 4.5625 43.1583V22.8437C4.5625 22.3054 4.77623 21.789 5.15672 21.4082C5.53722 21.0273 6.05335 20.813 6.59172 20.8125H20.1341L35.6183 5.16578C35.9014 4.87949 36.2633 4.68381 36.6579 4.6036C37.0525 4.52339 37.462 4.56227 37.8345 4.7153C38.2069 4.86833 38.5255 5.1286 38.7497 5.46307C38.9739 5.79753 39.0937 6.19108 39.0938 6.59375V59.4063C39.0938 59.945 38.8797 60.4616 38.4988 60.8426C38.1179 61.2235 37.6012 61.4375 37.0625 61.4375ZM8.625 41.125H20.8125C21.4056 41.1232 21.979 41.3377 22.4253 41.7283L35.0312 54.4663V11.5338L22.4253 24.2717C21.9789 24.6622 21.4056 24.8766 20.8125 24.875H8.625V41.125Z" fill="#162F6A"/>
                </g>
                <defs>
                    <clipPath id="clip0_13_127">
                        <rect width="65" height="65" fill="white" transform="translate(0.5 0.5)"/>
                    </clipPath>
                </defs>
            </svg>
        </button>

        <button type="button" id="refresh" class="dbim-captcha__btn"
                title="Get a new captcha" aria-label="Get a new captcha">
             <svg xmlns="http://www.w3.org/2000/svg" height="32 " viewBox="0 -960 960 960" width="32" fill="#2d2d2d" style="display: flex; justify-content: center;"><path d="M480-160q-133 0-226.5-93.5T160-480q0-133 93.5-226.5T480-800q85 0 149 34.5T740-671v-99q0-13 8.5-21.5T770-800q13 0 21.5 8.5T800-770v194q0 13-8.5 21.5T770-546H576q-13 0-21.5-8.5T546-576q0-13 8.5-21.5T576-606h138q-38-60-97-97t-137-37q-109 0-184.5 75.5T220-480q0 109 75.5 184.5T480-220q75 0 140-39.5T717-366q5-11 16.5-16.5t22.5-.5q12 5 16 16.5t-1 23.5q-39 84-117.5 133.5T480-160Z" fill="#162F6A"/></svg>
        </button>
    </div>

    <input type="text" class="form-control @error('captcha') is-invalid @enderror"
           id="captcha_input" name="captcha"
           placeholder="Enter the characters shown in the image"
           autocomplete="off" autocapitalize="off" spellcheck="false"
           required aria-required="true"
           aria-invalid="{{ $errors->has('captcha') ? 'true' : 'false' }}"
           aria-describedby="captchaError">
    <div id="captchaError" class="invalid-feedback @error('captcha') d-block @enderror">@error('captcha'){{ $message }}@enderror</div>

    {{-- Visible countdown. Not a live region: it would be read out every second.
         Expiry is announced once through #captchaStatus. --}}
    <p class="dbim-captcha__timer">Expires in <strong id="timer">2:00</strong> (minutes:seconds)</p>

    {{-- Messages (expired, too many refreshes, audio unavailable, network) --}}
    <div id="captchaStatus" role="status" aria-atomic="true"></div>

    <input type="hidden" id="captcha_token" name="captcha_token">
    <audio id="audio" hidden></audio>
</div>

<script>
(function () {
    var CAPTCHA_SECONDS = 120;
    var REFRESH_URL = '{{ route("security.refresh") }}';
    var timeLeft = 0;
    var timerId = null;
    var audioUnavailable = false;   /* set when the server says audio is missing (503) */

    var img          = document.getElementById('img');
    var imageBox     = document.querySelector('#captcha-container .dbim-captcha__image');
    var input        = document.getElementById('captcha_input');
    var tokenInput   = document.getElementById('captcha_token');
    var audioElement = document.getElementById('audio');
    var refreshBtn   = document.getElementById('refresh');
    var playBtn      = document.getElementById('play-audio');
    var timerDisplay = document.getElementById('timer');
    var statusBox    = document.getElementById('captchaStatus');

    /* ---------- Messages: same alert look as the rest of the site ---------- */
    function clearStatus() { statusBox.textContent = ''; }

    function showStatus(message, type, withReload) {
        clearStatus();
        var box = document.createElement('div');
        box.className = 'alert alert-' + (type || 'warning') + ' mt-2 mb-0';
        var p = document.createElement('p');
        p.className = 'mb-0';
        p.textContent = message;
        box.appendChild(p);
        if (withReload) {
            var b = document.createElement('button');
            b.type = 'button';
            b.className = 'download-btn text-uppercase w-ft';
            b.textContent = 'Reload captcha';
            b.addEventListener('click', refreshCaptcha);
            box.appendChild(b);
        }
        statusBox.appendChild(box);
    }

    /* ---------- Expired / active state ----------
       Expired: image hidden, input and audio disabled, old code and token removed,
       so nobody types a code from an old image. */
    function setExpired(expired) {
        imageBox.classList.toggle('d-none', expired);
        input.disabled = expired;
        playBtn.disabled = expired || audioUnavailable;
        if (expired) {
            img.removeAttribute('src');
            audioElement.removeAttribute('src');
            audioElement.load();
            input.value = '';
            tokenInput.value = '';
        }
    }

    /* ---------- Countdown ---------- */
    function formatTime(s) {
        var m = Math.floor(s / 60), r = s % 60;
        return m + ':' + (r < 10 ? '0' : '') + r;
    }

    window.startTimer = function () {
        clearInterval(timerId);
        timeLeft = CAPTCHA_SECONDS;
        timerDisplay.textContent = formatTime(timeLeft);
        timerId = setInterval(function () {
            timeLeft--;
            timerDisplay.textContent = formatTime(Math.max(timeLeft, 0));
            if (timeLeft <= 0) {
                clearInterval(timerId);
                var hadFocus = (document.activeElement === input || document.activeElement === playBtn);
                setExpired(true);
                showStatus('The captcha has expired. Reload it to get a new code.', 'warning', true);
                if (hadFocus) { statusBox.querySelector('button').focus(); }  /* focus must not be lost with the disabled control */
            }
        }, 1000);
    };

    /* ---------- Refresh ---------- */
    window.refreshCaptcha = async function () {
        if (refreshBtn.classList.contains('is-spinning')) { return; }
        refreshBtn.classList.add('is-spinning');

        try {
            var res = await fetch(REFRESH_URL, { headers: { 'X-Requested-With': 'XMLHttpRequest' } });

            if (res.status === 429) {
                showStatus('You are refreshing too fast. Please wait a moment and try again.');
                return;
            }
            if (res.status === 403) {
                showStatus('Your session has expired. The page will reload.');
                setTimeout(function () { window.location.reload(); }, 3000);
                return;
            }
            if (!res.ok) { throw new Error('Server error ' + res.status); }

            var data = await res.json();
            img.src            = data.captcha_image_url;
            tokenInput.value   = data.captcha_token;
            audioElement.src   = data.captcha_audio;
            audioElement.load();
            input.value = '';
            clearStatus();
            setExpired(false);
            window.startTimer();
        } catch (err) {
            console.error('Captcha error:', err);
            showStatus('Could not load the captcha. Check your internet connection and try again.', 'danger', true);
        } finally {
            /* keep the spinner for at least 600 ms so the click is noticed */
            setTimeout(function () { refreshBtn.classList.remove('is-spinning'); }, 600);
        }
    };

    refreshBtn.addEventListener('click', window.refreshCaptcha);

    /* ---------- Audio ---------- */
    playBtn.addEventListener('click', async function () {
        if (!audioElement.src) { return; }

        try {
            /* ping the audio URL first to see whether it is still valid */
            var response = await fetch(audioElement.src);

            if (response.status === 503) {          /* audio engine missing */
                var info = await response.json().catch(function () { return {}; });
                showStatus(info.message || 'Audio is currently unavailable. Please use the image.');
                audioUnavailable = true;
                playBtn.disabled = true;            /* disabled, not hidden: keeps the layout stable */
                input.focus();                      /* focus must not be lost with the button */
                return;
            }
            if (response.status === 403) {          /* token expired */
                window.refreshCaptcha();
                return;
            }
            if (!response.ok) { throw new Error('Server error ' + response.status); }

            await audioElement.play();
        } catch (err) {
            console.error('Audio error:', err);
            showStatus('Could not play the audio. Reload the captcha and try again.');
        }
    });

    document.addEventListener('DOMContentLoaded', window.refreshCaptcha);
})();
</script>