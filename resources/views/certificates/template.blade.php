<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Certificate of Completion</title>
    <style>
        @page { margin: 0px; }
        body {
            margin: 0px;
            padding: 0px;
            font-family: 'Helvetica', 'Arial', sans-serif;
            width: 100%;
            height: 100%;
        }
        .container {
            width: 100%;
            height: 100%;
            position: relative;
            box-sizing: border-box;
        }

        /* --- MODERN TECH --- */
        .modern-bg {
            background-color: #ffffff;
            border: 1px solid #e0e0e0;
        }
        .modern-top-bar {
            background-color: #4D8EFF;
            height: 20px;
            width: 100%;
        }
        .modern-border {
            position: absolute;
            top: 40px; left: 40px; right: 40px; bottom: 40px;
            border: 2px solid #e0e0e0;
        }
        .modern-content {
            text-align: center;
            padding-top: 150px;
        }
        .modern-title {
            color: #666;
            font-size: 18px;
            letter-spacing: 4px;
            text-transform: uppercase;
        }
        .modern-name {
            font-size: 56px;
            color: #222;
            margin-top: 40px;
            margin-bottom: 20px;
        }
        .modern-line {
            width: 400px;
            height: 2px;
            background-color: #4D8EFF;
            margin: 0 auto 30px auto;
        }
        .modern-text {
            color: #555;
            font-size: 18px;
            line-height: 1.6;
        }
        .modern-event {
            font-weight: bold;
            color: #222;
        }

        /* --- CLASSIC MINIMAL --- */
        .classic-bg {
            background-color: #ffffff;
            border: 10px double #aaaaaa;
            padding: 40px;
        }
        .classic-content {
            text-align: center;
            padding-top: 100px;
            font-family: 'Georgia', serif;
        }
        .classic-title {
            color: #555;
            font-size: 24px;
            letter-spacing: 5px;
            text-transform: uppercase;
            border-bottom: 1px solid #ccc;
            padding-bottom: 10px;
            display: inline-block;
        }
        .classic-name {
            font-size: 64px;
            font-weight: bold;
            color: #111;
            margin: 50px 0 20px 0;
            border-bottom: 1px solid #ddd;
            display: inline-block;
            padding-bottom: 10px;
        }
        .classic-text {
            font-size: 20px;
            color: #444;
            margin-top: 30px;
        }

        /* --- SIDE ACCENT --- */
        .accent-bg {
            background-color: #ffffff;
        }
        .accent-sidebar {
            position: absolute;
            left: 0;
            top: 0;
            bottom: 0;
            width: 250px;
            background-color: #4D8EFF;
        }
        .accent-content {
            margin-left: 300px;
            padding-top: 150px;
            padding-right: 80px;
        }
        .accent-title {
            color: #4D8EFF;
            font-size: 24px;
            letter-spacing: 5px;
            text-transform: uppercase;
            margin-bottom: 60px;
        }
        .accent-name {
            font-size: 52px;
            font-weight: bold;
            color: #222;
            border-left: 6px solid #4D8EFF;
            padding-left: 20px;
            margin: 30px 0;
        }

        /* --- CENTRIC SEAL --- */
        .centric-bg {
            background-color: #ffffff;
            border: 6px solid #4D8EFF;
        }
        .centric-inner-border {
            position: absolute;
            top: 20px; left: 20px; right: 20px; bottom: 20px;
            border: 1px solid #b3d1ff;
        }
        .centric-content {
            text-align: center;
            padding-top: 120px;
        }
        .centric-seal {
            width: 100px;
            height: 100px;
            border: 2px solid #4D8EFF;
            border-radius: 50%;
            margin: 0 auto 30px auto;
        }
        .centric-title {
            color: #4D8EFF;
            font-size: 36px;
            margin-bottom: 10px;
        }
        .centric-name {
            font-family: 'Georgia', serif;
            font-size: 56px;
            color: #111;
            margin: 30px 0 10px 0;
        }

        /* Signatures common */
        .signature-table {
            width: 100%;
            margin-top: 80px;
            text-align: center;
        }
        .signature-line {
            width: 200px;
            border-bottom: 1px solid #777;
            margin: 0 auto 10px auto;
        }
        .signature-role {
            font-size: 12px;
            color: #777;
            text-transform: uppercase;
            letter-spacing: 2px;
        }

    </style>
