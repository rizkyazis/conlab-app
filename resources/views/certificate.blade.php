<html>
<head>
    <style type="text/css" media="print">
        body, html {
            margin: 0;
            padding: 0;
            width: 210mm;
            height: 148mm;
        }

        body {
            color: black;
            display: table;
            font-family: sans-serif;
            font-size: 24px;
            text-align: center;
        }
        @media print {
            @page
            {
                margin: 0;  /* this affects the margin in the printer settings */
                size: A5 landscape;
            }
        }

        .container {
            border: 20px solid #4D80E4;
            width: 200mm;
            height: 138mm;
            display: table-cell;
            vertical-align: middle;
        }

        .logo {
            color: #4D80E4;
            padding-bottom: 40px;
        }

        hr {
            border: 1px solid #4D80E4;
            width: 80%;
        }

        .marquee {
            font-style: normal;
            font-weight: bold;
            font-size: 48px;
            line-height: 75px;
            color: #4D80E4;
        }

        .text {
            color: #525252;
            font-size: 16px;
        }

        .person {
            font-style: normal;
            font-weight: normal;
            font-size: 36px;
            line-height: 56px;
            padding: 10px;

            color: #4D80E4;
        }

        .link {
            color: #4D80E4;
            font-size: 16px;
        }
    </style>
</head>
<body>
<td>
    <div class="container">
        <div class="logo">
            <img src="{{asset('images/logo.png')}}" alt="" style="height: 103px">
        </div>
        <hr>
        <div class="marquee">
            Certificate of Completion
        </div>
        <hr>
        <br>
        <div class="text">
            Dear
        </div>
        <div class="person">
            {{$name}}
        </div>
        <div class="text">
            Congratulations of completing <br>
            {{$course}} Course
        </div>
        <br>
        <div class="text">
            {{$date}}<br>
            <span class="link">www.conlab.com</span>
        </div>
        <br>
    </div>
</td>
</body>
<script>
    window.print();
    setTimeout(()=>{
        window.close();
    },2000);
</script>
</html>
