<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <style>
        .wrapper {
            width: 400px;
            font-family: 'Roboto', sans-serif;
            margin: 0 auto;
        }

        .skill {
            margin-bottom: 35px;
            position: relative;
            overflow-x: hidden;
        }

        .skill>p {
            font-size: 18px;
            font-weight: 700;
            color: #1a1716;
            margin: 0;
        }

        .skill:before {
            width: 100%;
            height: 10px;
            content: "";
            display: block;
            position: absolute;
            background: #959595;
            bottom: 0;
        }

        .skill-bar {
            width: 100%;
            height: 10px;
            background: #f4392f;
            display: block;
            position: relative;
        }

        .skill-bar span {
            position: absolute;
            border-top: 5px solid #f4392f;
            top: -30px;
            padding: 0;
            font-size: 18px;
            padding: 3px 0;
            font-weight: 500;
        }

        .skill-bar {
            position: relative;
        }

        .skill1 .skill-count1 {
            right: 0;
        }

        .skill1 {
            width: 100%;
        }

        .skill2 {
            width: 50%;
        }

        .skill2 .skill-count2 {
            right: 0;
        }

        .skill3 {
            width: 0%;
        }

        .skill3 .skill-count3 {
            right: 0;
        }
    </style>
</head>

<body>
    <div class="wrapper">
        <h2 class="how-title">Paper Application Progress</h2>

        <div class="skill">
            <p>Reviewer 1</p>
            <div class="skill-bar skill1">
                <span class="skill-count1">100%</span>
            </div>
        </div>
        <div class="skill">
            <p>Reviewer 2</p>
            <div class="skill-bar skill2">
                <span class="skill-count2">50%</span>
            </div>
        </div>
        <div class="skill">
            <p>Reviewer 3</p>
            <div class="skill-bar skill3">
                <span class="skill-count3">0%</span>
            </div>
        </div>
    </div>
</body>

</html>