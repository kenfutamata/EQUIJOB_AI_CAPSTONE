<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Interview Details for Job Provider</title>
    <style>
        body,
        table,
        td,
        a {
            -webkit-text-size-adjust: 100%;
            -ms-text-size-adjust: 100%;
        }

        table,
        td {
            mso-table-lspace: 0pt;
            mso-table-rspace: 0pt;
        }

        u+#body a {
            color: inherit;
            text-decoration: none;
            font-size: inherit;
            font-family: inherit;
            font-weight: inherit;
            line-height: inherit;
        }
    </style>
</head>

<body id="body" style="margin: 0; padding: 0; background-color: #f1f1f1;">
    <table border="0" cellpadding="0" cellspacing="0" width="100%">
        <tr>
            <td align="center" style="background-color: #f1f1f1; padding: 20px 0;">

                <table border="0" cellpadding="0" cellspacing="0" width="100%" style="max-width: 600px; background-color: #ffffff;">
                    <tr>
                        <td align="left" style="padding: 40px 30px 20px 30px; font-family: Arial, sans-serif; font-size: 16px; line-height: 24px; color: #333333;">
                            <p style="margin: 0;">Hello. {{$jobApplication->jobPosting->companyName}}!</p>
                        </td>
                    </tr>
                    <tr>
                        <td align="left" style="padding: 0 30px 20px 30px; font-family: Arial, sans-serif; font-size: 16px; line-height: 24px; color: #333333;">
                            <p style="margin: 0;"> Please be Informed that your Interview with {{$jobApplication->applicant->firstName}} {{$jobApplication->applicant->lastName}} for the Position for {{$jobApplication->jobPosting->position}} will be tomorrow.</p>
                        </td>
                    </tr>
                    <tr>
                        <td align="left" style="padding: 0 30px 20px 30px; font-family: Arial, sans-serif; font-size: 16px; line-height: 24px; color: #333333;">
                            <p style="margin: 0;">Here are the interview details:.</p>
                        </td>
                    </tr>
                    <tr>
                        <td align="left" style="padding: 0 30px 20px 30px; font-family: Arial, sans-serif; font-size: 16px; line-height: 24px; color: #333333;">
                            <p style="margin: 0;">
                                INTERVIEW DETAILS:
                                <br>
                                Interview Date: {{$jobApplication->interviewDate->format('F j, Y, g:i A')}}
                                <br>
                                Google Meet Link: {{$jobApplication->interviewLink}}
                                <br>
                                Applicant Name: {{$jobApplication->applicant->firstName}} {{$jobApplication->applicant->lastName}}.
                            </p>
                        </td>
                    </tr>
                    <tr>
                    <tr>
                        <td align="left" style="padding: 0 30px 0 30px; font-family: Arial, sans-serif; font-size: 16px; line-height: 24px; color: #333333;">
                            <p style="margin: 0;">Please do not reply on this email</p>
                        </td>
                    </tr>
                    <tr>
                        <td align="left" style="padding: 0 30px 0 30px; font-family: Arial, sans-serif; font-size: 16px; line-height: 24px; color: #333333;">
                            <p style="margin: 0;">From the Teams of</p>
                        </td>
                    </tr>
                    <tr>
                        <td align="left" style="padding: 0 30px 30px 30px; font-family: Arial, sans-serif; font-size: 16px; line-height: 24px; color: #333333;">
                            <p style="margin: 0;">EQUIJOB</p>
                        </td>
                    </tr>
                    <!-- Footer -->
                    <tr>
                        <td align="left" style="padding: 20px 30px 40px 30px; font-family: Arial, sans-serif; font-size: 14px; line-height: 20px; color: #888888;">
                            <p style="margin: 0;">&copy; 2025 EQUIJOB</p>
                        </td>
                    </tr>
                </table>
        </tr>
    </table>
</body>

</html>