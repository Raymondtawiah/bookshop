<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>New Website Feedback</title>
</head>
<body style="margin:0; padding:0; background:#fafafa; font-family:-apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif;">
    <table width="100%" cellpadding="0" cellspacing="0" style="padding: 24px 0;">
        <tr>
            <td align="center">
                <table width="100%" max-width="600" cellpadding="0" cellspacing="0" style="background:#ffffff; border-radius:16px; padding:28px 24px; max-width:600px; width:100%; box-shadow:0 10px 30px rgba(20,24,38,0.15);">
                    <tr>
                        <td>
                            <h2 style="margin:0 0 8px; font-size:20px; font-weight:700; color:#18181b;">New Website Feedback</h2>
                            <p style="margin:0 0 18px; font-size:14px; color:#6b7280;">A user submitted feedback from the website.</p>

                            <table width="100%" cellpadding="0" cellspacing="0" style="margin-bottom:16px;">
                                <tr>
                                    <td style="padding:10px 12px; background:#fafafa; border-radius:10px;">
                                        <span style="font-size:12px; color:#6b7280; display:block; margin-bottom:4px;">Rating</span>
                                        <span style="font-size:18px; font-weight:700; color:#18181b;">{{ $rating }}/5</span>
                                    </td>
                                    @if($userEmail)
                                    <td style="padding:10px 12px; background:#fafafa; border-radius:10px;">
                                        <span style="font-size:12px; color:#6b7280; display:block; margin-bottom:4px;">Submitted by</span>
                                        <span style="font-size:14px; font-weight:600; color:#18181b; word-break:break-all;">{{ $userEmail }}</span>
                                    </td>
                                    @endif
                                </tr>
                            </table>

                            @if($comment)
                            <table width="100%" cellpadding="0" cellspacing="0" style="margin-bottom:18px;">
                                <tr>
                                    <td style="padding:12px; background:#fafafa; border-radius:10px; border-left:3px solid #4f46e5;">
                                        <span style="font-size:12px; color:#6b7280; display:block; margin-bottom:6px;">Comment</span>
                                        <span style="font-size:14px; color:#18181b; line-height:1.5; white-space:pre-wrap;">{{ $comment }}</span>
                                    </td>
                                </tr>
                            </table>
                            @endif

                            <p style="margin:0; font-size:13px; color:#6b7280;">Best regards,<br>Bookshop System</p>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>
</html>