</head>
<body>

@if($template_style == 'modern')
    <div class="container modern-bg">
        <div class="modern-top-bar"></div>
        <div class="modern-border"></div>
        <div class="modern-content">
            <div class="modern-title">Certificate of Completion</div>
            <div style="color: #888; margin-top: 20px;">This is to certify that</div>
            
            <div class="modern-name">{{ $participantName }}</div>
            <div class="modern-line"></div>
            
            <div class="modern-text">
                has successfully completed all requirements for the<br>
                <span class="modern-event">{{ $eventName }}</span><br>
                held on <span>{{ $eventDate }}</span>.
            </div>

            <table class="signature-table" style="margin-top: 120px;">
                <tr>
                    <td style="width: 33%;">
                        <div class="signature-line"></div>
                        <div class="signature-role">Program Director</div>
                    </td>
                    <td style="width: 33%;">
                        <!-- Seal placeholder -->
                    </td>
                    <td style="width: 33%;">
                        <div class="signature-line"></div>
                        <div class="signature-role">Lead Instructor</div>
                    </td>
                </tr>
            </table>
        </div>
    </div>
@elseif($template_style == 'classic')
    <div class="container classic-bg">
        <div class="classic-content">
            <div class="classic-title">Certificate of Completion</div>
            <div style="color: #666; font-style: italic; margin-top: 20px;">This is to certify that</div>
            
            <div class="classic-name">{{ $participantName }}</div>
            
            <div class="classic-text">
                has successfully completed all requirements for the<br>
                <strong>{{ $eventName }}</strong><br>
                held on {{ $eventDate }}.
            </div>

            <table class="signature-table" style="margin-top: 150px;">
                <tr>
                    <td style="width: 50%;">
                        <div class="signature-line"></div>
                        <div class="signature-role">Program Director</div>
                    </td>
                    <td style="width: 50%;">
                        <div class="signature-line"></div>
                        <div class="signature-role">Lead Instructor</div>
                    </td>
                </tr>
            </table>
        </div>
    </div>
@elseif($template_style == 'accent')
    <div class="container accent-bg">
        <div class="accent-sidebar"></div>
        <div class="accent-content">
            <div class="accent-title">Certificate</div>
            <div style="color: #777;">Presented to</div>
            
            <div class="accent-name">{{ $participantName }}</div>
            
            <div style="color: #555; font-size: 18px; line-height: 1.6; margin-top: 40px;">
                For successful completion of<br>
                <strong style="color: #222;">{{ $eventName }}</strong><br>
                on {{ $eventDate }}.
            </div>

            <table class="signature-table" style="text-align: left; margin-top: 150px;">
                <tr>
                    <td>
                        <div class="signature-line" style="margin-left: 0;"></div>
                        <div class="signature-role" style="font-weight: bold;">Authorized Signature</div>
                    </td>
                </tr>
            </table>
        </div>
    </div>
@elseif($template_style == 'centric')
    <div class="container centric-bg">
        <div class="centric-inner-border"></div>
        <div class="centric-content">
            <div class="centric-seal"></div>
            
            <div class="centric-title">CERTIFICATE</div>
            <div style="color: #888; font-size: 14px; letter-spacing: 4px; text-transform: uppercase;">Of Achievement</div>
            
            <div style="color: #777; font-style: italic; margin-top: 30px;">Proudly presented to</div>
            
            <div class="centric-name">{{ $participantName }}</div>
            <div style="width: 300px; border-bottom: 1px solid #b3d1ff; margin: 0 auto 30px auto;"></div>
            
            <div style="color: #555; font-size: 16px; line-height: 1.6;">
                For demonstrating exceptional skill and completing<br>
                <strong style="color: #222;">{{ $eventName }}</strong><br>
                on {{ $eventDate }}.
            </div>

            <table class="signature-table" style="margin-top: 100px;">
                <tr>
                    <td>
                        <div class="signature-line"></div>
                        <div class="signature-role">Director</div>
                    </td>
                </tr>
            </table>
        </div>
    </div>
@endif

</body>
</html>
