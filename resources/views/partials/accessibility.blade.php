{{-- ============================================================
     Accessibility tool (DBIM / GIGW 3.0)  -  ONE shared partial
     Save as : resources/views/partials/accessibility.blade.php

     Use it in ANY header with a single line, inside the utility icons row
     (where the old accessibility icon used to be):

         @include('partials.accessibility')

     It outputs:
       1. the header icon button (wrapped in <div class="tool">)
       2. the "Accessibility Controls" side panel (moved to <body> by the script,
          so header styles never touch it)
       3. all the logic: dark contrast, invert, saturation, text size,
          highlight links, hide images, large cursor, keyboard arrows

     Needs only: public/css/dbim.css (section "Accessibility Controls side panel")
                 and Bootstrap's offcanvas JS (already in compiled.bundle.min.js)

     Keyboard
       Enter/Space on the icon ... opens the panel (focus moves inside)
       Arrow keys / Home / End ... move between the panel buttons
       Esc or X .................. closes it and returns focus to the icon
     ============================================================ --}}

{{-- ================= 1. Header icon ================= --}}
<div class="tool">
    <button type="button" id="a11yMenuButton" data-bs-toggle="offcanvas"
            data-bs-target="#a11yPanel" aria-controls="a11yPanel"
            aria-haspopup="dialog"
            aria-label="Accessibility panel" title="Accessibility panel">
        <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 64 64" aria-hidden="true" focusable="false" fill="none" style="display: flex; justify-content: center;">
            <g id="a11yIconGlyph">
                <path d="M24.2921 29.7656C21.4604 29.7656 18.7402 29.7704 16.0199 29.7608C15.4887 29.7608 14.9503 29.7419 14.4286 29.6565C12.2182 29.296 10.6482 27.4043 10.6672 25.1638C10.6861 22.9779 12.3652 21.041 14.5305 20.7305C14.9977 20.6641 15.4744 20.6451 15.9488 20.6451C26.6163 20.6403 37.2838 20.6356 47.9515 20.6522C48.6534 20.6522 49.3862 20.7091 50.0505 20.9154C52.0307 21.5294 53.2593 23.4166 53.1265 25.491C52.9982 27.4968 51.4521 29.2771 49.4526 29.6256C48.7294 29.7512 47.9798 29.7536 47.2425 29.7584C44.7025 29.7725 42.1601 29.7632 39.3638 29.7632C39.5203 32.2075 39.3779 34.6163 39.8785 36.8827C41.0523 42.1933 42.5465 47.4328 43.8817 52.7077C44.5102 55.1947 43.1987 57.6104 40.8555 58.4072C38.2041 59.308 35.4126 57.7765 34.7414 54.9837C33.8307 51.188 32.9817 47.3757 32.1043 43.5731C32.0686 43.4165 32.0166 43.2648 31.7985 43.1059C31.3595 45.0168 30.9185 46.9301 30.4774 48.8408C30.0126 50.8584 29.5785 52.8832 29.0758 54.8912C28.4046 57.5632 25.9072 59.0613 23.3008 58.4141C20.7893 57.7883 19.2785 55.2325 19.9402 52.6128C21.1403 47.8523 22.2834 43.0752 23.6376 38.3573C24.4439 35.548 24.24 32.7384 24.2921 29.7656ZM31.9929 23.6868C26.6875 23.6868 21.3822 23.6868 16.0745 23.6868C15.7187 23.6868 15.3582 23.6797 15.012 23.7366C14.2341 23.8599 13.7218 24.4573 13.7147 25.1946C13.7076 25.9462 14.1914 26.5318 14.9788 26.6717C15.2966 26.7285 15.6286 26.7237 15.9535 26.7237C18.7093 26.7285 21.4675 26.7096 24.2234 26.7333C25.8219 26.7475 27.195 27.7456 27.2473 29.1704C27.335 31.6219 27.5222 34.1349 27.0977 36.52C26.429 40.2659 25.2906 43.9285 24.3467 47.6272C23.8629 49.5189 23.3483 51.4013 22.881 53.2981C22.613 54.3837 23.1443 55.2563 24.138 55.4885C25.0582 55.7043 25.8859 55.1379 26.1728 54.0923C26.1966 54.0069 26.2037 53.9168 26.2227 53.8315C27.2899 49.1587 28.3523 44.4859 29.4385 39.8176C29.5643 39.2795 29.7374 38.7128 30.0433 38.2672C30.5699 37.5037 31.3761 37.1933 32.3035 37.3664C33.3779 37.5677 34.0062 38.2435 34.2505 39.3032C35.3534 44.1184 36.4633 48.9333 37.571 53.7485C37.5899 53.8339 37.5945 53.9264 37.6158 54.0117C37.8958 55.1211 38.7259 55.7187 39.6697 55.4957C40.6942 55.2539 41.1947 54.3624 40.9054 53.2173C40.1987 50.4365 39.4777 47.6579 38.7521 44.8816C37.4878 40.0477 36.0009 35.2659 36.4419 30.1448C36.6363 27.8712 37.623 26.7285 39.9307 26.7213C42.5678 26.7144 45.2075 26.7213 47.8449 26.7192C48.1411 26.7192 48.4401 26.7237 48.7294 26.6741C49.5974 26.527 50.1334 25.8798 50.0859 25.0785C50.0385 24.2985 49.4907 23.7722 48.6227 23.6963C48.3286 23.6702 48.0297 23.6797 47.7334 23.6797C42.4873 23.6797 37.2411 23.6797 31.9929 23.6797V23.6868Z" fill="#162F6A"/>
                <path d="M31.6523 17.6787C28.1841 17.6715 25.4626 14.9253 25.4796 11.4504C25.4965 8.07201 28.3001 5.3138 31.6982 5.33311C35.0867 5.35482 37.8323 8.13473 37.8251 11.5397C37.8179 14.9519 35.0699 17.6859 31.6523 17.6787ZM34.7339 11.4938C34.7219 9.7974 33.2958 8.4002 31.6041 8.42433C29.9435 8.44844 28.5731 9.83841 28.5707 11.4962C28.5659 13.2313 29.9195 14.5826 31.6547 14.5802C33.395 14.5778 34.7483 13.2216 34.7339 11.4938Z" fill="#162F6A"/>
            </g>
        </svg>
    </button>
