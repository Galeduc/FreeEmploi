<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FreeEmploi</title>
    <link rel="stylesheet" href="css/style.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="preconnect" href="https://rsms.me/">
    <link rel="stylesheet" href="https://rsms.me/inter/inter.css">
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Archivo+Black&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet"/>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js"></script>
    <style>
        body {
            background-image: url('img/background.png');
            background-size: cover;
            background-repeat: no-repeat;
            background-attachment: fixed;
            background-position: center;
        }
        .ag-format-container {
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        .ag-format-container {
            width: 1142px;
            margin: 0 auto;
        }

        .ag-404_img-box {
            max-width: 700px;
            margin: 50px auto;
            z-index: 10;
            position: relative;
        }
        .ag-404_img {
            max-width: 100%;
        }
        .ag-404_img__first {
            position: absolute;
            top: 0;
            left: 0;
            -webkit-animation: an-upDown 2s infinite;
            -moz-animation: an-upDown 2s infinite;
            -o-animation: an-upDown 2s infinite;
            animation: an-upDown 2s infinite;
        }
        .ag-404_img__last {
            position: absolute;
            top: 0;
            left: 0;
            -webkit-animation: an-upDownInvert 2s infinite;
            -moz-animation: an-upDownInvert 2s infinite;
            -o-animation: an-upDownInvert 2s infinite;
            animation: an-upDownInvert 2s infinite;
        }

        @-webkit-keyframes an-upDown {
            0% {
                -webkit-transform: translateY(-10px);
                transform: translateY(-10px);
            }
            50% {
                -webkit-transform: translateY(0);
                transform: translateY(0);
            }
            100% {
                -webkit-transform: translateY(-10px);
                transform: translateY(-10px);
            }
        }

        @-moz-keyframes an-upDown {
            0% {
                -moz-transform: translateY(-10px);
                transform: translateY(-10px);
            }
            50% {
                -moz-transform: translateY(0);
                transform: translateY(0);
            }
            100% {
                -moz-transform: translateY(-10px);
                transform: translateY(-10px);
            }
        }

        @-o-keyframes an-upDown {
            0% {
                -o-transform: translateY(-10px);
                transform: translateY(-10px);
            }
            50% {
                -o-transform: translateY(0);
                transform: translateY(0);
            }
            100% {
                -o-transform: translateY(-10px);
                transform: translateY(-10px);
            }
        }

        @keyframes an-upDown {
            0% {
                -webkit-transform: translateY(-10px);
                -moz-transform: translateY(-10px);
                -o-transform: translateY(-10px);
                transform: translateY(-10px);
            }
            50% {
                -webkit-transform: translateY(0);
                -moz-transform: translateY(0);
                -o-transform: translateY(0);
                transform: translateY(0);
            }
            100% {
                -webkit-transform: translateY(-10px);
                -moz-transform: translateY(-10px);
                -o-transform: translateY(-10px);
                transform: translateY(-10px);
            }
        }

        @-webkit-keyframes an-upDownInvert {
            0% {
                -webkit-transform: translateY(5px);
                transform: translateY(5px);
            }
            50% {
                -webkit-transform: translateY(0);
                transform: translateY(0);
            }
            100% {
                -webkit-transform: translateY(5px);
                transform: translateY(5px);
            }
        }

        @-moz-keyframes an-upDownInvert {
            0% {
                -moz-transform: translateY(5px);
                transform: translateY(5px);
            }
            50% {
                -moz-transform: translateY(0);
                transform: translateY(0);
            }
            100% {
                -moz-transform: translateY(5px);
                transform: translateY(5px);
            }
        }

        @-o-keyframes an-upDownInvert {
            0% {
                -o-transform: translateY(5px);
                transform: translateY(5px);
            }
            50% {
                -o-transform: translateY(0);
                transform: translateY(0);
            }
            100% {
                -o-transform: translateY(5px);
                transform: translateY(5px);
            }
        }

        @keyframes an-upDownInvert {
            0% {
                -webkit-transform: translateY(5px);
                -moz-transform: translateY(5px);
                -o-transform: translateY(5px);
                transform: translateY(5px);
            }
            50% {
                -webkit-transform: translateY(0);
                -moz-transform: translateY(0);
                -o-transform: translateY(0);
                transform: translateY(0);
            }
            100% {
                -webkit-transform: translateY(5px);
                -moz-transform: translateY(5px);
                -o-transform: translateY(5px);
                transform: translateY(5px);
            }
        }

        @media only screen and (max-width: 767px) {
            .ag-format-container {
                width: 96%;
            }

        }

        @media only screen and (max-width: 639px) {

        }

        @media only screen and (max-width: 479px) {

        }

        @media (min-width: 768px) and (max-width: 979px) {
            .ag-format-container {
                width: 750px;
            }

        }

        @media (min-width: 980px) and (max-width: 1161px) {
            .ag-format-container {
                width: 960px;
            }

        }

    </style>
</head>
<body>
<div class="ag-format-container">
    <div class="ag-404_img-box">
        <img src="https://rawcdn.githack.com/SochavaAG/example-mycode/master/pens/animation-parallax-404/images/error-bot.png" class="ag-404_img ag-404_img__first" alt="Flashcookie">
        <img src="https://rawcdn.githack.com/SochavaAG/example-mycode/master/pens/animation-parallax-404/images/error-med.png" class="ag-404_img" alt="Flashcookie">
        <img src="https://rawcdn.githack.com/SochavaAG/example-mycode/master/pens/animation-parallax-404/images/error-top.png" class="ag-404_img ag-404_img__last" alt="Flashcookie">
    </div>

    <div class="flex justify-center mt-4">
        <a href="index.php" class="bg-orange-900 hover:bg-orange-800 text-white font-bold py-2 px-4 rounded-full">Retour à l'accueil</a>
    </div>
</div>

</body>
<script>
    (function ($) {
        $(function () {



        });
    })(jQuery);
</script>
</body>
</html>
