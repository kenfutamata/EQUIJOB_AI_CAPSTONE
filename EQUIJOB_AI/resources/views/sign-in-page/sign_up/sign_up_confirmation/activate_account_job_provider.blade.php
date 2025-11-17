<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <link rel="stylesheet" href="{{ asset('assets/sign-in/forgot-password/password-request/css/password_request_email_detials.css') }}">
  <title>Activate Job Seeker Applicant Details</title>

</head>

<body id="body" style="margin: 0; padding: 0; background-color: #f1f1f1;">
  <table border="0" cellpadding="0" cellspacing="0" width="100%">
    <tr>
      <td align="center" style="background-color: #f1f1f1; padding: 20px 0;">

        <table border="0" cellpadding="0" cellspacing="0" width="100%" style="max-width: 600px; background-color: #ffffff;">
          <tr>
            <td align="left" style="padding: 40px 30px 20px 30px; font-family: Arial, sans-serif; font-size: 16px; line-height: 24px; color: #333333;">
              <p style="margin: 0;">Hello. {{$maildata['email']}} Good day!</p>
            </td>
          </tr>
          <tr>
            <td align="left" style="padding: 0 30px 20px 30px; font-family: Arial, sans-serif; font-size: 16px; line-height: 24px; color: #333333;">
              <p style="margin: 0;">Please Be informed that Your Job Applicant Account is Ready for Activation. Please click the button below to Activate your Account.</p>
            </td>
          </tr>
          <tr>
            <td align="left" style="padding: 10px 30px 30px 30px;">
              <table border="0" cellspacing="0" cellpadding="0">
                <tr>
                  <td align="center" style="border-radius: 5px; background-color: #1ae82bff;">
                    <form action="{{route('sign-up-job-provider-activate-account', ['id' =>$maildata['id']]) }}" method="post">
                      @csrf
                      @method('GET')
                      <button type="submit" target="_blank" style="font-size: 16px; font-family: Arial, sans-serif; color: #000000ff; text-decoration: none; border-radius: 5px; padding: 12px 25px; border: 1px solid #1ae82bff; display: inline-block; font-weight: bold;">Login</button>
                    </form>
                  </td>
                </tr>
              </table>
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