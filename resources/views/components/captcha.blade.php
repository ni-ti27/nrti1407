<div class="container my-3">
    <div class="row justify-content-center">
        <div class="col-12 col-md-8 col-lg-6">

            <div id="captcha-container" class="position-relative py-4" aria-labelledby="captcha_label">
                <h2 id="captcha_label" class="visually-hidden">Security Verification</h2>

                <div id="overlay"
                     class="position-absolute w-100 h-100 d-none flex-column align-items-center justify-content-center"
                     style="z-index: 10; top:0; left:0; background: rgba(255,255,255,0.95); border-radius: 8px;"
                     role="alert"
                     aria-labelledby="expiry_msg">
                    <span id="expiry_msg" class="text-danger small fw-bold mb-2">Captcha Expired</span>
                    <button type="button" id="reload-btn" class="btn btn-dark rounded-1 px-4" onclick="refreshCaptcha()">Reload Captcha</button>
                </div>

                <div class="d-flex justify-content-center align-items-center gap-3 mb-4">
                    <div class="border rounded bg-white p-2 d-flex align-items-center justify-content-center shadow-sm" style="width: 220px; height: 75px;">
                        <img id="img" src="" alt="Visual CAPTCHA challenge" class="img-fluid" style="max-height: 100%;">
                    </div>

                    <div class="btn-group border rounded bg-white shadow-sm">
                        <button type="button" id="play-audio" class="btn btn-light border-end py-3 px-4 shadow-none" aria-label="Play audio captcha">
                            <svg aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" viewBox="0 0 16 16">
                                <path d="M11.536 14.01A8.47 8.47 0 0 0 14.026 8a8.47 8.47 0 0 0-2.49-6.01l-.708.707A7.48 7.48 0 0 1 13.025 8c0 2.071-.84 3.946-2.197 5.303l.708.707z"/><path d="M10.121 12.596A6.48 6.48 0 0 0 12.025 8a6.48 6.48 0 0 0-1.904-4.596l-.707.707A5.48 5.48 0 0 1 11.025 8a5.48 5.48 0 0 1-1.61 3.89l.706.706z"/><path d="M8.707 11.182A4.5 4.5 0 0 0 10.025 8a4.5 4.5 0 0 0-1.318-3.182L8 5.525A3.5 3.5 0 0 1 9.025 8 3.5 3.5 0 0 1 8 10.475l.707.707zM6.717 3.55A.5.5 0 0 1 7 4v8a.5.5 0 0 1-.812.39L3.825 10.5H1.5A.5.5 0 0 1 1 10V6a.5.5 0 0 1 .5-.5h2.325l2.363-1.89a.5.5 0 0 1 .529-.06z"/>
                            </svg>
                        </button>
                        <button type="button" id="refresh" class="btn btn-light py-3 px-4 shadow-none" aria-label="Get new captcha code">
                            <span id="refresh-icon" class="d-flex">
                                <svg aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" viewBox="0 0 16 16">
                                    <path d="M11.534 7h3.932a.25.25 0 0 1 .192.41l-1.966 2.36a.25.25 0 0 1-.384 0l-1.966-2.36a.25.25 0 0 1 .192-.41zm-11 2h3.932a.25.25 0 0 0 .192-.41L2.692 6.23a.25.25 0 0 0-.384 0L.342 8.59A.25.25 0 0 0 .534 9z"/><path fill-rule="evenodd" d="M8 3c-1.552 0-2.94.707-3.857 1.818a.5.5 0 1 1-.771-.636A6.002 6.002 0 0 1 13.917 7H12.9A5.002 5.002 0 0 0 8 3zM3.1 9a5.002 5.002 0 0 0 8.757 2.182.5.5 0 1 1 .771.636A6.002 6.002 0 0 1 2.083 9H3.1z"/>
                                </svg>
                            </span>
                        </button>
                    </div>
                </div>

                <div class="row justify-content-center">
                    <div class="col-12 position-relative">
                        <label for="captcha_input" class="visually-hidden">Enter Captcha Code</label>
                        <input type="text" name="captcha" id="captcha_input"
                               class="form-control text-center shadow-none"
                               placeholder="Type the code above"
                               required autocomplete="off"
                               aria-required="true"
                               style="height: 55px; font-size: 1.2rem; border-color: #ced4da;">

                        <small class="position-absolute end-0 top-100 mt-2 text-muted"
                               style="font-size: 0.85rem;"
                               aria-live="polite"
                               aria-atomic="true">
                            Expires in: <span id="timer" class="fw-bold text-dark">300</span> seconds
                        </small>
                    </div>
                </div>

                <input type="hidden" id="captcha_token" name="captcha_token">
                <audio id="audio" class="d-none"></audio>
            </div>

            @error('captcha')
                <div class="text-danger small text-center mt-4" role="alert">{{ $message }}</div>
            @enderror

        </div>
    </div>
