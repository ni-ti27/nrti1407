
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Site Under Maintenance</title>
    <style>
        /* Add your CSS styles here */
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }
        .maintenance-container {
            text-align: center;
        }
        .maintenance-title {
            font-size: 36px;
            color: #333;
            margin-bottom: 20px;
        }
        .maintenance-animation {
            /* Add animation styles here */
            width: 150px;
            height: 150px;
            background-color: #007bff;
            border-radius: 50%;
            display: inline-block;
            animation: bounce 2s ease-in-out infinite;
        }
        @keyframes bounce {
            0%, 100% {
                transform: translateY(0);
            }
            50% {
                transform: translateY(-20px);
            }
        }
    </style>
</head>
<body>
    <div class="maintenance-container">
        <h1 class="maintenance-title">RTI Site Under Maintenance</h1>
        <div class="maintenance-animation"></div>
    </div>
</body>
</html>