</div>

{{-- ================= 2. Side panel =================
     No backdrop + page scroll allowed, so changes can be seen live. --}}
<div class="offcanvas offcanvas-end dbim-a11y" tabindex="-1" id="a11yPanel"
     aria-labelledby="a11yPanelTitle" data-bs-backdrop="false" data-bs-scroll="true">

    <div class="offcanvas-header">
        <h2 class="offcanvas-title d-flex align-items-center gap-2" id="a11yPanelTitle">
        <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32 " viewBox="0 0 64 64" fill="none" style="display: flex; justify-content: center;">
            <path d="M24.2921 29.7656C21.4604 29.7656 18.7402 29.7704 16.0199 29.7608C15.4887 29.7608 14.9503 29.7419 14.4286 29.6565C12.2182 29.296 10.6482 27.4043 10.6672 25.1638C10.6861 22.9779 12.3652 21.041 14.5305 20.7305C14.9977 20.6641 15.4744 20.6451 15.9488 20.6451C26.6163 20.6403 37.2838 20.6356 47.9515 20.6522C48.6534 20.6522 49.3862 20.7091 50.0505 20.9154C52.0307 21.5294 53.2593 23.4166 53.1265 25.491C52.9982 27.4968 51.4521 29.2771 49.4526 29.6256C48.7294 29.7512 47.9798 29.7536 47.2425 29.7584C44.7025 29.7725 42.1601 29.7632 39.3638 29.7632C39.5203 32.2075 39.3779 34.6163 39.8785 36.8827C41.0523 42.1933 42.5465 47.4328 43.8817 52.7077C44.5102 55.1947 43.1987 57.6104 40.8555 58.4072C38.2041 59.308 35.4126 57.7765 34.7414 54.9837C33.8307 51.188 32.9817 47.3757 32.1043 43.5731C32.0686 43.4165 32.0166 43.2648 31.7985 43.1059C31.3595 45.0168 30.9185 46.9301 30.4774 48.8408C30.0126 50.8584 29.5785 52.8832 29.0758 54.8912C28.4046 57.5632 25.9072 59.0613 23.3008 58.4141C20.7893 57.7883 19.2785 55.2325 19.9402 52.6128C21.1403 47.8523 22.2834 43.0752 23.6376 38.3573C24.4439 35.548 24.24 32.7384 24.2921 29.7656ZM31.9929 23.6868C26.6875 23.6868 21.3822 23.6868 16.0745 23.6868C15.7187 23.6868 15.3582 23.6797 15.012 23.7366C14.2341 23.8599 13.7218 24.4573 13.7147 25.1946C13.7076 25.9462 14.1914 26.5318 14.9788 26.6717C15.2966 26.7285 15.6286 26.7237 15.9535 26.7237C18.7093 26.7285 21.4675 26.7096 24.2234 26.7333C25.8219 26.7475 27.195 27.7456 27.2473 29.1704C27.335 31.6219 27.5222 34.1349 27.0977 36.52C26.429 40.2659 25.2906 43.9285 24.3467 47.6272C23.8629 49.5189 23.3483 51.4013 22.881 53.2981C22.613 54.3837 23.1443 55.2563 24.138 55.4885C25.0582 55.7043 25.8859 55.1379 26.1728 54.0923C26.1966 54.0069 26.2037 53.9168 26.2227 53.8315C27.2899 49.1587 28.3523 44.4859 29.4385 39.8176C29.5643 39.2795 29.7374 38.7128 30.0433 38.2672C30.5699 37.5037 31.3761 37.1933 32.3035 37.3664C33.3779 37.5677 34.0062 38.2435 34.2505 39.3032C35.3534 44.1184 36.4633 48.9333 37.571 53.7485C37.5899 53.8339 37.5945 53.9264 37.6158 54.0117C37.8958 55.1211 38.7259 55.7187 39.6697 55.4957C40.6942 55.2539 41.1947 54.3624 40.9054 53.2173C40.1987 50.4365 39.4777 47.6579 38.7521 44.8816C37.4878 40.0477 36.0009 35.2659 36.4419 30.1448C36.6363 27.8712 37.623 26.7285 39.9307 26.7213C42.5678 26.7144 45.2075 26.7213 47.8449 26.7192C48.1411 26.7192 48.4401 26.7237 48.7294 26.6741C49.5974 26.527 50.1334 25.8798 50.0859 25.0785C50.0385 24.2985 49.4907 23.7722 48.6227 23.6963C48.3286 23.6702 48.0297 23.6797 47.7334 23.6797C42.4873 23.6797 37.2411 23.6797 31.9929 23.6797V23.6868Z" fill="currentColor"/>
            <path d="M31.6523 17.6787C28.1841 17.6715 25.4626 14.9253 25.4796 11.4504C25.4965 8.07201 28.3001 5.3138 31.6982 5.33311C35.0867 5.35482 37.8323 8.13473 37.8251 11.5397C37.8179 14.9519 35.0699 17.6859 31.6523 17.6787ZM34.7339 11.4938C34.7219 9.7974 33.2958 8.4002 31.6041 8.42433C29.9435 8.44844 28.5731 9.83841 28.5707 11.4962C28.5659 13.2313 29.9195 14.5826 31.6547 14.5802C33.395 14.5778 34.7483 13.2216 34.7339 11.4938Z"  fill="currentColor"/>
        </svg>
            Accessibility Controls
        </h2>
        <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close accessibility panel"></button>
    </div>

    <div class="offcanvas-body">

        {{-- Reset buttons --}}
        <div class="d-flex flex-wrap gap-2 mb-3">
            <button type="button" class="a11y-btn a11y-reset" id="a11yResetFilter">Reset Filter</button>
            <button type="button" class="a11y-btn a11y-reset" id="fontReset">Resize Text</button>
        </div>

        {{-- Option buttons --}}
        <div class="row g-2" role="group" aria-label="Accessibility options">
            <div class="col-6">
                <button type="button" class="a11y-btn" id="themeToggle" aria-pressed="false">
                    <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32 " viewBox="0 0 66 55" fill="none" style="display: flex; justify-content: center;">
                        <path d="M33 43.3928L37.3402 39.0526H44.7895V31.6034L49.1297 27.2632L44.7895 22.923V15.4737H37.3402L33 11.1335L28.6598 15.4737H21.2105V22.923L16.8703 27.2632L21.2105 31.6034V39.0526H28.6598L33 43.3928ZM33 36.5263V18C35.5695 18 37.7557 18.9016 39.5583 20.7048C41.3615 22.5075 42.2632 24.6936 42.2632 27.2632C42.2632 29.8327 41.3615 32.0188 39.5583 33.8215C37.7557 35.6247 35.5695 36.5263 33 36.5263ZM7.08926 52.5263C5.38765 52.5263 3.94737 51.9368 2.76842 50.7579C1.58947 49.5789 1 48.1387 1 46.4371V8.08926C1 6.38765 1.58947 4.94737 2.76842 3.76842C3.94737 2.58947 5.38765 2 7.08926 2H58.9107C60.6124 2 62.0526 2.58947 63.2316 3.76842C64.4105 4.94737 65 6.38765 65 8.08926V46.4371C65 48.1387 64.4105 49.5789 63.2316 50.7579C62.0526 51.9368 60.6124 52.5263 58.9107 52.5263H7.08926ZM7.08926 47.4737H58.9107C59.1701 47.4737 59.4076 47.3656 59.6232 47.1495C59.8393 46.9339 59.9474 46.6964 59.9474 46.4371V8.08926C59.9474 7.82989 59.8393 7.59242 59.6232 7.37684C59.4076 7.1607 59.1701 7.05263 58.9107 7.05263H7.08926C6.82989 7.05263 6.59242 7.1607 6.37684 7.37684C6.1607 7.59242 6.05263 7.82989 6.05263 8.08926V46.4371C6.05263 46.6964 6.1607 46.9339 6.37684 47.1495C6.59242 47.3656 6.82989 47.4737 7.08926 47.4737Z" fill="currentColor" fill-opacity="0.866667"/>
                    </svg>
                    <span>Dark Contrast</span>
                </button>
            </div>
            <div class="col-6">
                <button type="button" class="a11y-btn" id="a11yInvert" aria-pressed="false">
                    <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32 " viewBox="0 0 66 66" fill="none" style="display: flex; justify-content: center;">
                        <path d="M33.0059 65C28.5798 65 24.4195 64.1601 20.5251 62.4804C16.6306 60.8007 13.2431 58.5211 10.3625 55.6417C7.48196 52.7622 5.20126 49.3761 3.52042 45.4834C1.84014 41.5906 1 37.4314 1 33.0059C1 28.5798 1.83986 24.4195 3.51958 20.5251C5.1993 16.6306 7.47888 13.2431 10.3583 10.3625C13.2378 7.48196 16.6239 5.20126 20.5166 3.52042C24.4094 1.84014 28.5686 1 32.9941 1C37.4202 1 41.5805 1.83986 45.475 3.51958C49.3694 5.1993 52.7569 7.47888 55.6375 10.3583C58.518 13.2378 60.7987 16.6239 62.4796 20.5166C64.1599 24.4094 65 28.5686 65 32.9941C65 37.4202 64.1601 41.5805 62.4804 45.475C60.8007 49.3694 58.5211 52.7569 55.6417 55.6375C52.7622 58.518 49.3761 60.7987 45.4834 62.4796C41.5906 64.1599 37.4314 65 33.0059 65ZM35.5263 59.7916C42.3367 59.1443 48.1093 56.2974 52.8442 51.2509C57.5797 46.2051 59.9474 40.1214 59.9474 33C59.9474 25.8786 57.5906 19.8056 52.8771 14.7811C48.1635 9.75649 42.3799 6.89895 35.5263 6.20842V59.7916Z" fill="currentColor"/>
                    </svg>
                    <span>Invert</span>
                </button>
            </div>
            <div class="col-6">
                <button type="button" class="a11y-btn" id="a11ySaturation" aria-pressed="false">
                    <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32 " viewBox="0 0 58 68" fill="none" style="display: flex; justify-content: center;">
                        <path d="M28.9546 66C21.472 66 15.1089 63.4139 9.86534 58.2416C4.62178 53.0699 2 46.8024 2 39.439C2 35.6935 2.70861 32.2586 4.12582 29.1343C5.54243 26.0105 7.46489 23.2001 9.89319 20.7029L28.9546 2L48.0159 20.7029C50.4442 23.2001 52.3667 26.0279 53.7833 29.1864C55.2005 32.3448 55.9091 35.7624 55.9091 39.439C55.9091 46.8024 53.2873 53.0699 48.0438 58.2416C42.8002 63.4139 36.4371 66 28.9546 66ZM28.9546 60.6091V9.57513L13.6803 24.6697C11.5838 26.6463 10.0115 28.8833 8.96326 31.3805C7.91503 33.8777 7.39091 36.5638 7.39091 39.439C7.39091 45.2492 9.48738 50.2301 13.6803 54.3817C17.8732 58.5333 22.9647 60.6091 28.9546 60.6091Z" fill="currentColor"/>
                    </svg>
                    <span>Saturation</span>
                </button>
            </div>
            <div class="col-6">
                <button type="button" class="a11y-btn" id="fontIncrease">
                    <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32 " viewBox="0 0 66 45" fill="none" style="display: flex; justify-content: center;">
                        <path d="M1 42.4031L16.7699 2H21.9498L37.7197 42.4031H32.3206L28.2459 31.6177H10.3578L6.28308 42.4031H1ZM11.9814 27.1397H26.623L19.5001 8.28493H19.1522L11.9814 27.1397ZM51.5323 33.4246V24.4462H42.5538V19.9569H51.5323V10.9785H56.0215V19.9569H65V24.4462H56.0215V33.4246H51.5323Z" fill="currentColor"/>
                    </svg>
                    <span>Text Size Increase</span>
                </button>
            </div>
            <div class="col-6">
                <button type="button" class="a11y-btn" id="fontDecrease">
                    <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32 " viewBox="0 0 66 45" fill="none" style="display: flex; justify-content: center;">
                        <path d="M1 42.4031L16.7699 2H21.9498L37.7197 42.4031H32.3206L28.2459 31.6177H10.3578L6.28308 42.4031H1ZM11.9814 27.1397H26.623L19.5091 8.28493H19.1522L11.9814 27.1397ZM42.5538 24.4462V19.9569H65V24.4462H42.5538Z" fill="currentColor"/>
                    </svg>
                    <span>Text Size Decrease</span>
                </button>
            </div>
            <div class="col-6">
                <button type="button" class="a11y-btn" id="a11yLinks" aria-pressed="false">
                    <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32 " viewBox="0 0 66 35" fill="none" style="display: flex; justify-content: center;">
                        <path d="M28.984 32.5752H16.2876C12.058 32.5752 8.45263 31.0846 5.47158 28.1036C2.49053 25.1231 1 21.5183 1 17.2893C1 13.0602 2.49053 9.45488 5.47158 6.47326C8.45263 3.49109 12.058 2 16.2876 2H28.984V7.05263H16.2876C13.4587 7.05263 11.0458 8.05137 9.04884 10.0488C7.05137 12.0463 6.05263 14.4592 6.05263 17.2876C6.05263 20.1159 7.05137 22.5288 9.04884 24.5263C11.0458 26.5238 13.4587 27.5225 16.2876 27.5225H28.984V32.5752ZM20.3684 19.8139V14.7613H45.6316V19.8139H20.3684ZM37.016 32.5752V27.5225H49.7124C52.5413 27.5225 54.9542 26.5238 56.9512 24.5263C58.9486 22.5288 59.9474 20.1159 59.9474 17.2876C59.9474 14.4592 58.9486 12.0463 56.9512 10.0488C54.9542 8.05137 52.5413 7.05263 49.7124 7.05263H37.016V2H49.7124C53.942 2 57.5474 3.49052 60.5284 6.47158C63.5095 9.45207 65 13.0568 65 17.2859C65 21.5149 63.5095 25.1203 60.5284 28.1019C57.5474 31.0841 53.942 32.5752 49.7124 32.5752H37.016Z" fill="currentColor"/>
                    </svg>
                    <span>Highlight Links</span>
                </button>
            </div>
            <div class="col-6">
                <button type="button" class="a11y-btn" id="a11yImages" aria-pressed="false">
                <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32 " viewBox="0 0 66 66" fill="none" style="display: flex; justify-content: center;">
                    <path d="M62.6971 49.8543L57.7623 44.9195V9.25013C57.7623 8.99681 57.6568 8.76488 57.4457 8.55433C57.2351 8.34323 57.0032 8.23768 56.7499 8.23768H21.0804L16.1457 3.3029H56.7499C58.4118 3.3029 59.8185 3.87862 60.9699 5.03007C62.1214 6.18152 62.6971 7.58821 62.6971 9.25013V49.8543ZM61.5333 65L55.7629 59.2304H12.7168C11.0549 59.2304 9.6482 58.6547 8.49675 57.5032C7.34531 56.3518 6.76958 54.9451 6.76958 53.2832V10.2371L1 4.46669L4.46668 1L65 61.5333L61.5333 65ZM17.4616 46.8935L25.6229 36.0748L32.2026 44.4894L36.0559 39.5735L11.7044 15.222V53.2832C11.7044 53.5365 11.8099 53.7684 12.021 53.979C12.2316 54.1901 12.4635 54.2956 12.7168 54.2956H50.778L43.3758 46.8935H17.4616Z" fill="currentColor"/>
                </svg>
                <span>Hide Images</span>
                </button>
            </div>
            <div class="col-6">
                <button type="button" class="a11y-btn" id="a11yCursor" aria-pressed="false">
                    <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32 " viewBox="0 0 66 65" fill="none" style="display: flex; justify-content: center;">
                        <path d="M1 34.7931V29.1714H12.2434V34.7931H1ZM13.0941 55.8098L9.14394 51.7444L17.0143 43.874L21.0797 47.8242L13.0941 55.8098ZM17.0143 20.0906L9.14394 12.2202L13.0941 8.1548L21.0797 16.1404L17.0143 20.0906ZM59.0185 62.9015L41.7928 45.6758L37.6852 58.1446L27.955 25.8565L60.3874 35.5858L48.0347 39.9538L65 56.9191L59.0185 62.9015ZM31.1987 13.2434V2H36.8204V13.2434H31.1987ZM51.0039 20.0906L46.9394 16.1404L54.925 8.1548L58.8742 12.105L51.0039 20.0906Z" fill="currentColor"/>
                    </svg>
                    <span id="a11yCursorLabel">Large Cursor</span>
                </button>
            </div>
        </div>
    </div>