</div>

<style>
    /* Helper class to hide labels visually but keep them for Screen Readers */
    .visually-hidden {
        position: absolute !important;
        width: 1px !important;
        height: 1px !important;
        padding: 0 !important;
        margin: -1px !important;
        overflow: hidden !important;
        clip: rect(0,0,0,0) !important;
        border: 0 !important;
    }

    #captcha_input:focus {
        border-color: #198754 !important;
        outline: 3px solid rgba(25, 135, 84, 0.25) !important; /* Visible focus ring */
    }

    .spinning { animation: spin 0.8s linear infinite; }
    @keyframes spin { from { transform: rotate(0deg); } to { transform: rotate(360deg); } }
</style>

<script>
(function() {
    let timeLeft = 120;
    let timerId = null;

    const img = document.getElementById('img');
    const tokenInput = document.getElementById('captcha_token');
    const audioElement = document.getElementById('audio');
    const refreshBtn = document.getElementById('refresh');
    const refreshIcon = document.getElementById('refresh-icon');
    const overlay = document.getElementById('overlay');
    const timerDisplay = document.getElementById('timer');

    window.startTimer = function() {
        clearInterval(timerId);
        timeLeft = 120;
        overlay.classList.replace('d-flex', 'd-none');
        timerDisplay.textContent = timeLeft;
        timerId = setInterval(() => {
            timeLeft--;
            timerDisplay.textContent = timeLeft;
            if (timeLeft <= 0) {
                clearInterval(timerId);
                overlay.classList.replace('d-none', 'd-flex');
            }
        }, 1000);
    }

    window.refreshCaptcha = function() {
    if (refreshIcon.classList.contains('spinning')) return;

    refreshIcon.classList.add('spinning');

    fetch('{{ route("security.refresh") }}', {
        headers: { 'X-Requested-With': 'XMLHttpRequest' }
    })
    .then(async res => {
        // Handle Rate Limiting
        if (res.status === 429) {
            alert("You are refreshing too fast. Please wait a moment.");
            throw new Error("Throttled");
        }

        // Handle Session/Security issues
        if (res.status === 403) {
            alert("Captcha expired. Page will reload.");
            window.location.reload();
            throw new Error("Forbidden");
        }

        if (!res.ok) {
            throw new Error("Server Error");
        }

        return res.json();
        })
        .then(data => {
            // Update Image
            img.src = data.captcha_image_url;

            // Update Hidden Token Input
            tokenInput.value = data.captcha_token;

            // Update Audio Source and Load it
            audioElement.src = data.captcha_audio;
            audioElement.load();

            // Reset the 5-minute overlay timer
            startTimer();
        })
        .catch(err => {
            if (err.message !== "Throttled" && err.message !== "Forbidden") {
                console.error("Captcha Error:", err);
                alert("Failed to load captcha. Please check your internet connection.");
            }
        })
        .finally(() => {
            // Keep the icon spinning for at least 600ms for visual feedback
            setTimeout(() => refreshIcon.classList.remove('spinning'), 600);
        });
    }

    refreshBtn.addEventListener('click', refreshCaptcha);

   // --- UPDATED AUDIO PLAYBACK WITH 503 ERROR HANDLING ---
    document.getElementById('play-audio').addEventListener('click', async function() {
        if (!audioElement.src) return;

        try {
            // We "ping" the audio URL with a fetch to see if it's still valid
            const response = await fetch(audioElement.src);

            // 1. Handle 503: Audio Engine Missing (Fallback)
            if (response.status === 503) {
                const errorData = await response.json();
                alert("⚠️ " + (errorData.message || "Audio is currently unavailable. Please use the visual challenge."));
                // Optionally hide the audio button so the user doesn't keep clicking it
                document.getElementById('play-audio').style.display = 'none';
                return;
            }

            // 2. Handle 403: Security/Session Issues
            if (response.status === 403) {
                alert("Your security token has expired. Refreshing captcha...");
                refreshCaptcha();
                return;
            }

            // 3. Handle Generic Errors
            if (!response.ok) {
                throw new Error("Server error " + response.status);
            }

            // 4. Play if everything is OK
            // Note: Some browsers require a fresh play() call after fetch
            audioElement.play().catch(e => {
                console.error("Playback interrupted:", e);
            });

        } catch (error) {
            console.error("Audio Error:", error);
            alert("Could not load audio. Please refresh the page.");
        }
    });

    document.addEventListener('DOMContentLoaded', refreshCaptcha);
})();
</script>
