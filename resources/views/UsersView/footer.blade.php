<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>RTI Portal</title>
    <style>
        html, body {
            height: 100%; /* Ensure full height of the viewport */
            margin: 0; /* Remove default margins */
            display: flex; /* Enable Flexbox layout */
            flex-direction: column; /* Stack items vertically */
        }

        .main-content {
            flex: 1; /* This will push the footer to the bottom if the content is short */
        }

        .footer-background {
            background: linear-gradient(90deg, rgba(103, 183, 229, 0.5), rgba(103, 183, 229, 0.2), rgba(103, 183, 229, 0.5));
            width: 100%;
            padding: 40px 0;
        }

        footer a {
            color: #000;
            text-decoration: none;
            font-weight: bold;
        }

        footer p {
            margin-bottom: 5px;
        }

        .footer-email {
            color: blue;
            margin-left: 5px;
        }

        .format-reversed {
            direction: rtl;
            unicode-bidi: bidi-override;
        }

    </style>
</head>
<body>
    <div class="main-content">
        <!-- Your main content goes here -->
    </div>

    <footer>
        <div class="footer-background">
            <div class="container text-center">
                <div class="row justify-content-center">
                    <div class="col-12 col-md-8">
                        <div class="d-flex justify-content-center flex-wrap">
                            <a class="mx-3" href="/" target="_self">Home</a>
                            <a class="mx-3" href="https://www.goa.gov.in/" target="_blank" class="external-link">Govt. of Goa</a>
                            <a class="mx-3" href="https://www.india.gov.in/" target="_blank" class="external-link">National Portal of India</a>
                            <a class="mx-3" href="https://gsic.goa.gov.in/" target="_blank" class="external-link">Complaint & Second Appeal to SIC</a>
                            <a class="mx-3" href="/FAQ" target="_self">FAQ</a>
                            <a class="mx-3" href="/AboutUs" target="_self">About Us</a>
                            <a class="mx-3" href="/Policy" target="_self">Policy</a>
                        </div>
                    </div>

                    <div class="col-12 mt-3">
                        <p>Contents of the site provided by Department of Information & Publicity, Government of Goa</p>
                        <p>Copyright © 2024. All rights reserved.
                            Designed, Developed and Hosted by National Informatics Centre, Goa on the instructions of DIP.
                        </p>
                    </div>
                    <div class="col-12 mt-3 d-flex justify-content-center">
                        <p style="margin-right:20px;">
                            Total Visitors: <strong>{{ $totalHits ?? 0 }}</strong>
                        </p>

                        <p style="margin-right:20px;">
                            Today's Visitors: <strong>{{ $todayHits ?? 0 }}</strong>
                        </p>

                        <p>
                            Today's Unique Visitors: <strong>{{ $todayUnique ?? 0 }}</strong>
                        </p>
                    </div>
                    <div class="col-12 mt-3 text-center">
                        <p class="mb-0">
                            RTI Support Email Id:
                            <span class="footer-email"> @obfuscateEmail('support.rtionline@goa.gov.in')</span>
                        </p>
                    </div>

                    <div class="col-12 mt-3 d-flex justify-content-center">
                        <a class="mx-3" href="/feedback" target="_self">Feedback</a>
                    </div>

                    <div class="col-12 mt-3 d-flex justify-content-center">
                        <p>Page Last Updated on: 16/07/2026</p>
                    </div>


                </div>

                <div class="row justify-content-center">
                </div>
            </div>
        </div>
    </footer>

    <script>
        // Wait until the page is fully loaded
        document.addEventListener('DOMContentLoaded', function() {
            // Get all the external links (links with target="_blank")
            var externalLinks = document.querySelectorAll('a[target="_blank"]');

            // Add click event listener to each external link
            externalLinks.forEach(function(link) {
                link.addEventListener('click', function(event) {
                    // Show confirmation dialog when the user clicks on an external link
                    var userConfirm = confirm('You are about to leave the RTI Portal and visit an external website. Do you want to continue?');

                    // If user cancels, prevent the link from opening
                    if (!userConfirm) {
                        event.preventDefault(); // Prevent the default action (opening the external site)
                    }
                });
            });

            let tooltip;

            document.querySelectorAll('.cursor-tooltip').forEach(el => {

                el.addEventListener('mouseenter', function(e) {
                    tooltip = document.createElement('div');
                    tooltip.className = 'custom-cursor-tooltip';
                    tooltip.innerHTML = el.dataset.tooltip;
                    document.body.appendChild(tooltip);
                });

                el.addEventListener('mousemove', function(e) {
                    if (!tooltip) return;

                    // Tooltip position relative to cursor
                    const offsetX = 15; // distance from cursor
                    const offsetY = 15;

                    tooltip.style.left = (e.clientX + offsetX) + 'px';
                    tooltip.style.top  = (e.clientY + offsetY) + 'px';
                    tooltip.style.opacity = 1;

                    // Arrow points to cursor (bottom-left of tooltip)
                    tooltip.style.setProperty('--arrow-left', '10px');
                    tooltip.style.setProperty('--arrow-top', '-6px'); // above tooltip box
                });

                el.addEventListener('mouseleave', function() {
                    if (tooltip) tooltip.remove();
                    tooltip = null;
                });

            });
        });
    </script>
</body>
</html>