</div>

{{-- ================= 3. Logic ================= --}}
<script>
    (function () {
        /* Safety: never run twice on one page */
        if (window.__dbimA11yReady) { return; }
        window.__dbimA11yReady = true;

        var root = document.documentElement;

        /* Move the panel to <body>: header CSS (icon fill etc.) must not reach it,
           and it is never clipped by the sticky header */
        var panel = document.getElementById('a11yPanel');
        if (panel && panel.parentNode !== document.body) { document.body.appendChild(panel); }

        /* Screen-reader announcements: use the header's #dbimStatus, or create one */
        var status = document.getElementById('dbimStatus');
        if (!status) {
            status = document.createElement('div');
            status.id = 'dbimStatus';
            status.className = 'visually-hidden';
            status.setAttribute('role', 'status');
            status.setAttribute('aria-live', 'polite');
            status.setAttribute('aria-atomic', 'true');
            document.body.appendChild(status);
        }
        function announce(msg) {
            status.textContent = '';
            setTimeout(function () { status.textContent = msg; }, 50);
        }

        var store = {
            get: function (k) { try { return localStorage.getItem(k); } catch (e) { return null; } },
            set: function (k, v) { try { localStorage.setItem(k, v); } catch (e) {} }
        };

        /* ---- Theme: standard / DBIM dark contrast ---- */
        var toggle = document.getElementById('themeToggle');

        function applyTheme(theme, speak) {
            var dark = theme === 'dark';
            if (dark) { root.setAttribute('data-theme', 'dark'); }
            else      { root.removeAttribute('data-theme'); }
            root.setAttribute('data-bs-theme', dark ? 'dark' : 'light');
            toggle.setAttribute('aria-pressed', dark ? 'true' : 'false');
            store.set('dbim-theme', theme);
            if (speak) { announce(dark ? 'Dark contrast on' : 'Dark contrast off'); }
        }
        applyTheme(store.get('dbim-theme') === 'dark' ? 'dark' : 'light', false);
        toggle.addEventListener('click', function () {
            applyTheme(root.getAttribute('data-theme') === 'dark' ? 'light' : 'dark', true);
        });

        /* ---- Text size: A+ / A- / Resize Text (reset) ---- */
        var size = parseInt(store.get('dbim-font-size') || '100', 10);
        function applySize(s, speak) {
            size = Math.min(130, Math.max(80, s));
            root.style.fontSize = size + '%';
            store.set('dbim-font-size', size);
            if (speak) { announce('Text size ' + size + ' percent'); }
        }
        applySize(size, false);

        var fDec = document.getElementById('fontDecrease');
        var fRes = document.getElementById('fontReset');
        var fInc = document.getElementById('fontIncrease');
        fInc.addEventListener('click', function () { applySize(size + 10, true); });
        fDec.addEventListener('click', function () { applySize(size - 10, true); });
        fRes.addEventListener('click', function () { applySize(100, true); });

        /* ---- Invert, saturation, highlight links, hide images, cursor ---- */
        var opts = { invert: false, sat: 0, links: false, images: false, cursor: false };
        try {
            var saved = JSON.parse(store.get('dbim-a11y-opts') || '{}');
            Object.keys(opts).forEach(function (k) { if (k in saved) { opts[k] = saved[k]; } });
        } catch (e) {}

        var satFilter = ['', 'saturate(0.5)', 'saturate(2)', 'grayscale(1)'];
        var satText   = ['Normal saturation', 'Low saturation', 'High saturation', 'Monochrome'];
        var elResetFilter = document.getElementById('a11yResetFilter');
        var elInvert = document.getElementById('a11yInvert');
        var elSat    = document.getElementById('a11ySaturation');
        var elLinks  = document.getElementById('a11yLinks');
        var elImages = document.getElementById('a11yImages');
        var elCursor = document.getElementById('a11yCursor');
        var elCursorLabel = document.getElementById('a11yCursorLabel');

        function pressed(el, on) { el.setAttribute('aria-pressed', on ? 'true' : 'false'); }

        function applyOpts(msg) {
            var f = [];
            if (opts.invert) { f.push('invert(1)'); }
            if (satFilter[opts.sat]) { f.push(satFilter[opts.sat]); }
            root.style.filter = f.join(' ');
            root.classList.toggle('dbim-highlight-links', opts.links);
            root.classList.toggle('dbim-hide-images', opts.images);
            root.classList.toggle('dbim-big-cursor', opts.cursor);
            pressed(elInvert, opts.invert);
            pressed(elSat, opts.sat > 0);
            pressed(elLinks, opts.links);
            pressed(elImages, opts.images);
            pressed(elCursor, opts.cursor);
            elCursorLabel.textContent = opts.cursor ? 'Default Cursor' : 'Large Cursor';
            store.set('dbim-a11y-opts', JSON.stringify(opts));
            if (msg) { announce(msg); }
        }
        applyOpts(null);

        elInvert.addEventListener('click', function () { opts.invert = !opts.invert; applyOpts(opts.invert ? 'Invert colours on' : 'Invert colours off'); });
        elSat.addEventListener('click',    function () { opts.sat = (opts.sat + 1) % satFilter.length; applyOpts(satText[opts.sat]); });
        elLinks.addEventListener('click',  function () { opts.links = !opts.links; applyOpts(opts.links ? 'Highlight links on' : 'Highlight links off'); });
        elImages.addEventListener('click', function () { opts.images = !opts.images; applyOpts(opts.images ? 'Images hidden' : 'Images shown'); });
        elCursor.addEventListener('click', function () { opts.cursor = !opts.cursor; applyOpts(opts.cursor ? 'Large cursor on' : 'Default cursor'); });

        /* Reset Filter: dark contrast + invert + saturation back to normal */
        elResetFilter.addEventListener('click', function () {
            applyTheme('light', false);
            opts.invert = false;
            opts.sat = 0;
            applyOpts('Filters reset');
        });

        /* ---- Keyboard inside the panel: arrows / Home / End ----
             Row 1 = Reset Filter, Resize Text. Then a 2-column grid. */
        var panelBtns = [elResetFilter, fRes, toggle, elInvert, elSat, fInc, fDec, elLinks, elImages, elCursor];
        panelBtns.forEach(function (btn, i) {
            btn.addEventListener('keydown', function (e) {
                var last = panelBtns.length - 1, n = null;
                if (e.key === 'ArrowRight')     { n = Math.min(i + 1, last); }
                else if (e.key === 'ArrowLeft') { n = Math.max(i - 1, 0); }
                else if (e.key === 'ArrowDown') { n = i <= 1 ? i + 2 : Math.min(i + 2, last); }
                else if (e.key === 'ArrowUp')   { n = i <= 1 ? i : i - 2; }
                else if (e.key === 'Home')      { n = 0; }
                else if (e.key === 'End')       { n = last; }
                if (n !== null) { e.preventDefault(); panelBtns[n].focus(); }
            });
        });

        /* Move focus into the panel when it opens */
        panel.addEventListener('shown.bs.offcanvas', function () {
            elResetFilter.focus();
        });
    })();
</script>